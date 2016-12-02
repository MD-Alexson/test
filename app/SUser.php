<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Suser extends Authenticatable
{
    protected $table = 'susers';

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function homeworks()
    {
        return $this->hasMany('App\Homework', 'suser_id');
    }

    public function new_susers()
    {
        return $this->hasMany('App\NewSuser');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_domain');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }
    
    public function ipr_key()
    {
        return $this->belongsTo('App\Ipr', 'ipr_key');
    }
}