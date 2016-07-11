<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoriesLevels extends Migration
{

    public function up()
    {
        Schema::create('categories_levels',
            function (Blueprint $table) {
            $table->integer('category_id');
            $table->integer('level_id');

            $table->primary(['category_id', 'level_id']);

            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('levels')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('categories_levels');
    }
}