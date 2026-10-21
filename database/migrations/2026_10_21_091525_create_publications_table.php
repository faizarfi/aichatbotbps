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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('pub_id')->unique()->index();
            $table->string('title');
            $table->string('slug')->nullable()->index();
            $table->string('issn')->nullable();
            $table->string('kat_no')->nullable();
            $table->string('pub_no')->nullable();
            $table->string('id_subject_csa')->nullable();
            $table->string('subject_csa')->nullable();
            $table->date('sch_date')->nullable();
            $table->date('rl_date')->nullable()->index();
            $table->date('updt_date')->nullable();
            $table->text('cover_url')->nullable();
            $table->text('pdf_url')->nullable();
            $table->string('size')->nullable();
            $table->longText('abstract')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
