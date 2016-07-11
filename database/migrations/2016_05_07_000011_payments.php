<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payments extends Migration
{

    public function up()
    {
        Schema::create('payments',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_domain');
            $table->integer('level_id');

            $table->string('method');
            $table->string('key');
            $table->string('item_id');

            $table->boolean('membership')->default(false);
            $table->smallInteger('membership_num')->default(1);
            $table->string('membership_type', 6)->default('month');

            $table->string('subject')->nullable();
            $table->text('message')->nullable();

            $table->string('subject2')->nullable();
            $table->text('message2')->nullable();

            $table->unique(['project_domain', 'item_id']);

            $table->foreign('project_domain')->references('domain')->on('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('levels')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('payments');
    }
}