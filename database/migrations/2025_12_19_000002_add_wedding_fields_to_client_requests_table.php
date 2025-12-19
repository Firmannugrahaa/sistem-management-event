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
        Schema::table('client_requests', function (Blueprint $table) {
            // Wedding couple names (optional, can be filled later from dashboard)
            $table->string('cpp_name')->nullable()->after('message'); // Calon Pengantin Pria
            $table->string('cpw_name')->nullable()->after('cpp_name'); // Calon Pengantin Wanita
            $table->boolean('fill_couple_later')->default(false)->after('cpw_name'); // Flag: fill later from dashboard
            
            // Store booking confirmation number for tracking
            $table->string('booking_number')->nullable()->unique()->after('fill_couple_later');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_requests', function (Blueprint $table) {
            $table->dropColumn(['cpp_name', 'cpw_name', 'fill_couple_later', 'booking_number']);
        });
    }
};
