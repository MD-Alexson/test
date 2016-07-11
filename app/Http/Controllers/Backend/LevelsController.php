<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Level;
use App\Project;
use Auth;
use Request;
use Session;
use Validator;

class LevelsController extends Controller
{
    // VALIDATOR

    private $messages = [
        'name.required' => "Вы не указали название уровня доступа!",
        'name.max' => "Максимальная длина названия - 255 символов!",
        'action.required' => "Не указано действие! Не редактируйте коды страниц!",
        'ids.required' => "Не указаны уровни! Не редактируйте коды страниц!"
    ];

    // VIEWS

    public function index()
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $data['title'] = "Уровни доступа / ".$project->name;
        return view('backend.levels.index')->with('data', $data)->with('project', $project);
    }

    // HANDLERS

    public function store()
    {
        $project = Project::findOrFail(Session::get('selected_project'));

        $v = Validator::make(Request::all(), ['name' => 'required|max:255'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $level       = new Level();
        $level->name = htmlspecialchars(Request::input('name'));
        if($project->levels()->where('name', $level->name)->count()){
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Уровень с таким названием уже существует!']);
        }
        if (Request::has('open')) {
            foreach($project->levels as $lvl){
                $lvl->open = false;
                $lvl->save();
            }
            $level->open = true;
        }
        if (Request::has('hidden')) {
            $level->hidden = true;
        }
        $level->project()->associate($project);
        $level->save();
        return redirect('/levels');
    }

    public function update($level_id)
    {
        $project = Project::findOrFail(Session::get('selected_project'));

        $v = Validator::make(Request::all(), ['name' => 'required|max:255'],
                $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $level = Level::findOrFail($level_id);
        $old_name = $level->name;

        if ($level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
        }

        $level->name = htmlspecialchars(Request::input('name'));
        if($project->levels()->where('name', $level->name)->where('name', '!=', $old_name)->count()){
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Уровень с таким названием уже существует!']);
        }
        if (Request::has('open')) {
            foreach($project->levels as $lvl){
                $lvl->open = false;
                $lvl->save();
            }
            $level->open = true;
        } else {
            $level->open = false;
        }
        if (Request::has('hidden')) {
            $level->hidden = true;
        } else {
            $level->hidden = false;
        }
        $level->save();
        return redirect('/levels');
    }

    public function batch()
    {
        $v = Validator::make(Request::all(), ['action' => 'required', 'ids' => 'required'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $action = htmlspecialchars(Request::input('action'));
        $ids = htmlspecialchars(Request::input('ids'));

        $arr = explode(",", $ids);
        unset($arr[0]);

        foreach ($arr as $id){
            $level = Level::findOrFail($id);
            if ($level->project->user->id !== Auth::guard('backend')->id()) {
                return redirect('/levels')->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
            }
            switch ($action){
                case "delete":
                    $level->delete();
                    break;
            }
        }
        return redirect('/levels');
    }

    public function delete($level_id)
    {
        $level = Level::findOrFail($level_id);
        if ($level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect('/levels')->with('popup_info',
                    ['Ошибка', 'Вы выбрали не ваш уровень!']);
        }
        $level->delete();
        return redirect('/levels');
    }
}