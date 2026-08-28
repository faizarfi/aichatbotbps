<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom user_id ke 4 tabel layanan publik
     * agar setiap submission tercatat siapa yang mengajukan.
     * Nullable agar data lama yang sudah ada tetap valid.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        Schema::table('data_requests', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        Schema::table('satisfaction_surveys', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('data_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('satisfaction_surveys', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
