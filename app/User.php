<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    public function new_users()
    {
        return $this->hasMany('App\NewUser');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }

    // -- Has many through

    public function susers()
    {
        return $this->hasManyThrough('App\Suser', 'App\Project', NULL, 'project_domain');
    }
}