<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function suser()
    {
        return $this->belongsTo('App\Suser');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_domain');
    }

    // Polymorphic

    public function commentable()
    {
        return $this->morphTo();
    }
}