<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';
    public $timestamps = false;
    
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}