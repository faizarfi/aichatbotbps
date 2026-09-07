<?php

namespace App\Console\Commands;

use App\Models\KnowledgeArticle;
use App\Services\AiLlmService;
use Illuminate\Console\Command;

class GenerateKnowledgeEmbeddings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'knowledge:embed {--force : Generate ulang semua embedding meskipun sudah ada}';

    protected $description = 'Generate vektor embedding untuk artikel basis pengetahuan BPS menggunakan Google Gemini API';

    public function handle(AiLlmService $aiService): int
    {
        if (!$aiService->isConfigured()) {
            $this->error('AI Service belum terkonfigurasi. Pastikan AI_API_KEY terisi di .env.');
            return self::FAILURE;
        }

        $force = $this->option('force');
        $query = KnowledgeArticle::query();
        if (!$force) {
            $query->whereNull('embedding');
        }

        $articles = $query->get();
        if ($articles->isEmpty()) {
            $this->info('Semua artikel sudah memiliki vektor embedding. Gunakan --force untuk memperbarui ulang.');
            return self::SUCCESS;
        }

        $this->info("Memproses embedding untuk {$articles->count()} artikel basis pengetahuan...");
        $bar = $this->output->createProgressBar($articles->count());
        $bar->start();

        $successCount = 0;
        $failCount = 0;

        foreach ($articles as $article) {
            $textToEmbed = trim(
                $article->title . "\n" . 
                $article->question . "\n" . 
                implode(', ', (array) ($article->keywords ?? [])) . "\n" . 
                strip_tags(mb_substr($article->answer, 0, 800))
            );

            $embedding = $aiService->generateEmbedding($textToEmbed);

            if ($embedding && is_array($embedding)) {
                $article->update(['embedding' => $embedding]);
                $successCount++;
            } else {
                $failCount++;
            }

            $bar->advance();
            // Jeda kecil agar tidak melampaui rate limit Gemini
            usleep(250000); // 0.25 detik
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Selesai! Berhasil membuat embedding untuk {$successCount} artikel.");
        if ($failCount > 0) {
            $this->warn("Terdapat {$failCount} artikel yang gagal diproses (cek log / kuota API).");
        }

        return self::SUCCESS;
    }
}
