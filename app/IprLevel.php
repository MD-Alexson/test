<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IprLevel extends Model {

    protected $table = 'ipr_levels';
    public $timestamps = false;

    public function posts() {
        return $this->hasMany('App\Post');
    }

    public function susers() {
        return $this->belongsToMany('App\Suser', 'susers_ipr_keys')->withPivot('key');
    }

    public function project() {
        return $this->belongsTo('App\Project', 'project_domain');
    }

    public function ipr_keys() {
        return $this->hasMany('App\IprKey');
    }

}
