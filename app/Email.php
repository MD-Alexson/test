<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table   = 'email';
    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_domain');
    }
}