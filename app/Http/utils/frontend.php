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

            if($cat->upsale && !in_array($cat->id, $allowed['categories'])){
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

            if($post->category->upsale && !in_array($post->id, $allowed['posts'])){
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