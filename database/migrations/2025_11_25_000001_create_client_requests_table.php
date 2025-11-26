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
        Schema::create('client_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Client yang buat request
            $table->string('client_name'); // Nama client (bisa jadi belum punya akun)
            $table->string('client_email');
            $table->string('client_phone');
            $table->date('event_date');
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('event_type'); // Wedding, Birthday, Corporate, dll
            $table->text('message')->nullable(); // Pesan tambahan dari client
            $table->enum('status', ['pending', 'on_process', 'done'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Staff yang ditugaskan
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null'); // Vendor terkait (jika ada)
            $table->string('request_source')->default('website'); // website, phone, email, dll
            $table->text('notes')->nullable(); // Catatan internal
            $table->timestamp('responded_at')->nullable(); // Kapan pertama kali direspond
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('event_date');
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_requests');
    }
};
