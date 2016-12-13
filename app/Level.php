<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model {

    protected $table = 'levels';
    public $timestamps = false;

    public function susers() {
        return $this->hasMany('App\Suser');
    }

    public function posts() {
        return $this->belongsToMany('App\Post', 'posts_levels');
    }

    public function categories() {
        return $this->belongsToMany('App\Category', 'categories_levels');
    }

    public function project() {
        return $this->belongsTo('App\Project', 'project_domain');
    }

}
