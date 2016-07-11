<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table   = 'files';
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}