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
        // Add timeline fields to checklist_template_items
        Schema::table('checklist_template_items', function (Blueprint $table) {
            $table->integer('days_before_event')->nullable()->after('order')
                ->comment('Recommended days before event to complete this item');
            $table->enum('priority', ['CRITICAL', 'IMPORTANT', 'NICE_TO_HAVE'])
                ->default('IMPORTANT')->after('days_before_event')
                ->comment('Priority level for rush bookings');
            $table->boolean('is_flexible')->default(true)->after('priority')
                ->comment('Can be skipped for rush bookings');
        });

        // Add timeline fields to client_checklist_items
        Schema::table('client_checklist_items', function (Blueprint $table) {
            $table->integer('days_before_event')->nullable()->after('order')
                ->comment('Days before event (copied from template)');
            $table->date('suggested_date')->nullable()->after('days_before_event')
                ->comment('Auto-calculated suggested completion date');
            $table->date('custom_due_date')->nullable()->after('suggested_date')
                ->comment('User-defined custom due date (overrides suggested)');
            $table->enum('priority', ['CRITICAL', 'IMPORTANT', 'NICE_TO_HAVE'])
                ->default('IMPORTANT')->after('custom_due_date')
                ->comment('Priority level (copied from template)');
            $table->boolean('is_flexible')->default(true)->after('priority')
                ->comment('Can be skipped for rush bookings (copied from template)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklist_template_items', function (Blueprint $table) {
            $table->dropColumn(['days_before_event', 'priority', 'is_flexible']);
        });

        Schema::table('client_checklist_items', function (Blueprint $table) {
            $table->dropColumn(['days_before_event', 'suggested_date', 'custom_due_date', 'priority', 'is_flexible']);
        });
    }
};
