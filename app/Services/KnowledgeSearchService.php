<?php

namespace App\Services;

use App\Models\KnowledgeArticle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class KnowledgeSearchService
{
    /**
     * Cari artikel paling relevan berdasarkan teks pertanyaan.
     *
     * @param string $query
     * @param int $limit
     * @return array{bestMatch: ?KnowledgeArticle, candidates: Collection, confidence: float}
     */
    public function search(string $query, int $limit = 3): array
    {
        $cleanQuery = trim($query);
        if (empty($cleanQuery)) {
            return [
                'bestMatch' => null,
                'candidates' => new Collection(),
                'confidence' => 0.0,
            ];
        }

        // Ambil semua artikel aktif dengan kategori aktif
        $articles = KnowledgeArticle::with('category')
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        if ($articles->isEmpty()) {
            return [
                'bestMatch' => null,
                'candidates' => new Collection(),
                'confidence' => 0.0,
            ];
        }

        $queryWords = $this->tokenize($cleanQuery);
        $scored = [];

        foreach ($articles as $article) {
            $score = $this->calculateScore($article, $cleanQuery, $queryWords);
            if ($score > 0) {
                $scored[] = [
                    'article' => $article,
                    'score' => $score,
                ];
            }
        }

        // Urutkan berdasarkan skor tertinggi
        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        if (empty($scored)) {
            return [
                'bestMatch' => null,
                'candidates' => new Collection(),
                'confidence' => 0.0,
            ];
        }

        $topCandidates = collect(array_slice($scored, 0, $limit))->pluck('article');
        $topScore = $scored[0]['score'];
        $bestMatch = $topScore >= 20 ? $scored[0]['article'] : null;
        $confidence = min(1.0, round($topScore / 100, 4));

        return [
            'bestMatch' => $bestMatch,
            'candidates' => $topCandidates,
            'confidence' => $confidence,
        ];
    }

    /**
     * Hitung skor relevansi artikel terhadap query.
     */
    private function calculateScore(KnowledgeArticle $article, string $fullQuery, array $queryWords): float
    {
        $score = 0.0;
        $lowerQuery = Str::lower($fullQuery);
        $lowerTitle = Str::lower($article->title);
        $lowerQuestion = Str::lower($article->question);
        $lowerAnswer = Str::lower($article->answer);
        $keywords = array_map(fn($k) => Str::lower($k), (array) ($article->keywords ?? []));

        // 1. Exact match di title atau question
        if (str_contains($lowerTitle, $lowerQuery)) {
            $score += 60;
        }
        if (str_contains($lowerQuestion, $lowerQuery)) {
            $score += 50;
        }

        // 2. Keyword match
        foreach ($keywords as $kw) {
            if (!empty($kw)) {
                if ($kw === $lowerQuery) {
                    $score += 70;
                } elseif (str_contains($lowerQuery, $kw) || str_contains($kw, $lowerQuery)) {
                    $score += 35;
                }
            }
        }

        // 3. Kata per kata matching
        foreach ($queryWords as $word) {
            if (strlen($word) < 3) continue;

            if (str_contains($lowerTitle, $word)) {
                $score += 15;
            }
            if (str_contains($lowerQuestion, $word)) {
                $score += 12;
            }
            if (str_contains($lowerAnswer, $word)) {
                $score += 5;
            }

            foreach ($keywords as $kw) {
                if (str_contains($kw, $word)) {
                    $score += 10;
                }
            }
        }

        return $score;
    }

    /**
     * Tokenisasi query menjadi array kata-kata bersih.
     */
    private function tokenize(string $text): array
    {
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', Str::lower($text));
        $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);

        // Abaikan stopwords umum bahasa Indonesia
        $stopwords = ['yang', 'untuk', 'pada', 'ke', 'dari', 'di', 'dan', 'ini', 'itu', 'adalah', 'apakah', 'bagaimana', 'bisa', 'tolong', 'mohon', 'ada', 'apa', 'saya', 'kami', 'anda', 'dengan', 'atau'];
        return array_values(array_diff($words, $stopwords));
    }
}
