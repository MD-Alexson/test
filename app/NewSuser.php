<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewSuser extends Model
{
    protected $table      = 'new_susers';
    protected $primaryKey = 'key';
    public $incrementing  = false;

    public function suser()
    {
        return $this->belongsTo('App\Suser');
    }
    
    public function project()
    {
        return $this->belongsTo('App\Project', 'project_domain');
    }
}