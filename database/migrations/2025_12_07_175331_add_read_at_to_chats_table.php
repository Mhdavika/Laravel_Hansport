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
    Schema::table('chats', function (Blueprint $table) {
        $table->timestamp('read_at')->nullable();  // Menambahkan kolom read_at
    });
}

public function down()
{
    Schema::table('chats', function (Blueprint $table) {
        $table->dropColumn('read_at');  // Menghapus kolom read_at jika diperlukan
    });

    }
};
