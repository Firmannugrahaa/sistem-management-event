<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates pivot table for vendor package items (catalog products)
     * This allows vendors to include their catalog items in packages with qty/unit
     */
    public function up(): void
    {
        Schema::create('vendor_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('vendor_packages')->onDelete('cascade');
            $table->foreignId('catalog_item_id')->constrained('vendor_catalog_items')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('unit')->nullable(); // pax, box, porsi, meja, orang, set, pcs, jam
            $table->text('notes')->nullable();
            $table->boolean('is_included')->default(true); // termasuk atau tidak dalam paket
            $table->timestamps();
            
            // Prevent duplicate items in same package
            $table->unique(['package_id', 'catalog_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_package_items');
    }
};
