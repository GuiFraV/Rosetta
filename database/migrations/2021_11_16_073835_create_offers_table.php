<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('id_prospect');
            $table->foreign('id_prospect')->references('id')->on('prospects');
            $table->unsignedBigInteger('actor');
            $table->foreign('actor')->references('id')->on('managers');
            $table->string('cityFrom');   
            $table->string('cityTo');   
            $table->float('offer');   
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('offers');
    }
}
