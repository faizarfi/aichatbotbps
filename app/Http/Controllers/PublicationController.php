<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Services\BpsPublicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicationController extends Controller
{
    public function __construct(
        protected BpsPublicationService $publicationService
    ) {}

    /**
     * Halaman Katalog Seluruh Publikasi Resmi BPS Kabupaten Karanganyar.
     */
    public function index(Request $request)
    {
        $query = Publication::query();

        // Filter kata kunci
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('abstract', 'like', "%{$keyword}%")
                  ->orWhere('issn', 'like', "%{$keyword}%");
            });
        }

        // Filter tahun
        if ($request->filled('year')) {
            $year = (int) $request->input('year');
            $query->whereYear('rl_date', $year);
        }

        // Filter kategori tematik
        $activeCategory = $request->input('category');
        if ($activeCategory) {
            if ($activeCategory === 'kda') {
                $query->where('title', 'like', '%Kabupaten Karanganyar Dalam Angka%');
            } elseif ($activeCategory === 'kecamatan') {
                $query->where('title', 'like', '%Kecamatan%Dalam Angka%');
            } elseif ($activeCategory === 'sosial') {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%Sosial%')
                      ->orWhere('title', 'like', '%Kependudukan%')
                      ->orWhere('title', 'like', '%Kemiskinan%')
                      ->orWhere('title', 'like', '%Pembangunan Manusia%')
                      ->orWhere('title', 'like', '%Tenaga Kerja%')
                      ->orWhere('title', 'like', '%Pendidikan%')
                      ->orWhere('title', 'like', '%Kesehatan%')
                      ->orWhere('title', 'like', '%Gender%');
                });
            } elseif ($activeCategory === 'ekonomi') {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%Produk Domestik Regional Bruto%')
                      ->orWhere('title', 'like', '%PDRB%')
                      ->orWhere('title', 'like', '%Pengeluaran Konsumsi%')
                      ->orWhere('title', 'like', '%Ekonomi%')
                      ->orWhere('title', 'like', '%Inflasi%')
                      ->orWhere('title', 'like', '%Perdagangan%')
                      ->orWhere('title', 'like', '%Harga%');
                });
            } elseif ($activeCategory === 'pertanian') {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%Pertanian%')
                      ->orWhere('title', 'like', '%Padi%')
                      ->orWhere('title', 'like', '%Tanaman Pangan%')
                      ->orWhere('title', 'like', '%Hortikultura%')
                      ->orWhere('title', 'like', '%Peternakan%')
                      ->orWhere('title', 'like', '%Perikanan%')
                      ->orWhere('title', 'like', '%Perkebunan%');
                });
            } elseif ($activeCategory === 'industri') {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%Industri%')
                      ->orWhere('title', 'like', '%Konstruksi%')
                      ->orWhere('title', 'like', '%Transportasi%')
                      ->orWhere('title', 'like', '%Pariwisata%')
                      ->orWhere('title', 'like', '%Hotel%')
                      ->orWhere('title', 'like', '%Energi%');
                });
            } elseif ($activeCategory === 'statistik_daerah') {
                $query->where('title', 'like', '%Statistik Daerah%');
            }
        }

        // Pengurutan (Sorting)
        $activeSort = $request->input('sort', 'latest');
        if ($activeSort === 'oldest') {
            $query->orderBy('rl_date', 'asc');
        } elseif ($activeSort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($activeSort === 'title_desc') {
            $query->orderBy('title', 'desc');
        } else {
            $query->orderBy('rl_date', 'desc');
        }

        $publications = $query->paginate(12)->withQueryString();

        $stats = $this->publicationService->getStatistics();
        $availableYears = Publication::selectRaw('YEAR(rl_date) as year')
            ->whereNotNull('rl_date')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Hitungan per kategori untuk badge pill
        $categoryCounts = [
            'all' => Publication::count(),
            'kda' => Publication::where('title', 'like', '%Kabupaten Karanganyar Dalam Angka%')->count(),
            'kecamatan' => Publication::where('title', 'like', '%Kecamatan%Dalam Angka%')->count(),
            'sosial' => Publication::where(function ($q) {
                $q->where('title', 'like', '%Sosial%')
                  ->orWhere('title', 'like', '%Kependudukan%')
                  ->orWhere('title', 'like', '%Kemiskinan%')
                  ->orWhere('title', 'like', '%Pembangunan Manusia%')
                  ->orWhere('title', 'like', '%Tenaga Kerja%')
                  ->orWhere('title', 'like', '%Pendidikan%')
                  ->orWhere('title', 'like', '%Kesehatan%')
                  ->orWhere('title', 'like', '%Gender%');
            })->count(),
            'ekonomi' => Publication::where(function ($q) {
                $q->where('title', 'like', '%Produk Domestik Regional Bruto%')
                  ->orWhere('title', 'like', '%PDRB%')
                  ->orWhere('title', 'like', '%Pengeluaran Konsumsi%')
                  ->orWhere('title', 'like', '%Ekonomi%')
                  ->orWhere('title', 'like', '%Inflasi%')
                  ->orWhere('title', 'like', '%Perdagangan%')
                  ->orWhere('title', 'like', '%Harga%');
            })->count(),
            'pertanian' => Publication::where(function ($q) {
                $q->where('title', 'like', '%Pertanian%')
                  ->orWhere('title', 'like', '%Padi%')
                  ->orWhere('title', 'like', '%Tanaman Pangan%')
                  ->orWhere('title', 'like', '%Hortikultura%')
                  ->orWhere('title', 'like', '%Peternakan%')
                  ->orWhere('title', 'like', '%Perikanan%')
                  ->orWhere('title', 'like', '%Perkebunan%');
            })->count(),
            'industri' => Publication::where(function ($q) {
                $q->where('title', 'like', '%Industri%')
                  ->orWhere('title', 'like', '%Konstruksi%')
                  ->orWhere('title', 'like', '%Transportasi%')
                  ->orWhere('title', 'like', '%Pariwisata%')
                  ->orWhere('title', 'like', '%Hotel%')
                  ->orWhere('title', 'like', '%Energi%');
            })->count(),
            'statistik_daerah' => Publication::where('title', 'like', '%Statistik Daerah%')->count(),
        ];

        return view('publications.index', compact(
            'publications',
            'stats',
            'availableYears',
            'categoryCounts',
            'activeCategory',
            'activeSort'
        ));
    }

    /**
     * Detail satu publikasi resmi BPS Karanganyar.
     */
    public function show(string $slugOrId)
    {
        $publication = Publication::where('slug', $slugOrId)
            ->orWhere('pub_id', $slugOrId)
            ->firstOrFail();

        // Cari publikasi terkait yang benar-benar relevan
        $titleClean = trim(preg_replace('/\b(20\d{2}(-\d{4})?)\b/', '', $publication->title));
        $titleLower = Str::lower($titleClean);

        $relatedQuery = Publication::where('id', '!=', $publication->id);

        if (preg_match('/kecamatan\s+([a-z]+)/i', $publication->title, $matches)) {
            // Jika publikasi kecamatan, utamakan edisi tahun lain dari kecamatan yang sama
            $kecName = $matches[1];
            $relatedQuery->where('title', 'like', "%{$kecName}%");
        } elseif (Str::contains($titleLower, ['pdrb', 'produk domestik'])) {
            $relatedQuery->where(function ($q) {
                $q->where('title', 'like', '%PDRB%')->orWhere('title', 'like', '%Produk Domestik%');
            });
        } elseif (Str::contains($titleLower, 'kesejahteraan')) {
            $relatedQuery->where('title', 'like', '%Kesejahteraan%');
        } elseif (Str::contains($titleLower, 'statistik daerah')) {
            $relatedQuery->where('title', 'like', '%Statistik Daerah%');
        } elseif (Str::contains($titleLower, 'kabupaten karanganyar dalam angka')) {
            $relatedQuery->where('title', 'like', '%Kabupaten Karanganyar Dalam Angka%');
        } else {
            // Ambil kata kunci signifikan (minimal 4 huruf, bukan kata umum)
            $words = collect(explode(' ', $titleClean))
                ->map(fn($w) => trim(preg_replace('/[^a-zA-Z]/', '', $w)))
                ->filter(fn($w) => strlen($w) >= 4 && !in_array(strtolower($w), ['kabupaten', 'karanganyar', 'dalam', 'angka', 'tahun', 'edisi', 'statistik', 'badan', 'pusat'], true));

            $firstWord = $words->first();
            if ($firstWord) {
                $relatedQuery->where('title', 'like', "%{$firstWord}%");
            }
        }

        $related = $relatedQuery->latest('rl_date')->take(4)->get();

        // Fallback jika belum mencapai 4 publikasi: lengkapi dengan publikasi resmi terbaru lainnya
        if ($related->count() < 4) {
            $existingIds = $related->pluck('id')->push($publication->id)->all();
            $fallbacks = Publication::whereNotIn('id', $existingIds)
                ->latest('rl_date')
                ->take(4 - $related->count())
                ->get();
            $related = $related->concat($fallbacks);
        }

        // Tentukan kategori topik publikasi
        $titleLower = Str::lower($publication->title);
        $topicTag = 'Publikasi Resmi BPS';
        if (Str::contains($titleLower, 'kabupaten karanganyar dalam angka')) {
            $topicTag = 'Kompendium KDA';
        } elseif (Str::contains($titleLower, 'dalam angka') && Str::contains($titleLower, 'kecamatan')) {
            $topicTag = 'Kecamatan Dalam Angka';
        } elseif (Str::contains($titleLower, ['pdrb', 'produk domestik', 'ekonomi', 'konsumsi'])) {
            $topicTag = 'Ekonomi & PDRB';
        } elseif (Str::contains($titleLower, ['kemiskinan', 'sosial', 'kependudukan', 'pembangunan manusia', 'tenaga kerja'])) {
            $topicTag = 'Sosial & Kependudukan';
        } elseif (Str::contains($titleLower, ['pertanian', 'padi', 'tanaman', 'hortikultura'])) {
            $topicTag = 'Pertanian & Pangan';
        } elseif (Str::contains($titleLower, 'statistik daerah')) {
            $topicTag = 'Statistik Daerah';
        }

        // Siapkan prompt rekomendasi untuk KARTA AI
        $cleanTitle = trim($publication->title);
        $aiPrompts = [
            "Jelaskan ringkasan eksekutif dan poin data paling penting dari dokumen {$cleanTitle}!",
            "Apa saja indikator dan angka statistik kunci yang tercatat dalam {$cleanTitle}?",
            "Bagaimana tren perkembangan data dalam publikasi {$cleanTitle} jika dibandingkan dengan periode sebelumnya?",
        ];

        return view('publications.show', compact('publication', 'related', 'topicTag', 'aiPrompts'));
    }
}
