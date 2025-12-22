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
            if (!Schema::hasColumn('client_requests', 'cpp_name')) {
                $table->string('cpp_name')->nullable()->after('message');
            }
            if (!Schema::hasColumn('client_requests', 'cpw_name')) {
                $table->string('cpw_name')->nullable()->after('cpp_name');
            }
            if (!Schema::hasColumn('client_requests', 'fill_couple_later')) {
                $table->boolean('fill_couple_later')->default(false)->after('cpw_name');
            }
            if (!Schema::hasColumn('client_requests', 'booking_number')) {
                $table->string('booking_number')->nullable()->unique()->after('fill_couple_later');
            }
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
