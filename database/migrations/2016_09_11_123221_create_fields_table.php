<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fieldFolder');
            $table->string('fieldName');
            $table->date('date');
            $table->integer('user_id')->unsigned();
            $table->double('x_min', 10, 10);
            $table->double('x_max', 10, 10);
            $table->double('y_min', 10, 10);
            $table->double('y_max', 10, 10);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
