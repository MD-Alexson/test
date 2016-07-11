<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Invoices extends Migration
{

    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('plan_id');
            $table->integer('term');

            $table->primary('id');
            $table->foreign('plan_id')->references('id')->on('plans')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('invoices');
    }
}