<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table      = 'tasks';
    protected $primaryKey = 'id'; // Можно не ставить, т.к id - $primaryKey по-умолчанию
    public $incrementing  = true;
    public $timestamps = false;

//  Связи с другими моделями (в данном примере нет)
//    public function user()
//    {
//        return $this->belongsTo('App\User');
//    }

    
//  Функции-мутаторы (в данном примере нет)
//    public function setRemoteDomainAttribute($value)
//    {
//        if (empty($value)) { // will check for empty string, null values, see php.net about it
//            $this->attributes['remote_domain'] = NULL;
//        } else {
//            $this->attributes['remote_domain'] = $value;
//        }
//    }
}