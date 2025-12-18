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
        Schema::table('recommendation_items', function (Blueprint $table) {
            $table->string('service_name')->nullable()->after('category'); // e.g., "Wedding Package 300 pax"
            $table->string('recommendation_type')->default('primary')->after('service_name'); // primary, alternative, upgrade
            $table->string('status')->default('pending')->after('estimated_price'); // pending, accepted, rejected
            $table->text('rejection_reason')->nullable()->after('status');
        });

        // Add Check Constraints for PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE recommendation_items ADD CONSTRAINT recommendation_items_type_check CHECK (recommendation_type IN ('primary', 'alternative', 'upgrade'))");
            DB::statement("ALTER TABLE recommendation_items ADD CONSTRAINT recommendation_items_status_check CHECK (status IN ('pending', 'accepted', 'rejected'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recommendation_items', function (Blueprint $table) {
            $table->dropColumn(['service_name', 'recommendation_type', 'status', 'rejection_reason']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
             // Drop constraints if they exist (Postgres usually drops them with column drop, but manual drop is safer)
             // Actually dropping columns removes associated check constraints automatically in Postgres.
        }
    }
};
