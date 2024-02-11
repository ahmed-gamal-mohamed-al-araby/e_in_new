<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//use Doctrine\DBAL\Driver\PDOMySql\Driver;

class ChangeColumnForeignerClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_clients', function (Blueprint $table) {
            $table->string("person_name")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_clients', function (Blueprint $table) {
            //
        });
    }
}
