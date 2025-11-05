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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // kode voucher unik
            $table->enum('type', ['fixed', 'percentage']); // jenis diskon
            $table->decimal('value', 15, 2); // nilai diskon
            $table->date('expires_at')->nullable(); // tanggal kedaluwarsa
            $table->integer('max_uses')->nullable(); // batas penggunaan
            $table->integer('uses')->default(0); // jumlah penggunaan saat ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
