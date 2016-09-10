<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Project;
use App\Webinar;
use function pathTo;

class WebinarsController extends Controller
{

    public function show($domain, $url){
        $web = Webinar::where('url', $url)->where('status', true)->first();
        if(!$web){
            abort(404);
        }
        $project = Project::findOrFail($domain);
        $data['header_bg'] = config("app.url")."/assets/images/thumbnails/headers/0.jpg";
        if(!empty($web->image)){
            $data['header_bg'] = pathTo($web->image, 'imagepath');
        }
        $data['title'] = $web->name." / ".$project->name;
        if($web->timer){
            $data['assets']['js'] = [asset('/assets/js/jquery.countdown.min.js')];
        }
        return view('frontend_old.webinar')->with('data', $data)->with('web', $web)->with('project', $project);
    }
}