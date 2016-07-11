<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewUser extends Model
{
    protected $table      = 'new_users';
    protected $primaryKey = 'key';
    public $timestamps    = false;
    public $incrementing  = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}