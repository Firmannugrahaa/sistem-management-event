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
        if (DB::connection()->getDriverName() === 'pgsql') {
            // Drop old constraint
            DB::statement("ALTER TABLE client_requests DROP CONSTRAINT IF EXISTS client_requests_detailed_status_check");
            
            // Add new constraint with updated values
            DB::statement("ALTER TABLE client_requests ADD CONSTRAINT client_requests_detailed_status_check CHECK (detailed_status::text = ANY (ARRAY[
                'new'::text, 
                'contacted'::text, 
                'on_process'::text,
                'need_recommendation'::text, 
                'recommendation_sent'::text, 
                'waiting_client_response'::text,
                'revision_requested'::text, 
                'quotation_sent'::text, 
                'waiting_approval'::text, 
                'ready_to_confirm'::text,
                'confirmed'::text,
                'approved'::text, 
                'converted_to_event'::text,
                'rejected'::text, 
                'cancelled'::text, 
                'on_hold'::text, 
                'lost'::text
            ]))");
        } else {
            // For MySQL
            DB::statement("ALTER TABLE client_requests MODIFY COLUMN detailed_status ENUM(
                'new', 
                'contacted', 
                'on_process',
                'need_recommendation', 
                'recommendation_sent', 
                'waiting_client_response',
                'revision_requested', 
                'quotation_sent', 
                'waiting_approval', 
                'ready_to_confirm',
                'confirmed',
                'approved', 
                'converted_to_event',
                'rejected', 
                'cancelled', 
                'on_hold', 
                'lost'
            ) DEFAULT 'new'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE client_requests DROP CONSTRAINT IF EXISTS client_requests_detailed_status_check");
            
            DB::statement("ALTER TABLE client_requests ADD CONSTRAINT client_requests_detailed_status_check CHECK (detailed_status::text = ANY (ARRAY[
                'new'::text, 
                'contacted'::text, 
                'need_recommendation'::text, 
                'recommendation_sent'::text, 
                'waiting_client_response'::text,
                'revision_requested'::text, 
                'quotation_sent'::text, 
                'waiting_approval'::text, 
                'approved'::text, 
                'converted_to_event'::text,
                'rejected'::text, 
                'cancelled'::text, 
                'on_hold'::text, 
                'lost'::text
            ]))");
        } else {
            DB::statement("ALTER TABLE client_requests MODIFY COLUMN detailed_status ENUM(
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
            ) DEFAULT 'new'");
        }
    }
};
