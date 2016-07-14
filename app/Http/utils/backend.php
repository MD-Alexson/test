<?php

function getPreviewLink($type, $id){
    $sid = \Session::getId();
    if($type === "project"){
        $project = App\Project::findOrFail($id);
        if(!empty($project->remote_domain)){
            return "http://".$project->remote_domain.'/?sid='.$sid;
        } else {
            return "http://".$project->domain.".".config('app.domain')."/?sid=".$sid;
        }
    } elseif($type === 'category'){
        $category = App\Category::findOrFail($id);
        $project = $category->project;
        if(!empty($project->remote_domain)){
            return "http://".$project->remote_domain.'/categories/'.$category->id.'/?sid='.$sid;
        } else {
            return "http://".$project->domain.".".config('app.domain').'/categories/'.$category->id.'/?sid='.$sid;
        }
    } elseif($type === 'post'){
        $post = App\Post::findOrFail($id);
        $project = $post->category->project;
        if(!empty($project->remote_domain)){
            return "http://".$project->remote_domain.'/posts/'.$post->id.'/?sid='.$sid;
        } else {
            return "http://".$project->domain.".".config('app.domain').'/posts/'.$post->id.'/?sid='.$sid;
        }
    } elseif($type === 'webinar'){
        $web = App\Webinar::findOrFail($id);
        $project = $web->project;
        if(!empty($project->remote_domain)){
            return "http://".$project->remote_domain.'/webinar/'.$web->url;
        } else {
            return "http://".$project->domain.".".config('app.domain').'/webinar/'.$web->url;
        }
    }
}

function sortDefaults(){
    if(!\Session::has('sort.users.order_by') || empty(\Session::get('sort.users.order_by'))) {
        \Session::put('sort.users.order_by', 'created_at');
    }
    if(!\Session::has('sort.users.order') || empty(\Session::get('sort.users.order'))) {
        \Session::put('sort.users.order', 'desc');
    }
    if(!\Session::has('sort.posts_all.order_by') || empty(\Session::get('sort.posts_all.order_by'))) {
        \Session::put('sort.posts_all.order_by', 'order_all');
    }
    if(!\Session::has('sort.posts_all.order') || empty(\Session::get('sort.posts_all.order'))) {
        \Session::put('sort.posts_all.order', 'asc');
    }
    if(!\Session::has('sort.posts.order_by') || empty(\Session::get('sort.posts.order_by'))) {
        \Session::put('sort.posts.order_by', 'order');
    }
    if(!\Session::has('sort.posts.order') || empty(\Session::get('sort.posts.order'))) {
        \Session::put('sort.posts.order', 'asc');
    }
    if(!\Session::has('sort.categories.order_by') || empty(\Session::get('sort.categories.order_by'))) {
        \Session::put('sort.categories.order_by', 'order');
    }
    if(!\Session::has('sort.categories.order') || empty(\Session::get('sort.categories.order'))) {
        \Session::put('sort.categories.order', 'asc');
    }
}