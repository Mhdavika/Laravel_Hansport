<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeToCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Tambah product_size_id kalau belum ada
            if (!Schema::hasColumn('carts', 'product_size_id')) {
                $table->unsignedBigInteger('product_size_id')->nullable()->after('product_id');

                $table->foreign('product_size_id')
                    ->references('id')
                    ->on('product_sizes')
                    ->onDelete('set null');
            }

            // Tambah size kalau belum ada
            if (!Schema::hasColumn('carts', 'size')) {
                $table->string('size', 20)->nullable()->after('product_size_id');
            }
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'product_size_id')) {
                $table->dropForeign(['product_size_id']);
                $table->dropColumn('product_size_id');
            }

            if (Schema::hasColumn('carts', 'size')) {
                $table->dropColumn('size');
            }
        });
    }
}
