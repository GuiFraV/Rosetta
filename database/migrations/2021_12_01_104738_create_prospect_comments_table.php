<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProspectCommentsTable extends Migration
{   
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospect_comments', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('prospect_id');
            $table->foreign('prospect_id')->references('id')->on('prospects');
            $table->unsignedBigInteger('author');
            $table->foreign('author')->references('id')->on('managers'); 
            $table->string('comment');            
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
        Schema::dropIfExists('prospect_comments');
    }
}
