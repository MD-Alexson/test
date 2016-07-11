<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPassword extends Model
{
    protected $table      = 'users_passwords';
    protected $primaryKey = 'key';
    public $incrementing  = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}