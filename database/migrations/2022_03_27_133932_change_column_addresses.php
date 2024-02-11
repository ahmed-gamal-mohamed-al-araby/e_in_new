<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string("region_city")->nullable()->change();
            $table->string("street")->nullable()->change();
            $table->string("building_no")->nullable()->change();
            $table->string("city_id")->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            //
        });
    }
}
