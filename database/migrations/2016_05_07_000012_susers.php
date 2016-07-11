<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Susers extends Migration
{

    public function up()
    {
        Schema::create('susers',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_domain');
            $table->integer('level_id');

            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('password_raw', 20);

            $table->boolean('status')->default(true);
            $table->boolean('expire')->default(false);
            $table->integer('expires')->default(2147483647);
            $table->string('rand');

            $table->rememberToken();
            $table->timestamps();

            $table->unique(['project_domain', 'email']);

            $table->foreign('project_domain')->references('domain')->on('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('levels')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('susers');
    }
}