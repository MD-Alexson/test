<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tasks extends Migration
{

    public function up()
    {
        Schema::create('tasks',
            function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('name');
            $table->string('comment')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('tasks');
    }
}