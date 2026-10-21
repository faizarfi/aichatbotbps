<?php

namespace App\Services;

use App\Models\Publication;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BpsPublicationService
{
    public const BPS_DOMAIN = '3313'; // Kabupaten Karanganyar
    public const ACTION_ID = '60624e3a7c825a0b98392f2120a4870bd26b8f90f2';
    public const BASE_URL = 'https://karanganyarkab.bps.go.id/id/publication';

    /**
     * Sinkronisasi seluruh publikasi BPS Kabupaten Karanganyar dari portal resmi.
     */
    public function syncAll(?callable $onProgress = null): array
    {
        @ini_set('max_execution_time', '300');
        @set_time_limit(300);

        // 1. Ambil halaman pertama untuk mengetahui total halaman
        $firstPageResponse = $this->fetchRawPage(1);
        if (!$firstPageResponse || !isset($firstPageResponse['data'])) {
            return ['status' => false, 'message' => 'Gagal mengambil metadata halaman 1', 'total' => 0];
        }

        $meta = $firstPageResponse['data'][0] ?? [];
        $totalPages = (int) ($meta['pages'] ?? 1);
        $totalItemsExpected = (int) ($meta['total'] ?? 0);

        $savedCount = 0;
        $allFetchedItems = [];

        // Simpan item dari halaman 1
        $page1Items = $firstPageResponse['data'][1] ?? [];
        foreach ($page1Items as $item) {
            $this->storeOrUpdatePublication($item);
            $savedCount++;
        }

        if ($onProgress) {
            $onProgress(1, $totalPages, $savedCount);
        }

        // 2. Ambil sisa halaman secara concurrent (batch 6 halaman per batch)
        $remainingPages = range(2, $totalPages);
        $chunks = array_chunk($remainingPages, 6);

        foreach ($chunks as $chunk) {
            $responses = Http::pool(function (Pool $pool) use ($chunk) {
                foreach ($chunk as $p) {
                    $pool->as("page_{$p}")
                        ->withoutVerifying()
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                            'Next-Action' => self::ACTION_ID,
                            'Content-Type' => 'text/plain;charset=UTF-8',
                            'Accept' => 'text/x-component',
                        ])
                        ->withBody(json_encode(['id', ['page' => $p]]), 'text/plain')
                        ->post(self::BASE_URL);
                }
            });

            foreach ($responses as $key => $res) {
                if ($res instanceof \Illuminate\Http\Client\Response && $res->successful()) {
                    $parsed = $this->parseFlightResponse($res->body());
                    if ($parsed && isset($parsed['data'][1]) && is_array($parsed['data'][1])) {
                        foreach ($parsed['data'][1] as $item) {
                            $this->storeOrUpdatePublication($item);
                            $savedCount++;
                        }
                    }
                }
            }

            if ($onProgress) {
                $lastP = end($chunk);
                $onProgress($lastP, $totalPages, $savedCount);
            }
        }

        return [
            'status' => true,
            'message' => "Berhasil menyinkronkan {$savedCount} publikasi BPS Kabupaten Karanganyar.",
            'total' => $savedCount,
            'total_expected' => $totalItemsExpected,
        ];
    }

    /**
     * Ambil 1 halaman data mentah.
     */
    protected function fetchRawPage(int $page = 1): ?array
    {
        try {
            $res = Http::withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Next-Action' => self::ACTION_ID,
                    'Content-Type' => 'text/plain;charset=UTF-8',
                    'Accept' => 'text/x-component',
                ])
                ->withBody(json_encode(['id', ['page' => $page]]), 'text/plain')
                ->post(self::BASE_URL);

            if ($res->successful()) {
                return $this->parseFlightResponse($res->body());
            }
        } catch (\Throwable $e) {
            Log::error("BPS fetch raw page error: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Parsing payload RSC/Flight response.
     */
    protected function parseFlightResponse(string $body): ?array
    {
        foreach (explode("\n", $body) as $line) {
            if (str_starts_with($line, '1:')) {
                $json = json_decode(substr($line, 2), true);
                if (isset($json['response'])) {
                    return $json['response'];
                }
            }
        }
        return null;
    }

    /**
     * Simpan atau perbarui model Publication.
     */
    public function storeOrUpdatePublication(array $item): Publication
    {
        $pubId = trim($item['pub_id'] ?? '');
        $title = trim($item['title'] ?? '');
        
        // Bersihkan karakter encoding rusak dari abstrak (seperti )
        $rawAbstract = $item['abstract'] ?? '';
        $cleanAbstract = str_replace(['', 'â€œ', 'â€'], '"', $rawAbstract);
        $cleanAbstract = preg_replace('/\s+/', ' ', trim($cleanAbstract));

        $isFeatured = Str::contains($title, [
            'Kabupaten Karanganyar Dalam Angka',
            'Statistik Daerah Kabupaten Karanganyar',
            'Indikator Kesejahteraan Rakyat',
        ]);

        $idSubjectCsa = $item['id_subject_csa'] ?? null;
        if (is_array($idSubjectCsa)) {
            $idSubjectCsa = json_encode($idSubjectCsa);
        }

        $subjectCsa = $item['subject_csa'] ?? null;
        if (is_array($subjectCsa)) {
            $subjectCsa = implode(', ', array_filter($subjectCsa, 'is_string'));
        }

        return Publication::updateOrCreate(
            ['pub_id' => $pubId],
            [
                'title' => $title,
                'slug' => Str::slug($title . '-' . $pubId),
                'issn' => !empty($item['issn']) && $item['issn'] !== '-' ? (string) $item['issn'] : null,
                'id_subject_csa' => $idSubjectCsa ? (string) $idSubjectCsa : null,
                'subject_csa' => $subjectCsa ? (string) $subjectCsa : null,
                'sch_date' => !empty($item['sch_date']) ? Carbon::parse($item['sch_date']) : null,
                'rl_date' => !empty($item['rl_date']) ? Carbon::parse($item['rl_date']) : null,
                'updt_date' => !empty($item['updt_date']) ? Carbon::parse($item['updt_date']) : null,
                'cover_url' => is_string($item['cover'] ?? null) ? $item['cover'] : null,
                'pdf_url' => is_string($item['pdf'] ?? null) ? $item['pdf'] : null,
                'size' => is_string($item['size'] ?? null) ? $item['size'] : null,
                'abstract' => $cleanAbstract ?: null,
                'is_featured' => $isFeatured,
            ]
        );
    }

    /**
     * Deep Research: Cari publikasi relevan untuk grounding LLM / Chatbot.
     */
    public function searchForDeepResearch(string $query, int $limit = 6): Collection
    {
        $terms = preg_split('/[\s,\.\?\!\-\_]+/', Str::lower($query));
        $terms = array_filter($terms, fn($t) => strlen($t) > 2 && !in_array($t, ['dan', 'yang', 'dari', 'pada', 'untuk', 'dengan', 'dalam', 'angka', 'data', 'bps', 'kabupaten', 'karanganyar']));

        $dbQuery = Publication::query();

        // 1. Ekstrak tahun jika disebutkan
        if (preg_match('/\b(20[12]\d)\b/', $query, $matches)) {
            $year = $matches[1];
            $dbQuery->where(function ($q) use ($year) {
                $q->whereYear('rl_date', $year)
                  ->orWhere('title', 'like', "%{$year}%");
            });
        }

        // 2. Ekstrak nama kecamatan jika ada
        $districts = [
            'jatipuro', 'jatiyoso', 'jumapolo', 'jumantono', 'matesih',
            'tawangmangu', 'ngargoyoso', 'karangpandan', 'karanganyar',
            'tasikmadu', 'jaten', 'colomadu', 'gondangrejo', 'kebakkramat',
            'mojogedang', 'kerjo', 'jenawi'
        ];

        $matchedDistrict = null;
        foreach ($districts as $d) {
            if (Str::contains(Str::lower($query), $d)) {
                $matchedDistrict = $d;
                break;
            }
        }

        if ($matchedDistrict) {
            $dbQuery->where('title', 'like', "%{$matchedDistrict}%");
        }

        // 3. Pencarian kata kunci pada judul & abstrak
        if (!empty($terms)) {
            $dbQuery->where(function ($sub) use ($terms) {
                foreach (array_slice($terms, 0, 4) as $term) {
                    $sub->orWhere('title', 'like', "%{$term}%")
                        ->orWhere('abstract', 'like', "%{$term}%")
                        ->orWhere('subject_csa', 'like', "%{$term}%");
                }
            });
        }

        $results = $dbQuery->orderBy('rl_date', 'desc')->take($limit)->get();

        // Fallback jika tidak ada hasil spesifik: berikan publikasi kompendium terbaru (KDA terbaru)
        if ($results->isEmpty()) {
            $results = Publication::where('is_featured', true)
                ->orderBy('rl_date', 'desc')
                ->take(3)
                ->get();
        }

        return $results;
    }

    /**
     * Dapatkan publikasi unggulan untuk Beranda.
     */
    public function getFeaturedPublications(int $limit = 6): Collection
    {
        return Publication::where('is_featured', true)
            ->orderBy('rl_date', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Dapatkan publikasi terbaru.
     */
    public function getLatestPublications(int $limit = 12): Collection
    {
        return Publication::orderBy('rl_date', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Dapatkan statistik publikasi BPS Karanganyar.
     */
    public function getStatistics(): array
    {
        return [
            'total' => Publication::count(),
            'latest_year' => Publication::max(\Illuminate\Support\Facades\DB::raw('YEAR(rl_date)')) ?? date('Y'),
            'total_kecamatan' => Publication::where('title', 'like', '%Kecamatan%Dalam Angka%')->count(),
            'total_kda' => Publication::where('title', 'like', '%Kabupaten Karanganyar Dalam Angka%')->count(),
        ];
    }
}
