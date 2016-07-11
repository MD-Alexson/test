<?php

namespace App\Http\Controllers\Backend;

use App\Homework;
use App\Http\Controllers\Controller;
use App\Post;
use App\Project;
use Auth;
use Request;
use Session;
use Storage;
use Validator;

class HomeworksController extends Controller
{
    private $messages = [
        "action.required" => "Не указано действие!",
        "ids.required" => "Не указаны пользователи!"
    ];

    // VIEWS

    public function index()
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $data['title'] = "Домашние задания / ".$project->name;
        $homeworks = $project->homeworks()->orderBy('created_at', 'desc')->paginate((int) Session::get('perpage'));
        return view('backend.homeworks.index')->with('data', $data)->with('project', $project)->with('homeworks', $homeworks);
    }

    public function indexByPost($post_id)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $post = Post::findOrFail($post_id);
        $data['title'] = "Комментарии / ".$project->name;
        $homeworks = $post->homeworks()->orderBy('created_at', 'desc')->paginate((int) Session::get('perpage'));
        return view('backend.homeworks.indexByPost')->with('data', $data)->with('project', $project)->with('homeworks', $homeworks)->with('post', $post);
    }

    // HANDLERS

    public function check($homework_id){
        $homework = Homework::findOrFail($homework_id);
        $homework->checked = true;
        $homework->save();
        return redirect()->back();
    }

    public function uncheck($homework_id){
        $homework = Homework::findOrFail($homework_id);
        $homework->checked = false;
        $homework->save();
        return redirect()->back();
    }

    public function delete($homework_id)
    {
        $homework = Homework::findOrFail($homework_id);
        if(Storage::exists($homework->file_path)){
            Storage::delete($homework->file_path);
        }
        $homework->delete();
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
            $homework = Homework::findOrFail($id);
            if ($homework->project->user->id !== Auth::guard('backend')->id()) {
                return redirect('/homework')->with('popup_info', ['Ошибка', 'Нет прав!']);
            }
            if($action === "delete"){
                if(Storage::exists($homework->file_path)){
                    Storage::delete($homework->file_path);
                }
                $homework->delete();
            } elseif($action === "checked"){
                $homework->checked = (int) htmlspecialchars(Request::input('checked'));
                $homework->save();
            }
        }
        return redirect()->back();
    }
}