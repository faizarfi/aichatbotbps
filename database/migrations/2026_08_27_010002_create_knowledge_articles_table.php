<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('knowledge_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_category_id')->constrained('knowledge_categories')->cascadeOnDelete();
            $table->string('title');
            $table->text('question');
            $table->longText('answer');
            $table->json('keywords')->nullable();
            $table->string('source_title')->nullable();
            $table->string('source_url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // FULLTEXT index untuk pencarian FAQ
        DB::statement('ALTER TABLE knowledge_articles ADD FULLTEXT fulltext_search (title, question, answer)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_articles');
    }
};
