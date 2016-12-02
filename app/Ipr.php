<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ipr extends Model
{
    protected $table   = 'ipr';
    protected $primaryKey = 'key';
    public $timestamps = false;
    public $incrementing = false;
    
    public function susers()
    {
        return $this->hasMany('App\Suser', 'ipr_key');
    }
}