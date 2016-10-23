<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markers', function(Blueprint $table){
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('field_id')->unsigned();
            $table->double('lat');
            $table->double('lng');
            $table->timestamps();

            $table->foreign('field_id')->references('id')->on('fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
