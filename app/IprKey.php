<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IprKey extends Model {

    protected $table = 'ipr_keys';
    public $timestamps = false;

    public function ipr_level() {
        return $this->belongsTo('App\IprLevel');
    }

}
