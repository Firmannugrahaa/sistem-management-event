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
        Schema::table('recommendation_items', function (Blueprint $table) {
            if (!Schema::hasColumn('recommendation_items', 'client_response')) {
                $table->enum('client_response', ['pending', 'approved', 'rejected'])->default('pending')->after('notes');
            }
            if (!Schema::hasColumn('recommendation_items', 'client_feedback')) {
                $table->text('client_feedback')->nullable()->after('client_response');
            }
            if (!Schema::hasColumn('recommendation_items', 'responded_at')) {
                $table->timestamp('responded_at')->nullable()->after('client_feedback');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recommendation_items', function (Blueprint $table) {
            $table->dropColumn(['client_response', 'client_feedback', 'responded_at']);
        });
    }
};
