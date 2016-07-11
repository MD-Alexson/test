<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }
}