<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_searches', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name');
            $table->string('country');
            $table->string('email');
            $table->string('phone');
            $table->string('type');
            $table->unsignedBigInteger('creator')->nullable();
            $table->foreign('creator')->references('id')->on('managers');
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
        Schema::dropIfExists('marketing_searches');
    }
}
