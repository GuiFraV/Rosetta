<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Trajet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trajets', function (Blueprint $table) {
            $table->id();
            $table->date("date_depart");
            $table->integer('zone_id')->unsigned();
            $table->foreign('zone_id')->references('id')->on('zones');
            $table->integer('manager_id')->unsigned();
            $table->foreign('manager_id')->references('id')->on('managers');
            $table->string("from_others");
            $table->string("to_others");
            $table->integer("distance");
            $table->integer("duration");
            $table->boolean("key");
            $table->boolean("concurants");
            $table->integer("stars");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trajets');
    }
}
