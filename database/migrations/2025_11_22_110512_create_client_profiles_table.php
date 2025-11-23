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
        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable(); // untuk menyimpan sosial media link dalam format JSON
            $table->timestamps();

            $table->unique('user_id'); // Pastikan hanya satu profil per user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_profiles');
    }
};
