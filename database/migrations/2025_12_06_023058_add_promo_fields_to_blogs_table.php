<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromoFieldsToBlogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->integer('original_price')->nullable()->after('type');
            $table->integer('promo_price')->nullable()->after('original_price');
            $table->integer('discount_percent')->nullable()->after('promo_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['original_price', 'promo_price', 'discount_percent']);
        });
    }
}
