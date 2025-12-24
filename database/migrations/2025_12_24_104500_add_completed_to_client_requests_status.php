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
        // For PostgreSQL, we need to drop and re-add the constraint
        DB::statement("ALTER TABLE client_requests DROP CONSTRAINT client_requests_detailed_status_check");

        // Fix any invalid statuses first
        DB::table('client_requests')
            ->whereNotIn('detailed_status', [
                'new', 'contacted', 'need_recommendation', 'recommendation_sent', 
                'waiting_client_response', 'revision_requested', 'quotation_sent', 
                'waiting_approval', 'approved', 'converted_to_event', 'rejected', 
                'cancelled', 'on_hold', 'lost', 'completed'
            ])
            ->update(['detailed_status' => 'completed']);
        
        DB::statement("ALTER TABLE client_requests ADD CONSTRAINT client_requests_detailed_status_check CHECK (detailed_status IN (
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
            'lost',
            'completed'
        ))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original constraint without 'completed'
        // WARNING: This will fail if there are rows with 'completed' status
        DB::statement("ALTER TABLE client_requests DROP CONSTRAINT client_requests_detailed_status_check");
            
        DB::statement("ALTER TABLE client_requests ADD CONSTRAINT client_requests_detailed_status_check CHECK (detailed_status IN (
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
        ))");
    }
};
