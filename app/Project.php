<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table      = 'projects';
    protected $primaryKey = 'domain';
    public $incrementing  = false;

    public function categories()
    {
        return $this->hasMany('App\Category', 'project_domain');
    }

    public function webinars()
    {
        return $this->hasMany('App\Webinar', 'project_domain');
    }

    public function levels()
    {
        return $this->hasMany('App\Level', 'project_domain');
    }

    public function ipr_levels()
    {
        return $this->hasMany('App\IprLevel', 'project_domain');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'project_domain');
    }

    public function homeworks()
    {
        return $this->hasMany('App\Homework', 'project_domain');
    }

    public function susers()
    {
        return $this->hasMany('App\Suser', 'project_domain');
    }

    public function susers_passwords()
    {
        return $this->hasMany('App\SuserPassword', 'project_domain');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment', 'project_domain');
    }

    public function emails()
    {
        return $this->hasMany('App\Email', 'project_domain');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    // -- Has many through

    public function posts()
    {
        return $this->hasManyThrough('App\Post', 'App\Category', 'project_domain');
    }

    // -- Mutators

    public function setRemoteDomainAttribute($value)
    {
        if (empty($value)) { // will check for empty string, null values, see php.net about it
            $this->attributes['remote_domain'] = NULL;
        } else {
            $this->attributes['remote_domain'] = $value;
        }
    }
}