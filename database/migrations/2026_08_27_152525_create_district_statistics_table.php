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
        Schema::create('district_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('capital_name');
            $table->decimal('area_sqkm', 8, 2);
            $table->unsignedInteger('population');
            $table->unsignedInteger('density');
            $table->unsignedSmallInteger('villages_count');
            $table->string('featured_sector');
            $table->text('description')->nullable();
            $table->string('color_code')->default('#3b82f6');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district_statistics');
    }
};
