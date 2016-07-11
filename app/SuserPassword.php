<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuserPassword extends Model
{
    protected $table      = 'susers_passwords';
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