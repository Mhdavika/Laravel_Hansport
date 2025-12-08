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
        $table->string('image1')->default('default.jpg'); // Gambar utama produk
        $table->string('image2')->default('default.jpg'); // Gambar deskripsi 1
        $table->string('image3')->default('default.jpg'); // Gambar deskripsi 2
        $table->string('image4')->default('default.jpg'); // Gambar deskripsi 3
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
