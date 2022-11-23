<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarToJugadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jugadores', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('tiemporeaccion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jugadores', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
}
