<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Levels extends Migration
{

    public function up()
    {
        Schema::create('levels',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('project_domain');
            $table->boolean('open')->default(false);
            $table->boolean('hidden')->default(false);

            $table->foreign('project_domain')->references('domain')->on('projects')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('levels');
    }
}