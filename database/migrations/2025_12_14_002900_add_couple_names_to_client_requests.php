<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_requests', function (Blueprint $table) {
            $table->string('groom_name')->nullable()->after('client_name');
            $table->string('bride_name')->nullable()->after('groom_name');
            $table->boolean('fill_couple_later')->default(false)->after('bride_name');
        });
    }

    public function down(): void
    {
        Schema::table('client_requests', function (Blueprint $table) {
            $table->dropColumn(['groom_name', 'bride_name', 'fill_couple_later']);
        });
    }
};
