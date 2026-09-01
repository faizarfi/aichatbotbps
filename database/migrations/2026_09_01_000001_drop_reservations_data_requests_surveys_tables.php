<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Hapus tabel reservations, data_requests, dan satisfaction_surveys.
     */
    public function up(): void
    {
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function (Blueprint $table) {
                if (Schema::hasColumn('reservations', 'user_id')) {
                    $table->dropConstrainedForeignId('user_id');
                }
            });
            Schema::dropIfExists('reservations');
        }

        if (Schema::hasTable('data_requests')) {
            Schema::table('data_requests', function (Blueprint $table) {
                if (Schema::hasColumn('data_requests', 'user_id')) {
                    $table->dropConstrainedForeignId('user_id');
                }
            });
            Schema::dropIfExists('data_requests');
        }

        if (Schema::hasTable('satisfaction_surveys')) {
            Schema::table('satisfaction_surveys', function (Blueprint $table) {
                if (Schema::hasColumn('satisfaction_surveys', 'user_id')) {
                    $table->dropConstrainedForeignId('user_id');
                }
            });
            Schema::dropIfExists('satisfaction_surveys');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fitur telah dihapus secara permanen
    }
};
