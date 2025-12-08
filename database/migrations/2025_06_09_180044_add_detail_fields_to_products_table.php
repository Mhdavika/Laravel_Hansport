<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('desc_image_1')->nullable();
            $table->string('desc_image_2')->nullable();
            $table->string('desc_image_3')->nullable();
            $table->string('color_options')->nullable(); // misal "Red,Black,Blue"
            $table->string('size_options')->nullable();  // misal "S,M,L,XL"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
                'full_description',
                'desc_image_1',
                'desc_image_2',
                'desc_image_3',
                'color_options',
                'size_options',
            ]);
        });
    }
};
