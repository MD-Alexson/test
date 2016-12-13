<?php

use Illuminate\Database\Migrations\Migration;

class SuserPassword extends Migration
{
    protected $table     = 'suser_passwords';
    public $timestamps   = false;
    public $incrementing = false;

    public function suser()
    {
        return $this->belongsTo('App\Suser', 'suser_id');
    }
}