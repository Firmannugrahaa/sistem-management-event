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
        // Categories for the catalog
        Schema::create('vendor_catalog_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        // The actual catalog items (products/inventory)
        Schema::create('vendor_catalog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('vendor_catalog_categories')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->enum('status', ['available', 'booked', 'not_available'])->default('available');
            $table->json('attributes')->nullable(); // Dynamic attributes: {"Color": "Red", "Size": "M"}
            $table->timestamps();
        });

        // Multiple images for each item
        Schema::create('vendor_catalog_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_item_id')->constrained('vendor_catalog_items')->onDelete('cascade');
            $table->string('image_path');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_catalog_images');
        Schema::dropIfExists('vendor_catalog_items');
        Schema::dropIfExists('vendor_catalog_categories');
    }
};
