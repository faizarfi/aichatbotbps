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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->enum('channel', ['web', 'whatsapp'])->default('web');
            $table->string('visitor_session')->nullable()->index();
            $table->string('visitor_name')->nullable();
            $table->text('visitor_contact')->nullable(); // Akan dienkripsi di model
            $table->enum('status', ['bot', 'waiting', 'handled', 'closed'])->default('bot');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
