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
            // Soft Delete
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->after('responded_at')
                ->constrained('users')->nullOnDelete();
            
            // Enhanced Status Fields
            $table->enum('detailed_status', [
                'new', 
                'contacted', 
                'need_recommendation', 
                'recommendation_sent', 
                'waiting_client_response',
                'revision_requested', 
                'quotation_sent', 
                'waiting_approval', 
                'approved', 
                'converted_to_event',
                'rejected', 
                'cancelled', 
                'on_hold', 
                'lost'
            ])->default('new')->after('status');
            
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])
                ->default('medium')->after('detailed_status');
            
            // Assignment & Tracking
            $table->timestamp('last_contacted_at')->nullable()->after('priority');
            $table->integer('follow_up_count')->default(0)->after('last_contacted_at');
            
            // Add index for better query performance
            $table->index(['detailed_status', 'priority']);
            $table->index('last_contacted_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_requests', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['detailed_status', 'priority']);
            $table->dropIndex(['last_contacted_at']);
            $table->dropIndex(['deleted_at']);
            
            // Drop foreign key
            $table->dropForeign(['deleted_by']);
            
            // Drop columns
            $table->dropColumn([
                'deleted_at',
                'deleted_by',
                'detailed_status',
                'priority',
                'last_contacted_at',
                'follow_up_count'
            ]);
        });
    }
};
