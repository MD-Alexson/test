<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Level;
use App\Payment;
use App\Project;
use Auth;
use Request;
use Session;
use Validator;

class IprController extends Controller {

    // VALIDATOR

    private $messages = [
        "name.required" => "Вы не указали название!",
        "text_key.min" => "Ключ должен иметь длину 14 символов!",
        "text_key.max" => "Ключ должен иметь длину 14 символов!",
        "new_keys.required" => "Вы не загрузили ключи!",
        "action.required" => "Не указано действие!",
        "ids.required" => "Не указаны категории!"
    ];

    // VIEWS

    public function index() {
        $project = Project::findOrFail(Session::get('selected_project'));
        $data['title'] = "Настройки инфопротектора / " . $project->name;
        $ipr_levels = $project->ipr_levels()->orderBy('id', 'desc')->get();
        return view('backend.ipr.index')->with('data', $data)->with('project', $project)->with('ipr_levels', $ipr_levels);
    }

    public function add() {
        $project = Project::findOrFail(Session::get('selected_project'));
        $data['title'] = "Добавить настройки уровень доступа инфопротектора / " . $project->name;
        return view('backend.ipr.add')->with('data', $data)->with('project', $project);
    }

    public function edit($ipr_level_id) {
        $project = Project::findOrFail(Session::get('selected_project'));
        $ipr_level = \App\IprLevel::findOrFail($ipr_level_id);
        if ($ipr_level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect()->back()->with('popup_info', ['Ошибка', 'Nope!']);
        }
        $data['title'] = "Редактировать настройки Инфопротектора / " . $project->name;
        return view('backend.ipr.edit')->with('data', $data)->with('ipr_level', $ipr_level)->with('project', $project);
    }

    // HANDLERS

    public function store() {
        Request::flash();
        $v = Validator::make(Request::all(), ['name' => 'required|max:40', 'test_key' => 'min:14|max:14'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail(Session::get('selected_project'));

        $ipr_level = new \App\IprLevel();
        $ipr_level->name = htmlspecialchars(Request::input('name'));
        $ipr_level->test_key = htmlspecialchars(Request::input('test_key'));
        $ipr_level->project()->associate($project);
        $ipr_level->save();

        $keys = Request::file('new_keys');
        $fp = fopen($keys->getRealPath(), 'rb');
        while (($key = fgets($fp)) !== false) {
            $key = preg_replace('/[^\w-]/', '', $key);
            $key = substr($key, 0, 14);
            if (strlen($key) && !\App\IprKey::where('key', $key)->where('ipr_level_id', $ipr_level->id)->count()) {
                $ipr_key = new \App\IprKey();
                $ipr_key->key = $key;
                $ipr_key->ipr_level()->associate($ipr_level);
                $ipr_key->save();
            }
        }
        fclose($fp);

        return redirect('/ipr');
    }

    public function update($ipr_level_id) {
        $v = Validator::make(Request::all(), ['name' => 'required|max:40', 'test_key' => 'min:14|max:14'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $ipr_level = \App\IprLevel::findOrFail($ipr_level_id);
        $ipr_level->name = htmlspecialchars(Request::input('name'));
        $ipr_level->test_key = htmlspecialchars(Request::input('test_key'));

        $ipr_level->save();

        if (Request::hasFile('new_keys')) {
            $keys = Request::file('new_keys');
            $fp = fopen($keys->getRealPath(), 'rb');
            while (($key = fgets($fp)) !== false) {
                $key = preg_replace('/[^\w-]/', '', $key);
                $key = substr($key, 0, 14);
                if (strlen($key) && !\App\IprKey::where('key', $key)->where('ipr_level_id', $ipr_level->id)->count()) {
                    $ipr_key = new \App\IprKey();
                    $ipr_key->key = $key;
                    $ipr_key->ipr_level()->associate($ipr_level);
                    $ipr_key->save();
                }
            }
            fclose($fp);
        }

        return redirect('/ipr/' . $ipr_level->id . '/edit');
    }

    public function batch() {
        $v = Validator::make(Request::all(), ['action' => 'required', 'ids' => 'required'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $action = htmlspecialchars(Request::input('action'));
        $ids = htmlspecialchars(Request::input('ids'));

        $arr = explode(",", $ids);
        unset($arr[0]);

        foreach ($arr as $id) {
            $pay = Payment::findOrFail($id);
            if ($pay->project->user->id !== Auth::guard('backend')->id()) {
                return redirect()->back()->with('popup_info', ['Ошибка', 'Nope!']);
            }
            if ($action === "delete") {
                $pay->delete();
            }
        }
        return redirect()->back();
    }

    public function delete($ipr_level_id) {
        $ipr_level = \App\IprLevel::findOrFail($ipr_level_id);
        if ($ipr_level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect()->back()->with('popup_info', ['Ошибка', 'Nope!']);
        }
        $ipr_level->delete();
        return redirect()->back();
    }

}
