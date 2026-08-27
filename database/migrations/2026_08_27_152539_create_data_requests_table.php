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
        Schema::create('data_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique()->index();
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone');
            $table->string('applicant_type')->default('umum');
            $table->string('institution_name');
            $table->string('research_title');
            $table->text('data_description');
            $table->text('purpose');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_filename')->nullable();
            $table->string('result_file_path')->nullable();
            $table->string('result_filename')->nullable();
            $table->enum('status', ['submitted', 'reviewing', 'approved', 'ready', 'rejected'])->default('submitted')->index();
            $table->text('officer_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_requests');
    }
};
