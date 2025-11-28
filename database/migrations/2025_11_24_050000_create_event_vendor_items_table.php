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
        Schema::create('event_vendor_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            // Polymorphic relation to handle both VendorProduct (Services) and VendorCatalogItem (Catalog)
            $table->string('itemable_type');
            $table->unsignedBigInteger('itemable_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2)->default(0); // Price at the time of adding
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['itemable_type', 'itemable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_vendor_items');
    }
};
