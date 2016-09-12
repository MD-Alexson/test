<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Level;
use App\Project;
use Auth;
use Request;
use Session;
use Storage;
use Validator;
use function getTimePlus;

class CategoriesController extends Controller
{
    // VALIDATOR

    private $messages = [
        "name.required" => "Вы не указали название категории!",
        "name.max" => "Максимальная длина названия - 255 символов!",
        "action.required" => "Не указано действие!",
        "ids.required" => "Не указаны категории!"
    ];

    // VIEWS

    public function index()
    {
        $project               = Project::findOrFail(Session::get('selected_project'));
        $data['title']         = "Категории / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js']  = [asset("assets/js/datetime.js")];
        $categories            = $project->categories()->orderBy(\Session::get('sort.categories.order_by'), \Session::get('sort.categories.order'))->where('parent', -1)->get();
        return view('backend.categories.index')->with('data', $data)->with('project', $project)->with('categories', $categories);
    }

    public function indexByLevel($level_id)
    {
        $project               = Project::findOrFail(Session::get('selected_project'));
        $level = Level::findOrFail($level_id);
        if ($level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
        }
        $data['title']         = "Категории / ".$level->name." / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js']  = [asset("assets/js/datetime.js")];
        $categories            = $level->categories()->orderBy(\Session::get('sort.categories.order_by'), \Session::get('sort.categories.order'))->get();
        return view('backend.categories.index_by_level')->with('data', $data)->with('project', $project)->with('categories', $categories)->with('level', $level);
    }

    public function add($level_id = false)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $data['title']        = "Добавить категорию / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [
            asset("assets/js/datetime.js"),
            asset('assets/js/ckeditor/ckeditor.js'),
            asset('assets/js/ckeditor/adapters/jquery.js'),
            asset('assets/js/ckeditor/init.js')
            ];
        if($level_id){
            $level = \App\Level::find($level_id);
            if(!$level || $level->project->domain !== $project->domain){
                exit('Неправильный уровень доступа!');
            }
        }
        return view('backend.categories.add')->with('data', $data)->with('project', $project)->with('level_id', $level_id);
    }

    public function edit($cat_id)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $category = Category::findOrFail($cat_id);
        if($category->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваша категория!']);
        }
        $data['title']        = "Редактировать категорию / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [
            asset("assets/js/datetime.js"),
            asset('assets/js/ckeditor/ckeditor.js'),
            asset('assets/js/ckeditor/adapters/jquery.js'),
            asset('assets/js/ckeditor/init.js')
            ];
        return view('backend.categories.edit')->with('data', $data)->with('category', $category)->with('project', $project);
    }

    public function orderView(){
        $project               = Project::findOrFail(Session::get('selected_project'));
        $data['title']         = "Порядок категорий / ".$project->name;
        $data['assets']['js']  = [asset("assets/js/jqui_sortable.min.js"), asset("assets/js/sortable.min.js")];
        return view('backend.categories.order')->with('data', $data)->with('project', $project);
    }

    // HANDLERS

    public function store()
    {
        Request::flash();
        $v = Validator::make(Request::all(), ['name' => 'required|max:255'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail(Session::get('selected_project'));

        $cat = new Category;
        $cat->name = htmlspecialchars(Request::input('name'));

        $cat->excerpt = htmlentities(Request::input('excerpt'));
        $cat->category_html = htmlentities(Request::input('category_html'));
        $cat->upsale_text = htmlentities(Request::input('upsale_text'));
        
        $cat->parent = (int) htmlspecialchars(Request::input('parent'));
        
        if(Request::has('sidebar')){
            $cat->sidebar = true;
        }
        $cat->sidebar_type = (int) Request::input('sidebar_type');
        $cat->sidebar_html = htmlentities(Request::input('sidebar_html'));

        $cat->status = htmlspecialchars(Request::input('status'));
        
        if(Request::has('scheduled') && !empty(Request::input('scheduled'))){
            $cat->scheduled = strtotime(htmlspecialchars(Request::input('scheduled'))) - (int) htmlspecialchars(Request::input('offset'));
            if($cat->scheduled <= 0 || $cat->scheduled > 2147483647){
                return redirect()->back()->with('popup_info', ['Ошибка', 'Вы ввели некорректную дату!']);
            }
        } else {
            $cat->scheduled = getTimePlus();
        }
        $cat->sch2num = htmlspecialchars(Request::input('sch2num'));
        $cat->sch2type = htmlspecialchars(Request::input('sch2type'));
        switch($cat->sch2type){
            case "days": $cat->sch2typename = "дней"; break;
            case "weeks": $cat->sch2typename = "недель"; break;
            case "months": $cat->sch2typename = "месяцев"; break;
            default: $cat->sch2typename = "";
        }
        if(Request::has('header_dim')){
            $cat->header_dim = true;
        }
        if(Request::has('upsale')){
            $cat->upsale = true;
        }
        if(Request::has('comingsoon1') || Request::has('comingsoon2')){
            $cat->comingsoon = true;
        }
        $cat->order = (int) $project->categories()->max('order') + 1;

        $cat->project()->associate($project);
        $cat->save();
        
        if(!empty (Request::input('parent_image'))){
            $image_raw = htmlspecialchars(Request::input('parent_image'));
            $image_name = explode('/', $image_raw);
            $image_name = $image_name[count($image_name) - 1];
            $folder_path = "/".Auth::guard('backend')->id().'/'.$project->domain.'/categories/'.$cat->id.'/';
            if (!file_exists($folder_path)) {
                Storage::makeDirectory($folder_path, 0775, true, true);
            }
            $db_path = $folder_path.$image_name;
            $full_path = storage_path("app".$db_path);
            if(filter_var($image_raw, FILTER_VALIDATE_URL) && copy($image_raw, $full_path)){
                $cat->image = $db_path;
            } else if (\Storage::copy($image_raw, $db_path)){
                $cat->image = $db_path;
            }
        } else if (Request::hasFile('image') && empty (Request::input('image_select'))) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/categories/'.$cat->id.'/';
            $image_save = imageSave(Request::file('image'), $path, 1920, 400);
            if ($image_save) {
                $cat->image = $image_save;
            }
        } else if(!empty (Request::input('image_select'))){
            $cat->image = htmlspecialchars(Request::input('image_select'));
        }

        if (Request::hasFile('thumbnail')) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/categories/'.$cat->id.'/';
            $image_save = imageSave(Request::file('thumbnail'), $path, 300);
            if ($image_save) {
                $cat->thumbnail = $image_save;
            }
        } else if(strlen(Request::input('thumbnail_select'))){
            $cat->thumbnail = htmlspecialchars(Request::input('thumbnail_select'));
        }

        $levels = Request::input('levels');
        $cat_levels = Array();
        if(count($levels)){
            foreach($levels as $id => $state){
                $level = Level::findOrFail((int) $id);
                if ($level->project->user->id !== Auth::guard('backend')->id()) {
                    return redirect()->back()->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
                }
                array_push($cat_levels, $id);
            }
        }
        $cat->levels()->sync($cat_levels);
        $cat->save();
        if(Request::has('preview')){
            return redirect(getPreviewLink('category', $cat->id));
        } else {
            return redirect('/categories');
        }
    }

    public function update($cat_id)
    {
        $v = Validator::make(Request::all(), ['name' => 'required|max:255'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail(Session::get('selected_project'));
        $cat = Category::findOrFail($cat_id);
        if($cat->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваша категория!']);
        }
        $cat->name = htmlspecialchars(Request::input('name'));

        $cat->excerpt = htmlentities(Request::input('excerpt'));
        $cat->category_html = htmlentities(Request::input('category_html'));
        $cat->upsale_text = htmlentities(Request::input('upsale_text'));
        
        $cat->parent = (int) htmlspecialchars(Request::input('parent'));
        
        if(Request::has('sidebar')){
            $cat->sidebar = true;
        } else {
            $cat->sidebar = false;
        }
        $cat->sidebar_type = (int) Request::input('sidebar_type');
        $cat->sidebar_html = htmlentities(Request::input('sidebar_html'));

        $cat->status = htmlspecialchars(Request::input('status'));

        if(Request::has('scheduled') && !empty(Request::input('scheduled'))){
            $cat->scheduled = strtotime(htmlspecialchars(Request::input('scheduled'))) - (int) htmlspecialchars(Request::input('offset'));
            if($cat->scheduled <= 0 || $cat->scheduled > 2147483647){
                return redirect()->back()->with('popup_info', ['Ошибка', 'Вы ввели некорректную дату!']);
            }
        } else {
            $cat->scheduled = getTimePlus();
        }
        $cat->sch2num = htmlspecialchars(Request::input('sch2num'));
        $cat->sch2type = htmlspecialchars(Request::input('sch2type'));
        switch($cat->sch2type){
            case "days": $cat->sch2typename = "дней"; break;
            case "weeks": $cat->sch2typename = "недель"; break;
            case "months": $cat->sch2typename = "месяцев"; break;
            default: $cat->sch2typename = "";
        }
        if(Request::has('header_dim')){
            $cat->header_dim = true;
        } else {
            $cat->header_dim = false;
        }
        if(Request::has('upsale')){
            $cat->upsale = true;
        } else {
            $cat->upsale = false;
        }
        if(Request::has('comingsoon1') || Request::has('comingsoon2')){
            $cat->comingsoon = true;
        } else {
            $cat->comingsoon = false;
        }
        $cat->save();

        if (Request::has('image_remove')) {
            if (Storage::exists($cat->image)) {
                Storage::delete($cat->image);
            }
            $cat->image = "";
        }

        if (Request::hasFile('image') && empty (Request::input('image_select'))) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/categories/'.$cat->id.'/';
            $image_save = imageSave(Request::file('image'), $path, 1920, 400);
            if ($image_save) {
                if (Storage::exists($cat->image)) {
                    Storage::delete($cat->image);
                }
                $cat->image = $image_save;
            }
        } else if(!empty (Request::input('image_select'))){
            if (Storage::exists($cat->image)) {
                Storage::delete($cat->image);
            }
            $cat->image = htmlspecialchars(Request::input('image_select'));
        }

        if (Request::has('thumbnail_remove')) {
            if (Storage::exists($cat->thumbnail)) {
                Storage::delete($cat->thumbnail);
            }
            $cat->thumbnail = "";
        }

        if (Request::hasFile('thumbnail')) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/categories/'.$cat->id.'/';
            $image_save = imageSave(Request::file('thumbnail'), $path, 300);
            if ($image_save) {
                if (Storage::exists($cat->thumbnail)) {
                    Storage::delete($cat->thumbnail);
                }
                $cat->thumbnail = $image_save;
            }
        } else if(strlen(Request::input('thumbnail_select'))){
            if (Storage::exists($cat->thumbnail)) {
                Storage::delete($cat->thumbnail);
            }
            $cat->thumbnail = htmlspecialchars(Request::input('thumbnail_select'));
        }
        
        $levels = Request::input('levels');
        $cat_levels = Array();
        if(count($levels)){
            foreach($levels as $id => $state){
                $level = Level::findOrFail((int) $id);
                if ($level->project->user->id !== Auth::guard('backend')->id()) {
                    return redirect()->back()->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
                }
                array_push($cat_levels, $id);
            }
        }
        $cat->levels()->sync($cat_levels);
        $cat->save();
        $this->refreshOrder($project);
        if(Request::has('preview')){
            return redirect(getPreviewLink('category', $cat->id));
        } else {
            return redirect()->back()->with('popup_ok', ['Настройки категории', 'Вы успешно сохранили настройки категории!']);
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
            $cat = Category::findOrFail($id);
            if($cat->project->user->id !== Auth::guard('backend')->id()){
                return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваша категория!']);
            }
            if($action === "delete"){
                $cat_files_path = $cat->project->user->id.'/'.$cat->project->domain.'/categories/'.$cat->id.'/';
                if(Storage::exists($cat_files_path)){
                    Storage::deleteDirectory($cat_files_path);
                }
                foreach($cat->posts as $post){
                    $post_files_path = $post->category->project->user->id.'/'.$post->category->project->domain.'/posts/'.$post->id.'/';
                    if(Storage::exists($post_files_path)){
                        Storage::deleteDirectory($post_files_path);
                    }
                }
                $cat->delete();
            } else if($action === "levels"){
                $levels = Request::input('levels');
                $cat_levels = Array();
                if(count($levels)){
                    foreach($levels as $id => $state){
                        $level = Level::findOrFail((int) $id);
                        if ($level->project->user->id !== Auth::guard('backend')->id()) {
                            return redirect()->back()->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
                        }
                        array_push($cat_levels, $id);
                    }
                }
                $cat->levels()->sync($cat_levels);
                $cat->save();
            } else if($action === "status"){
                $cat->status = htmlspecialchars(Request::input('status'));
                $cat->sch2num = htmlspecialchars(Request::input('sch2num'));
                $cat->sch2type = htmlspecialchars(Request::input('sch2type'));
                switch($cat->sch2type){
                    case "days": $cat->sch2typename = "дней"; break;
                    case "weeks": $cat->sch2typename = "недель"; break;
                    case "months": $cat->sch2typename = "месяцев"; break;
                    default: $cat->sch2typename = "";
                }
                if($cat->status === "scheduled"){
                    if(!Request::has('scheduled') && empty(Request('scheduled'))){
                        return redirect()->back()->with('popup_info', ['Ошибка!', 'Вы не указали дату!']);
                    }
                    $cat->scheduled = strtotime(htmlspecialchars(Request::input('scheduled'))) - (int) htmlspecialchars(Request::input('offset'));
                    if($cat->scheduled <= 0 || $cat->scheduled > 2147483647){
                        return redirect()->back()->with('popup_info', ['Ошибка', 'Вы ввели некорректную дату!']);
                    }
                }
                if(Request::has('comingsoon1') || Request::has('comingsoon2')){
                    $cat->comingsoon = true;
                } else {
                    $cat->comingsoon = false;
                }
                $cat->save();
            }
        }
        $this->refreshOrder(Project::findOrFail(Session::get('selected_project')));
        return redirect()->back();
    }

    public function delete($cat_id)
    {
        $category = Category::findOrFail($cat_id);
        if($category->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваша категория!']);
        }
        $cat_files_path = $category->project->user->id.'/'.$category->project->domain.'/categories/'.$category->id.'/';
        if(Storage::exists($cat_files_path)){
            Storage::deleteDirectory($cat_files_path);
        }
        foreach($category->posts as $post){
            $post_files_path = $post->category->project->user->id.'/'.$post->category->project->domain.'/posts/'.$post->id.'/';
            if(Storage::exists($post_files_path)){
                Storage::deleteDirectory($post_files_path);
            }
        }
        $category->delete();
        $this->refreshOrder(Project::findOrFail(Session::get('selected_project')));
        return redirect()->back();
    }

    public function order(){
        $cats = Request::all();
        $order = 1;
        foreach ($cats['list'] as $cat_id => $parent) {
            if(!$parent || $parent === null || $parent === "null"){
                $category = Category::findOrFail($cat_id);
                $category->order = $order;
                $category->parent = -1;
                $category->save();
                $order++;
            }
        }
        foreach ($cats['list'] as $cat_id => $parent) {
            if($parent > 0){
                $category = Category::findOrFail($cat_id);
                $category->order = $order;
                $category->parent = (int) $parent;
                $category->save();
                $order++;
            }
        }
        return $cats;
    }
    
    /* -- */
    
    private function refreshOrder($project){
        $count = 1;
        foreach($project->categories()->orderBy('order', 'asc')->get() as $cat){
            $cat->order = $count;
            $cat->save();
            $count++;
        }
    }
}