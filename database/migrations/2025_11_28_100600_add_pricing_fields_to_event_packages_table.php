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
        Schema::table('event_packages', function (Blueprint $table) {
            // Rename existing columns for clarity
            $table->renameColumn('price', 'base_price');
            $table->renameColumn('discount_price', 'final_price');
            
            // Add new hybrid pricing columns
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('base_price')->comment('Discount in percentage (0-100)');
            $table->decimal('markup_percentage', 5, 2)->default(0)->after('discount_percentage')->comment('Markup in percentage (0-100)');
            $table->string('image_url')->nullable()->after('thumbnail_path')->comment('Package main image URL');
            $table->boolean('is_featured')->default(false)->after('is_active')->comment('Show as featured/best value');
            
            // Add calculation method
            $table->enum('pricing_method', ['manual', 'auto', 'hybrid'])->default('hybrid')->after('is_featured');
        });
        
        // Add price field to event_package_items for auto-calculation
        Schema::table('event_package_items', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 2)->nullable()->after('quantity')->comment('Price per unit at time of package creation');
            $table->decimal('total_price', 12, 2)->nullable()->after('unit_price')->comment('unit_price * quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_packages', function (Blueprint $table) {
            $table->renameColumn('base_price', 'price');
            $table->renameColumn('final_price', 'discount_price');
            $table->dropColumn(['discount_percentage', 'markup_percentage', 'image_url', 'is_featured', 'pricing_method']);
        });
        
        Schema::table('event_package_items', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'total_price']);
        });
    }
};
