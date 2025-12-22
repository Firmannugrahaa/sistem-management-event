<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check driver to support both MySQL and PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE events ALTER COLUMN venue_id DROP NOT NULL');
        } else {
            DB::statement('ALTER TABLE events MODIFY venue_id BIGINT UNSIGNED NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL (careful if nulls exist)
        // DB::statement('ALTER TABLE events MODIFY venue_id BIGINT UNSIGNED NOT NULL');
    }
};
