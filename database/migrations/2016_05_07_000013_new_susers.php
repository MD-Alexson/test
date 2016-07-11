<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewSusers extends Migration
{

    public function up()
    {
        Schema::create('new_susers',
            function (Blueprint $table) {
            $table->string('key');
            $table->string('project_domain');
            $table->integer('suser_id');
            $table->timestamps();

            $table->primary('key');
            $table->foreign('project_domain')->references('domain')->on('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('suser_id')->references('id')->on('susers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('new_susers');
    }
}