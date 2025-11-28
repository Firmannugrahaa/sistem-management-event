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
        Schema::create('vendor_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // Album/Kategori
            $table->string('project_date')->nullable(); // Tanggal project
            $table->string('client_name')->nullable(); // Nama klien (opsional)
            $table->string('location')->nullable(); // Lokasi project
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('order')->default(0); // Untuk sorting
            $table->timestamps();
        });

        Schema::create('vendor_portfolio_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained('vendor_portfolios')->onDelete('cascade');
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->integer('order')->default(0); // Untuk sorting gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_portfolio_images');
        Schema::dropIfExists('vendor_portfolios');
    }
};
