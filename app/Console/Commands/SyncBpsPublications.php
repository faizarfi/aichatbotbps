<?php

namespace App\Console\Commands;

use App\Services\BpsPublicationService;
use Illuminate\Console\Command;

class SyncBpsPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bps:sync-publications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi seluruh publikasi resmi BPS Kabupaten Karanganyar (Domain 3313)';

    /**
     * Execute the console command.
     */
    public function handle(BpsPublicationService $service): int
    {
        $this->info("=== Memulai Sinkronisasi Publikasi BPS Kabupaten Karanganyar (3313) ===");

        $res = $service->syncAll(function ($currentPage, $totalPages, $savedCount) {
            $this->output->write("\rHalaman {$currentPage}/{$totalPages} diproses... ({$savedCount} publikasi tersimpan)");
        });

        $this->newLine();

        if ($res['status']) {
            $this->info("✓ " . $res['message']);
            return Command::SUCCESS;
        }

        $this->error("Gagal sinkronisasi: " . $res['message']);
        return Command::FAILURE;
    }
}
