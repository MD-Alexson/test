<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Webinars extends Migration
{

    public function up()
    {
        Schema::create('webinars',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_domain');

            $table->string('name');
            $table->string('sub')->nullable();
            $table->text('webinar_code')->nullable();
            $table->text('content')->nullable();
            $table->integer('date');
            $table->string('url');
            $table->boolean('status')->default(true);
            $table->string('image')->nullable();
            $table->boolean('header_dim')->default(false);
            $table->boolean('timer')->default(true);
            $table->boolean('display_date')->default(true);
            $table->boolean('comments')->default(true);

            $table->timestamps();

            $table->unique(['project_domain', 'url']);
            $table->foreign('project_domain')->references('domain')->on('projects')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('webinars');
    }
}