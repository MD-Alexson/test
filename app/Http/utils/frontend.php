<?php

function frontendSelectLevel($project, $level_id){
    $allowed = [
        "categories" => [],
        "posts" => []
    ];

    foreach($project->categories()->orderBy('order', 'asc')->get() as $cat){
        if($cat->status === "published" || ($cat->status === 'scheduled' && $cat->comingsoon) || $cat->status === "scheduled2"){
            foreach($cat->levels as $lvl){
                if($level_id === $lvl->id){
                    array_push($allowed['categories'], $cat->id);
                }
            }

            if(($cat->upsale && !in_array($cat->id, $allowed['categories'])) || ($cat->posts()->where('upsale', true)->count() && !in_array($cat->id, $allowed['categories']))){
                array_push($allowed['categories'], $cat->id);
            }
        }
    }
    foreach($project->posts()->orderBy('order_all', 'asc')->get() as $post){
        if($post->status === "published" || ($post->status === 'scheduled' && $post->comingsoon) || $post->status === "scheduled2"){
            foreach($post->levels as $lvl2){
                if($level_id === $lvl2->id){
                    array_push($allowed['posts'], $post->id);
                }
            }

            if(($post->category->upsale && !in_array($post->id, $allowed['posts'])) || ($post->upsale && !in_array($post->id, $allowed['posts']))){
                array_push($allowed['posts'], $post->id);
            }
        }
    }
    return $allowed;
}

function frontendCheckLevel($ent, $level_id){
    foreach($ent->levels as $lvl){
        if($level_id === $lvl->id){
            return true;
        }
    }
    return false;
}

function frontendCheckHomeworks($post){
    if($post->requiredPosts->count()){
        foreach($post->requiredPosts as $req){
            $hw = Auth::guard('frontend')->user()->homeworks()->where('post_id', $req->id)->first();
            if(!$hw || !$hw->checked){
                return false;
            }
        }
        return true;
    }
    return true;
}

function clientIp(){
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')){
        $ipaddress = getenv('HTTP_CLIENT_IP');
    } else if(getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } else if(getenv('HTTP_X_FORWARDED')){
        $ipaddress = getenv('HTTP_X_FORWARDED');
    } else if(getenv('HTTP_FORWARDED_FOR')){
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } else if(getenv('HTTP_FORWARDED')){
       $ipaddress = getenv('HTTP_FORWARDED');
    } else if(getenv('REMOTE_ADDR')){
        $ipaddress = getenv('REMOTE_ADDR');
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}

function getHigherLevel($project){
    $higher = $project->levels->first();
    foreach($project->levels as $level){
        if($level->categories()->count() >= $higher->categories()->count()){
            $higher = $level;
        }
    }
    return $level;
}