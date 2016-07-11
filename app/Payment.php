<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table   = 'payments';
    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_domain');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }
}