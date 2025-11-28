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
        // Header Recommendation
        Schema::create('lead_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('title'); // e.g., "Rekomendasi Venue & Catering - Opsi A"
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'revision_requested'])->default('draft');
            $table->decimal('total_estimated_budget', 15, 2)->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('client_feedback')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Recommendation Items (Vendors/Venues included)
        Schema::create('recommendation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_recommendation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete(); // Internal vendor
            // For external vendors (manual input)
            $table->string('external_vendor_name')->nullable();
            $table->string('category'); // venue, catering, decoration, etc.
            $table->decimal('estimated_price', 15, 2)->nullable();
            $table->text('notes')->nullable(); // Admin's notes about this vendor
            $table->integer('order')->default(0); // For sorting display
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_items');
        Schema::dropIfExists('lead_recommendations');
    }
};
