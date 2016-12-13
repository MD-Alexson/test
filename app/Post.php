<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    public function files()
    {
        return $this->hasMany('App\File');
    }

    public function videos()
    {
        return $this->hasMany('App\Video');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'post_id');
    }

    public function homeworks()
    {
        return $this->hasMany('App\Homework', 'post_id');
    }

    public function requiredPosts()
    {
        return $this->belongsToMany('App\Post', 'required_homeworks', 'post_id', 'required_post_id');
    }

    public function requiredBy()
    {
        return $this->belongsToMany('App\Post', 'required_homeworks', 'required_post_id', 'post_id');
    }

    public function levels()
    {
        return $this->belongsToMany('App\Level', 'posts_levels');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
    
    public function ipr_level()
    {
        return $this->belongsTo('App\IprLevel');
    }
}