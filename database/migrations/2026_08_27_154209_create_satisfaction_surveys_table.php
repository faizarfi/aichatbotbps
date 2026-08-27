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
        Schema::create('satisfaction_surveys', function (Blueprint $table) {
            $table->id();
            $table->string('respondent_name')->nullable();
            $table->string('respondent_email')->nullable();
            $table->string('respondent_phone')->nullable();
            $table->string('respondent_type')->default('umum');
            $table->string('service_used')->default('chatbot');
            $table->unsignedTinyInteger('quality_score')->default(5);
            $table->unsignedTinyInteger('speed_score')->default(5);
            $table->unsignedTinyInteger('friendliness_score')->default(5);
            $table->unsignedTinyInteger('facility_score')->default(5);
            $table->decimal('overall_score', 4, 2)->default(5.00);
            $table->text('feedback_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satisfaction_surveys');
    }
};
