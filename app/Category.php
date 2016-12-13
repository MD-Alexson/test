<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'categories';

    public function posts() {
        return $this->hasMany('App\Post');
    }

    public function levels() {
        return $this->belongsToMany('App\Level', 'categories_levels');
    }

    public function project() {
        return $this->belongsTo('App\Project', 'project_domain');
    }

}
