<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->string('duration')->nullable(); // e.g., "6 Jam", "Full Day"
            $table->string('thumbnail_path')->nullable();
            $table->json('features')->nullable(); // List of items
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Pivot table for linking products/services (if we decide to link real items later)
        Schema::create('event_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_package_id')->constrained()->onDelete('cascade');
            // We can link to vendor_products if we want strict relationships
            $table->foreignId('vendor_product_id')->nullable()->constrained('vendor_products')->nullOnDelete();
            // Or just a custom name if it's a manual entry
            $table->string('custom_item_name')->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_package_items');
        Schema::dropIfExists('event_packages');
    }
};
