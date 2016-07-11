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

    public function ratings()
    {
        return $this->hasMany('App\Rating', 'suser_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_domain');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }
}