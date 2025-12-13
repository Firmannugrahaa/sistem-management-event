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
        // Add status and order to portfolios if they don't exist
        if (Schema::hasTable('portfolios')) {
            Schema::table('portfolios', function (Blueprint $table) {
                if (!Schema::hasColumn('portfolios', 'status')) {
                    $table->enum('status', ['draft', 'published'])->default('draft')->after('location');
                }
                if (!Schema::hasColumn('portfolios', 'order')) {
                    $table->integer('order')->default(0)->after('status');
                }
                // Check if 'image' exists, if not add it (it should exist from previous migration)
            });
        }

        // Create portfolio_images table
        Schema::create('portfolio_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained('portfolios')->onDelete('cascade');
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_images');
        
        if (Schema::hasTable('portfolios')) {
            Schema::table('portfolios', function (Blueprint $table) {
                $table->dropColumn(['status', 'order']);
            });
        }
    }
};
