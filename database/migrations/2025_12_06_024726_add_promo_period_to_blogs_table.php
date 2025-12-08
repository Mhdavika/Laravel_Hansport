<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromoPeriodToBlogsTable extends Migration
{
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dateTime('promo_start')->nullable()->after('discount_percent');
            $table->dateTime('promo_end')->nullable()->after('promo_start');
        });
    }

    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['promo_start', 'promo_end']);
        });
    }
}
