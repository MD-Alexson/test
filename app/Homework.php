<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $table   = 'homeworks';

    public function suser()
    {
        return $this->belongsTo('App\Suser', 'suser_id');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_domain');
    }
}