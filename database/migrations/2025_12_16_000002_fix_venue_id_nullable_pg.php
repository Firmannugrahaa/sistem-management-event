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
        // PostgreSQL specific syntax to drop NOT NULL constraint
        // Check driver to be safe, though user error confirms pgsql
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE events ALTER COLUMN venue_id DROP NOT NULL');
        } else {
             // Fallback for MySQL in case dev environment differs
             DB::statement('ALTER TABLE events MODIFY venue_id BIGINT UNSIGNED NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::statement('ALTER TABLE events ALTER COLUMN venue_id SET NOT NULL');
    }
};
