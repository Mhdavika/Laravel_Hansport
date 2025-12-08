<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserAndAdminIdToChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();  // Add user_id
            $table->unsignedBigInteger('admin_id')->nullable(); // Add admin_id
            
            // Optionally, you can add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['user_id', 'admin_id']);
        });
    }
}
