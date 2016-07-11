<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Notification;
use Auth;

class NotificationsController extends Controller
{

    public function read($not_id){
        $not = Notification::findOrFail($not_id);
        if($not->user->id !== Auth::guard('backend')->id()){
            return "fail";
        }
        $not->read = true;
        $not->save();
        return "success";
    }

    public function delete($not_id){
        $not = Notification::findOrFail($not_id);
        if($not->user->id !== Auth::guard('backend')->id()){
            return "fail";
        }
        $not->delete();
        return "success";
    }
}