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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }

            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable();
            }

            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable();
            }

            if (!Schema::hasColumn('users', 'approval_status')) {
                $table->string('approval_status')->default('pending'); // pending, approved, rejected
            }

            if (!Schema::hasColumn('users', 'type')) {
                $table->string('type')->default('team_member'); // team_member, vendor
            }

            // Add indexes for better performance
            $table->index(['type', 'approval_status']);
            $table->index(['email']);
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'avatar',
                'position',
                'department',
                'approval_status',
                'type'
            ]);
        });
    }
};
