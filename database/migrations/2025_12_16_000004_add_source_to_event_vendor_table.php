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
        Schema::table('event_vendor', function (Blueprint $table) {
            $table->string('source')->default('custom')->after('role'); // 'package', 'recommendation', 'custom'
            // MySQL-specific 'after' is ignored in PG but schema builder handles table alter fine.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_vendor', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
