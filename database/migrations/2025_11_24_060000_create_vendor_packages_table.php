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
        Schema::create('vendor_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->json('benefits')->nullable(); // Array of benefit strings
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('vendor_package_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('vendor_packages')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('vendor_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_package_services');
        Schema::dropIfExists('vendor_packages');
    }
};
