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
        Schema::table('users', function (Blueprint $table) {
            $table->string('province')->nullable()->after('phone');
            $table->string('city')->nullable()->after('province');
            $table->string('district')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['province', 'city', 'district', 'postal_code']);
        });
    }
};
