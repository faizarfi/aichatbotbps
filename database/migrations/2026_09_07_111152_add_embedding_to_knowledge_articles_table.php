<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('knowledge_articles', function (Blueprint $table) {
            if (!Schema::hasColumn('knowledge_articles', 'embedding')) {
                $table->longText('embedding')->nullable()->after('answer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge_articles', function (Blueprint $table) {
            if (Schema::hasColumn('knowledge_articles', 'embedding')) {
                $table->dropColumn('embedding');
            }
        });
    }
};
