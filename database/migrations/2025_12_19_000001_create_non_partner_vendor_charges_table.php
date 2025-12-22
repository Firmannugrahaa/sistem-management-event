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
        Schema::create('non_partner_vendor_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_request_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('service_type'); // e.g., Catering, MUA, Dekorasi
            $table->string('vendor_name');
            $table->string('vendor_contact')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('charge_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_partner_vendor_charges');
    }
};
