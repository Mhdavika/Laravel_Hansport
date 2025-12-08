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
    // Tambahkan kolom hanya jika belum ada (mencegah duplicate column error)
    if (!Schema::hasColumn('chats', 'sender_id')) {
        Schema::table('chats', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id')->nullable(); // Menambahkan kolom sender_id
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade'); // Menyambungkan ke tabel users
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus foreign key dan kolom jika ada. Bungkus dengan try/catch untuk keamanan.
        if (Schema::hasColumn('chats', 'sender_id')) {
            try {
                Schema::table('chats', function (Blueprint $table) {
                    // Hapus constraint foreign key jika ada
                    try {
                        $table->dropForeign(['sender_id']);
                    } catch (\Exception $e) {
                        // jika constraint tidak ditemukan, lanjutkan
                    }

                    // Hapus kolom
                    if (Schema::hasColumn('chats', 'sender_id')) {
                        $table->dropColumn('sender_id');
                    }
                });
            } catch (\Exception $e) {
                // jika terjadi error saat menghapus, jangan menghentikan proses rollback
            }
        }
    }
};
