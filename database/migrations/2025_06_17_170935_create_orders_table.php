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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // jika ada auth
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('postal_code');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->text('address');
            $table->enum('payment_method', ['cod', 'transfer', 'ewallet']);
            $table->string('bank_name')->nullable();
            $table->string('ewallet_name')->nullable();
            $table->string('proof_file')->nullable();
            $table->integer('total_price');
            $table->string('status')->default('pending'); // pending, confirmed, canceled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
