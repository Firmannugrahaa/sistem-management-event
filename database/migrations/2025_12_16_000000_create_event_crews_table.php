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
        Schema::create('event_crews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('Crew');
            $table->json('permissions')->nullable();
            $table->timestamps();
            
            // Prevent duplicate assignment of same user to same event?
            // Optional, but good practice. A user can have multiple roles? If so, no unique constraint.
            // Assuming 1 user = 1 role per event for now? Or allow multiple?
            // Standard approach: 1 entry per user per event. If they have multiple roles, list them in 'role' string or separate entries?
            // I'll leave it without unique constraint for flexibility, or maybe add it later.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_crews');
    }
};
