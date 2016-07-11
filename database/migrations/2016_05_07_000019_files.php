<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Files extends Migration
{

    public function up()
    {
        Schema::create('files',
            function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->string('path');
            $table->string('name');
            $table->string('type');

            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('files');
    }
}