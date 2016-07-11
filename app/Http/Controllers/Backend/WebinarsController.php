<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Project;
use App\Webinar;
use Auth;
use Request;
use Session;
use Storage;
use Validator;
use function translit;

class WebinarsController extends Controller
{
    // VALIDATOR

    private $messages = [
        "name.required" => "Вы не указали название вебинара!",
        "name.max" => "Максимальная длина названия - 255 символов!",
        "url.required" => "Вы не ввели url!",
        "url.max" => "Максимальная длина url - 40 символов!!",
        "date.required" => "Вы не указали дату!",
        "action.required" => "Не указано действие!",
        "ids.required" => "Не указаны категории!"
    ];

    // VIEWS

    public function index()
    {
        $project               = Project::findOrFail(Session::get('selected_project'));
        $data['title']         = "Вебинары / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js']  = [asset("assets/js/datetime.js")];
        $webinars            = $project->webinars()->orderBy('id', 'desc')->get();
        return view('backend.webinars.index')->with('data', $data)->with('project', $project)->with('webinars', $webinars);
    }

    public function add()
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $data['title']        = "Добавить вебинар / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [
            asset("assets/js/datetime.js"),
            asset('assets/js/ckeditor/ckeditor.js'),
            asset('assets/js/ckeditor/adapters/jquery.js'),
            asset('assets/js/ckeditor/init.js')
            ];
        return view('backend.webinars.add')->with('data', $data)->with('project', $project);
    }

    public function edit($webinar_id)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $webinar = Webinar::findOrFail($webinar_id);
        if($webinar->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш вебинар!']);
        }
        $data['title']        = "Редактировать вебинар / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [
            asset("assets/js/datetime.js"),
            asset('assets/js/ckeditor/ckeditor.js'),
            asset('assets/js/ckeditor/adapters/jquery.js'),
            asset('assets/js/ckeditor/init.js')
            ];
        return view('backend.webinars.edit')->with('data', $data)->with('webinar', $webinar)->with('project', $project);
    }

    // HANDLERS

    public function store()
    {
        Request::flash();
        $v = Validator::make(Request::all(), ['name' => 'required|max:255', 'url' => 'required|max:40', 'date' => 'required'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail(Session::get('selected_project'));

        $web = new Webinar();
        $web->name = htmlspecialchars(Request::input('name'));
        $web->url = translit(strtolower(htmlspecialchars(Request::input('url'))));
        $web->sub = htmlspecialchars(Request::input('sub'));

        if(Webinar::where('project_domain', $project->domain)->where('url', $web->url)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данный url уже зарегистрирован в данном проекте!']);
        }

        $web->webinar_code = htmlentities(Request::input('webinar_code'));
        $web->content = htmlentities(Request::input('content'));
        
        $web->date = strtotime(htmlspecialchars(Request::input('date'))) - (int) htmlspecialchars(Request::input('offset'));
        if($web->date <= 0 || $web->date > 2147483647){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Вы ввели некорректную дату!']);
        }
        $web->status = (int) Request::input('status');
        
        if(Request::has('header_dim')){
            $web->header_dim = true;
        }
        if(Request::has('timer')){
            $web->timer = true;
        }
        if(Request::has('display_date')){
            $web->display_date = true;
        }
        if(Request::has('comments')){
            $web->comments = true;
        }
        
        $web->project()->associate($project);
        $web->save();

        if (Request::hasFile('image') && empty (Request::input('image_select'))) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/webinars/'.$web->id.'/';
            $image_save = imageSave(Request::file('image'), $path, 1920, 400);
            if ($image_save) {
                $web->image = $image_save;
            }
        } else if(!empty (Request::input('image_select'))){
            $web->image = htmlspecialchars(Request::input('image_select'));
        }

        $web->save();

        if(Request::has('preview')){
            return redirect(getPreviewLink('webinar', $web->id));
        } else {
            return redirect('/webinars');
        }
    }

    public function update($webinar_id)
    {
        $v = Validator::make(Request::all(), ['name' => 'required|max:255', 'url' => 'required|max:40', 'date' => 'required'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail(Session::get('selected_project'));

        $web = Webinar::findOrFail($webinar_id);
        if($web->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш вебинар!']);
        }

        $old_url = $web->url;

        $web->name = htmlspecialchars(Request::input('name'));
        $web->url = translit(strtolower(htmlspecialchars(Request::input('url'))));
        $web->sub = htmlspecialchars(Request::input('sub'));

        if(Webinar::where('project_domain', $project->domain)->where('url', $web->url)->where('url', "!=", $old_url)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данный url уже зарегистрирован в данном проекте!']);
        }

        $web->webinar_code = htmlentities(Request::input('webinar_code'));
        $web->content = htmlentities(Request::input('content'));

        $web->date = strtotime(htmlspecialchars(Request::input('date'))) - (int) htmlspecialchars(Request::input('offset'));
        if($web->date <= 0 || $web->date > 2147483647){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Вы ввели некорректную дату!']);
        }
        $web->status = (int) Request::input('status');

        if(Request::has('header_dim')){
            $web->header_dim = true;
        } else {
            $web->header_dim = false;
        }
        if(Request::has('timer')){
            $web->timer = true;
        } else {
            $web->timer = false;
        }
        if(Request::has('display_date')){
            $web->display_date = true;
        } else {
            $web->display_date = false;
        }
        if(Request::has('comments')){
            $web->comments = true;
        } else {
            $web->comments = false;
        }
        
        $web->save();

        if (Request::has('image_remove')) {
            if (Storage::exists($web->image)) {
                Storage::delete($web->image);
            }
            $web->image = "";
        }

        if (Request::hasFile('image') && empty (Request::input('image_select'))) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/webinars/'.$web->id.'/';
            $image_save = imageSave(Request::file('image'), $path, 1920, 400);
            if ($image_save) {
                if (Storage::exists($web->image)) {
                    Storage::delete($web->image);
                }
                $web->image = $image_save;
            }
        } else if(!empty (Request::input('image_select'))){
            if (Storage::exists($web->image)) {
                Storage::delete($web->image);
            }
            $web->image = htmlspecialchars(Request::input('image_select'));
        }

        $web->save();


        if(Request::has('preview')){
            return redirect(getPreviewLink('webinar', $web->id));
        } else {
            return redirect()->back()->with('popup_ok', ['Настройки вебинара', 'Вы успешно сохранили настройки вебинара!']);
        }
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
            $web = Webinar::findOrFail($id);
            if($web->project->user->id !== Auth::guard('backend')->id()){
                return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш вебинар!']);
            }
            if($action === "delete"){
                $web_files_path = $web->project->user->id.'/'.$web->project->domain.'/webinars/'.$web->id.'/';
                if(Storage::exists($web_files_path)){
                    Storage::deleteDirectory($web_files_path);
                }
                $web->delete();
            } else if($action === "status"){
                $web->status = (int) Request::input('status');
                $web->save();
            }
        }
        return redirect()->back();
    }

    public function delete($webinar_id)
    {
        $webinar = Webinar::findOrFail($webinar_id);
        if($webinar->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш вебинар!']);
        }
        $web_files_path = $webinar->project->user->id.'/'.$webinar->project->domain.'/webinars/'.$webinar->id.'/';
        if(Storage::exists($web_files_path)){
            Storage::deleteDirectory($web_files_path);
        }
        $webinar->delete();
        return redirect()->back();
    }
}