<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasSizeToProductsTable extends Migration
{
    public function up()
    {
        // Tambah kolom hanya kalau belum ada
        if (!Schema::hasColumn('products', 'has_size')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('has_size')->default(false)->after('stock');
            });
        }
    }

    public function down()
    {
        // Hapus kolom hanya kalau ada
        if (Schema::hasColumn('products', 'has_size')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('has_size');
            });
        }
    }
}
