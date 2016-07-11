<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    protected $table = 'webinars';

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_domain');
    }
}