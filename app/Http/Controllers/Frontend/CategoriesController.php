<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\Project;
use Request;

class CategoriesController extends Controller
{
    public function index($domain){
        $project = Project::findOrFail($domain);
        $data['title'] = "Категории / ".$project->name;

        $data['header_bg'] = config("app.url")."/assets/images/thumbnails/headers/0.jpg";
        if(!empty($project->image)){
            $data['header_bg'] = pathTo($project->image, "imagepath");
        }

        $cats = [];
        $allowed = Request::input('allowed')['categories'];
        $menu = Request::input('allowed')['categories'];
        foreach ($allowed as $cat_id){
            $cat = Category::findOrFail($cat_id);
            array_push($cats, $cat);
        }
        return view('frontend.categories')->with('data', $data)->with('cats', $cats)->with('project', $project)->with('menu', $menu);
    }

    public function show($domain, $cat_id){
        $category = Category::findOrFail($cat_id);
        $project = Project::findOrFail($domain);

        $data['header_bg'] = config("app.url")."/assets/images/thumbnails/headers/0.jpg";
        if(!empty($category->image)){
            $data['header_bg'] = pathTo($category->image, 'imagepath');
        }

        $data['title'] = $category->name." / ".$project->name;
        $posts = [];
        $allowed = Request::input('allowed')['posts'];
        $menu = Request::input('allowed')['categories'];
        foreach ($allowed as $post_id){
            $post = Post::findOrFail($post_id);
            if($post->category->id === $category->id){
                array_push($posts, $post);
            }
        }
        usort($posts, "cmp");
        
        $cats = [];
        $allowed = Request::input('allowed')['categories'];
        foreach ($allowed as $cat_id){
            $cat = Category::findOrFail($cat_id);
            array_push($cats, $cat);
        }
        
        return view('frontend.postsByCat')->with('data', $data)->with('posts', $posts)->with('cats', $cats)->with('category', $category)->with('project', $project)->with('menu', $menu);
    }

}