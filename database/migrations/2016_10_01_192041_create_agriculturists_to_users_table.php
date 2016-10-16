<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgriculturistsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agriculturists_to_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agriculturist_id')->unsigned();
            $table->integer('farmer_id')->unsigned();

            $table->foreign('agriculturist_id')->references('id')->on('users');
            $table->foreign('farmer_id')->references('id')->on('users');
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
