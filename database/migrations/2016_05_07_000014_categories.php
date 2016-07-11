<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Categories extends Migration
{

    public function up()
    {
        Schema::create('categories',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_domain');

            $table->string('name');
            $table->text('excerpt')->nullable();
            $table->text('category_html')->nullable();

            $table->string('image')->default("");
            $table->boolean('header_dim')->default(false);
            $table->string('thumbnail_128')->default("");
            $table->string('thumbnail_750')->default("");
            $table->boolean('thumbnail_size')->default(false);

            $table->boolean('upsale')->default(false);
            $table->text('upsale_text')->nullable();

            $table->string('status')->default("published");
            $table->boolean('comingsoon')->default(false);
            $table->integer('scheduled')->nullable();
            $table->smallInteger('sch2num')->default(1);
            $table->string('sch2type')->default('day');
            $table->string('sch2typename')->default('дней');

            $table->smallInteger('order')->default(1);

            $table->timestamps();

            $table->foreign('project_domain')->references('domain')->on('projects')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('categories');
    }
}