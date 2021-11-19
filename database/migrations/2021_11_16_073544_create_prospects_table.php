<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name');
            $table->string('country');
            $table->string('email');
            $table->string('phone');
            $table->string('type');
            $table->unsignedBigInteger('actor')->nullable();
            $table->foreign('actor')->references('id')->on('managers');
            $table->unsignedBigInteger('state');
            $table->foreign('state')->references('id')->on('states');
            //$table->boolean('available');
            $table->timestamp('deadline')->nullable();
            $table->timestamp('unavailable_until')->nullable()->default(null);
            //$table->boolean('isActive')->default('1');
            $table->string('loadNumber')->nullable();        
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
        Schema::dropIfExists('prospects');
    }
}
