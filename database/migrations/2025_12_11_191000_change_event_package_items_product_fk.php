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
        Schema::table('event_package_items', function (Blueprint $table) {
            // Drop foreign key and column for vendor_products if exists
            // Since we don't know the exact constraint name generated, we try array syntax
            // Or usually it is event_package_items_vendor_product_id_foreign
            
            // Check if column exists first to be safe or just try?
            // Laravel migration 'table' closure doesn't easy check.
            // Assumption: vendor_product_id exists.
            
            // We need to drop constraint first.
            $table->dropForeign(['vendor_product_id']);
            $table->dropColumn('vendor_product_id');

            // Add new column
            $table->foreignId('vendor_catalog_item_id')->nullable()->after('event_package_id')->constrained('vendor_catalog_items')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_package_items', function (Blueprint $table) {
            $table->dropForeign(['vendor_catalog_item_id']);
            $table->dropColumn('vendor_catalog_item_id');
            
            $table->foreignId('vendor_product_id')->nullable()->constrained('vendor_products')->nullOnDelete();
        });
    }
};
