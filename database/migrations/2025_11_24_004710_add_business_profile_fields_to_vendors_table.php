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
        Schema::table('vendors', function (Blueprint $table) {
            // Business Profile Fields
            $table->string('brand_name')->nullable()->after('user_id');
            $table->string('logo_path')->nullable()->after('brand_name');
            $table->text('description')->nullable()->after('logo_path');
            $table->string('email')->nullable()->after('phone_number');
            $table->string('whatsapp')->nullable()->after('email');
            $table->string('location')->nullable()->after('address');
            
            // Social Media
            $table->string('instagram')->nullable()->after('location');
            $table->string('tiktok')->nullable()->after('instagram');
            $table->string('facebook')->nullable()->after('tiktok');
            
            // Operating Hours
            $table->json('operating_hours')->nullable()->after('facebook');
            
            // Display on website
            $table->boolean('is_active')->default(true)->after('operating_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'brand_name',
                'logo_path',
                'description',
                'email',
                'whatsapp',
                'location',
                'instagram',
                'tiktok',
                'facebook',
                'operating_hours',
                'is_active',
            ]);
        });
    }
};
