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
        // Add indexes for user table to improve search performance
        Schema::table('users', function (Blueprint $table) {
            $table->index(['owner_id'], 'users_owner_id_index');
            $table->index(['name'], 'users_name_index');
            $table->index(['email'], 'users_email_index');
            $table->index(['username'], 'users_username_index');
        });

        // Add indexes for vendor table to improve search performance
        Schema::table('vendors', function (Blueprint $table) {
            $table->index(['user_id'], 'vendors_user_id_index');
            $table->index(['category'], 'vendors_category_index');
            $table->index(['contact_person'], 'vendors_contact_person_index');
            $table->index(['phone_number'], 'vendors_phone_number_index');
            $table->index(['service_type_id'], 'vendors_service_type_id_index');
        });

        // Add index for model_has_roles table to improve role filtering
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->index(['model_id', 'role_id'], 'model_has_roles_model_role_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['users_owner_id_index']);
            $table->dropIndex(['users_name_index']);
            $table->dropIndex(['users_email_index']);
            $table->dropIndex(['users_username_index']);
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropIndex(['vendors_user_id_index']);
            $table->dropIndex(['vendors_category_index']);
            $table->dropIndex(['vendors_contact_person_index']);
            $table->dropIndex(['vendors_phone_number_index']);
            $table->dropIndex(['vendors_service_type_id_index']);
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropIndex(['model_has_roles_model_role_index']);
        });
    }
};
