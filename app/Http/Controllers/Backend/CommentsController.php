<?php

namespace App\Http\Controllers\Backend;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Post;
use App\Project;
use Auth;
use Request;
use Session;
use Validator;

class CommentsController extends Controller
{
    private $messages = [
        "action.required" => "Не указано действие!",
        "ids.required" => "Не указаны пользователи!"
    ];

    // VIEWS

    public function index()
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $data['title'] = "Комментарии / ".$project->name;
        $comments = $project->comments()->orderBy('created_at', 'desc')->paginate((int) Session::get('perpage'));
        return view('backend.comments.index')->with('data', $data)->with('project', $project)->with('comments', $comments);
    }

    public function indexByPost($post_id)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $post = Post::findOrFail($post_id);
        $data['title'] = "Комментарии / ".$project->name;
        $comments = $post->comments()->orderBy('created_at', 'desc')->paginate((int) Session::get('perpage'));
        return view('backend.comments.indexByPost')->with('data', $data)->with('project', $project)->with('comments', $comments)->with('post', $post);
    }

    // HANDLERS



    public function allow($comment_id){
        $comment = Comment::findOrFail($comment_id);
        $comment->allowed = true;
        $comment->save();
        return redirect()->back();
    }

    public function disable($comment_id){
        $comment = Comment::findOrFail($comment_id);
        $comment->allowed = false;
        $comment->save();
        return redirect()->back();
    }

    public function delete($comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $comment->delete();
        return redirect()->back();
    }

    public function batch(){
        $v = Validator::make(Request::all(), ['action' => 'required', 'ids' => 'required'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $action = htmlspecialchars(Request::input('action'));
        $ids = htmlspecialchars(Request::input('ids'));

        $arr = explode(",", $ids);
        unset($arr[0]);

        foreach ($arr as $id){
            $comment = Comment::findOrFail($id);
            if ($comment->project->user->id !== Auth::guard('backend')->id()) {
                return redirect('/comment')->with('popup_info', ['Ошибка', 'Нет прав!']);
            }
            if($action === "delete"){
                $comment->delete();
            } elseif($action === "allowed"){
                $comment->allowed = (int) htmlspecialchars(Request::input('allowed'));
                $comment->save();
            }
        }
        return redirect()->back();
    }
}