<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('portfolio_images', function (Blueprint $table) {
            $table->boolean('is_featured_in_gallery')->default(false)->after('image_path');
        });
    }

    public function down()
    {
        Schema::table('portfolio_images', function (Blueprint $table) {
            $table->dropColumn('is_featured_in_gallery');
        });
    }
};
