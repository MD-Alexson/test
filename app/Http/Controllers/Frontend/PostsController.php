<?php

namespace App\Http\Controllers\Frontend;

use App\Comment;
use App\Homework;
use App\Http\Controllers\Controller;
use App\Post;
use App\Project;
use Auth;
use Request;
use Session;
use Storage;
use Validator;
use function fileSaveHW;
use function pathTo;

class PostsController extends Controller
{

    private $messages = [
        'text.required' => "Вы не ввели текст сообщения!"
    ];

    public function index($domain){
        $project = Project::findOrFail($domain);
        $data['header_bg'] = config("app.url")."/assets/images/thumbnails/headers/0.jpg";
        if(!empty($project->image)){
            $data['header_bg'] = pathTo($project->image, 'imagepath');
        }
        $data['title'] = "Все публикации / ".$project->name;
        $posts = [];
        $allowed = Request::input('allowed')['posts'];
        $menu = Request::input('allowed')['categories'];
        foreach ($allowed as $post_id){
            $post = Post::findOrFail($post_id);
            array_push($posts, $post);
        }
        return view('frontend.posts')->with('data', $data)->with('posts', $posts)->with('project', $project)->with('menu', $menu);
    }

    public function show($domain, $post_id){
        $post = Post::findOrFail($post_id);
        $project = Project::findOrFail($domain);
        $data['header_bg'] = config("app.url")."/assets/images/thumbnails/headers/0.jpg";
        if(!empty($post->image)){
            $data['header_bg'] = pathTo($post->image, 'imagepath');
        }
        $data['title'] = $post->name." / ".$project->name;
        $menu = Request::input('allowed')['categories'];
        return view('frontend.post')->with('data', $data)->with('post', $post)->with('project', $project)->with('menu', $menu);
    }
    
    /* -- */

    public function comment($domain, $post_id){
        $v = Validator::make(Request::all(), ['text' => 'required'], $this->messages);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $user = Auth::guard(Session::get('guard'))->user();

        $project = Project::findOrFail($domain);
        $post = Post::findOrFail($post_id);
        $allowed = Request::input('allowed')['posts'];

        if(!in_array($post->id, $allowed)){
            return redirect('/');
        }

        $text = htmlspecialchars(Request::input('text'));

        $comment = new Comment();
        $comment->text = $text;
        $comment->project()->associate($project);
        $comment->post()->associate($post);
        if(($project->user->id === $user->id && Session::get('guard') === 'backend') || !$post->comments_moderate){
            $comment->allowed = true;
        } else {
            $comment->allowed = false;
        }
        $comment->commentable()->associate($user);
        $comment->save();

        if($comment->allowed){
            return redirect('/posts/'.$post->id)->with('popup_info', ['Комментарий', 'Вы успешно добавили комментарий!']);
        } else {
            return redirect('/posts/'.$post->id)->with('popup_info', ['Комментарий', 'Вы успешно добавили комментарий! Он будет добавлен после проверки администратором']);
        }
    }

    public function commentDestroy($domain, $post_id, $comment_id){
        $project = Project::findOrFail($domain);
        $post = Post::findOrFail($post_id);
        $guard = Session::get('guard');
        $user = Auth::guard($guard)->user();
        $comment = Comment::findOrFail($comment_id);

        if(($guard === 'backend' && $project->user->id === $user->id) || ($guard === 'frontend' && $comment->commentable_id === $user->id)){
            $comment->delete();
            return redirect('/posts/'.$post->id);
        } else {
            return redirect('/posts/'.$post->id)->with('popup_info', ['Удаление комментария', 'У вас нет прав на удаление этого комментария!']);
        }
    }

    public function homework($domain, $post_id){
        $v = Validator::make(Request::all(), ['text' => 'required'], $this->messages);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $user = Auth::guard('frontend')->user();

        $project = Project::findOrFail($domain);
        $post = Post::findOrFail($post_id);
        $allowed = Request::input('allowed')['posts'];

        if(!in_array($post->id, $allowed)){
            return redirect('/');
        }

        $text = htmlspecialchars(Request::input('text'));

        $homework = new Homework();
        $homework->text = $text;
        $homework->project()->associate($project);
        $homework->post()->associate($post);
        $homework->suser()->associate($user);
        if($post->homework_check){
            $homework->checked = false;
        } else {
            $homework->checked = true;
        }
        if(Request::hasFile('file')){
            $file = Request::file('file');
            $homework = fileSaveHW($file, $homework);
        }
        $homework->save();

        if($homework->checked){
            return redirect('/posts/'.$post->id)->with('popup_info', ['Домашнее задание', 'Вы успешно подтвердили домашнее задание!! Оно будет проверенно администратором']);
        } else {
            return redirect('/posts/'.$post->id)->with('popup_info', ['Домашнее задание', 'Вы успешно подтвердили домашнее задание!']);
        }
    }

    public function homeworkUpdate($domain, $post_id, $homework_id){
        $v = Validator::make(Request::all(), ['text' => 'required'], $this->messages);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $user = Auth::guard('frontend')->user();

        $project = Project::findOrFail($domain);
        $post = Post::findOrFail($post_id);
        $homework = Homework::findOrFail($homework_id);
        $allowed = Request::input('allowed')['posts'];

        if($homework->suser->id !== $user->id){
            return redirect('/posts/'.$post->id)->with('popup_info', ['Домашнее задание', 'Нет прав!']);
        }

        if(!in_array($post->id, $allowed)){
            return redirect('/');
        }

        $text = htmlspecialchars(Request::input('text'));

        $homework->text = $text;
        if(Request::hasFile('file')){
            $file = Request::file('file');
            $homework = fileSaveHW($file, $homework);
        }
        $homework->save();

        return redirect('/posts/'.$post->id)->with('popup_info', ['Домашнее задание', 'Вы успешно обновили домашнее задание!']);
    }

    public function homeworkDestroy($domain, $post_id, $homework_id){
        $project = Project::findOrFail($domain);
        $post = Post::findOrFail($post_id);
        $user = Auth::guard('frontend')->user();
        $homework = Homework::findOrFail($homework_id);

        if($homework->suser->id === $user->id){
            if(Storage::exists($homework->file_path)){
                Storage::delete($homework->file_path);
            }
            $homework->delete();
            return redirect('/posts/'.$post->id);
        } else {
            return redirect('/posts/'.$post->id)->with('popup_info', ['Удаление ДЗ', 'У вас нет прав на удаление этого ДЗ!']);
        }
    }

}