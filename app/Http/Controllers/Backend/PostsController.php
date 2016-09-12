<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Level;
use App\Post;
use App\Project;
use Auth;
use Request;
use Session;
use Storage;
use Validator;
use function fileDelete;
use function fileSave;
use function getTimePlus;
use function imageSave;
use function videoDelete;
use function videoSave;

class PostsController extends Controller
{
    // VALIDATOR

    private $messages = [
        "name.required" => "Вы не указали название публикации!",
        "action.required" => "Вы не указали действие!",
        "ids.required" => "Вы не указали публикации!"
    ];

    // VIEWS

    public function index()
    {
        $project               = Project::findOrFail(Session::get('selected_project'));
        $data['title']         = "Публикации / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js']  = [asset("assets/js/datetime.js")];
        $posts            = $project->posts()->orderBy(\Session::get('sort.posts_all.order_by'), \Session::get('sort.posts_all.order'))->paginate((int) Session::get('perpage'));
        return view('backend.posts.index')->with('data', $data)->with('project', $project)->with('posts', $posts);
    }

    public function indexByCategory($cat_id)
    {
        $project               = Project::findOrFail(Session::get('selected_project'));
        $category = Category::findOrFail($cat_id);
        if($category->project->user->id !== Auth::guard('backend')->id()){
            return redirect('/categories')->with('popup_info', ['Ошибка', 'Это не ваша категория!']);
        }
        $data['title']         = "Публикации / ".$category->name." / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js']  = [asset("assets/js/datetime.js")];
        $posts            = $category->posts()->orderBy(\Session::get('sort.posts.order_by'), \Session::get('sort.posts.order'))->paginate((int) Session::get('perpage'));
        return view('backend.posts.index_by_category')->with('data', $data)->with('project', $project)->with('posts', $posts)->with('category', $category);
    }

    public function indexByLevel($level_id)
    {
        $project               = Project::findOrFail(Session::get('selected_project'));
        $level = Level::findOrFail($level_id);
        if ($level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
        }
        $data['title']         = "Публикации / ".$level->name." / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js']  = [asset("assets/js/datetime.js")];
        $posts            = $level->posts()->orderBy(\Session::get('sort.posts_all.order_by'), \Session::get('sort.posts_all.order'))->paginate((int) Session::get('perpage'));
        return view('backend.posts.index_by_level')->with('data', $data)->with('project', $project)->with('posts', $posts)->with('level', $level);
    }

    public function add($with = false, $with_id = false)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        if(!$project->categories->count()){
            return redirect('/categories')->with('popup_info', ['Нет категорий', 'Для создания публикаций необходима хотя бы одна категория! Создайте первую']);
        }
        $data['title']        = "Добавить публикацию / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [
            asset("assets/js/datetime.js"),
            asset('assets/js/ckeditor/ckeditor.js'),
            asset('assets/js/ckeditor/adapters/jquery.js'),
            asset('assets/js/ckeditor/init.js'),
            "https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"
            ];
        if($with && $with_id){
            if($with === 'level'){
                $level = \App\Level::find($with_id);
                if(!$level || $level->project->domain !== $project->domain){
                    exit('Неправильный уровень доступа');
                }
            } else if($with === 'category'){
                $cat = \App\Category::find($with_id);
                if(!$cat || $cat->project->domain !== $project->domain){
                    exit('Неправильная категория');
                }
            } else {
                exit('Неправильная ссылка');
            }
        }
        return view('backend.posts.add')->with('data', $data)->with('project', $project)->with('with', $with)->with('with_id', $with_id);
    }

    public function edit($post_id)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $post = Post::findOrFail($post_id);
        if($post->category->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваша публикация!']);
        }
        $data['title']        = "Редактировать публикацию / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [
            asset("assets/js/datetime.js"),
            asset('assets/js/ckeditor/ckeditor.js'),
            asset('assets/js/ckeditor/adapters/jquery.js'),
            asset('assets/js/ckeditor/init.js'),
            "https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"
            ];
        return view('backend.posts.edit')->with('data', $data)->with('post', $post)->with('project', $project);
    }

    public function orderView($cat_id){
        $project               = Project::findOrFail(Session::get('selected_project'));
        $category = Category::findOrFail($cat_id);
        $data['title']         = $category->name ." / Порядок записей / ".$project->name;
        $data['assets']['js']  = [asset("assets/js/jqui_sortable.min.js"), asset("assets/js/sortable.min.js")];
        $posts            = $category->posts()->orderBy('order', 'asc')->get();
        return view('backend.posts.order')->with('data', $data)->with('project', $project)->with('category', $category)->with('posts', $posts);
    }

    public function orderAllView(){
        $project               = Project::findOrFail(Session::get('selected_project'));
        $data['title']         = "Порядок всех записей / ".$project->name;
        $data['assets']['js']  = [asset("assets/js/jqui_sortable.min.js"), asset("assets/js/sortable.min.js")];
        $posts            = $project->posts()->orderBy('order_all', 'asc')->get();
        return view('backend.posts.order_all')->with('data', $data)->with('project', $project)->with('posts', $posts);
    }

    // HANDLERS

    public function store(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255'
        ], $this->messages);
        
        $arr = [];

        $project = Project::findOrFail(Session::get('selected_project'));

        $post = new Post;
        $post->name = htmlspecialchars(Request::input('name'));

        $post->excerpt = htmlentities(Request::input('excerpt'));
        $post->post_html = htmlentities(Request::input('post_html'));
        $post->embed = htmlentities(Request::input('embed'));
        
        if(Request::has('upsale')){
            $post->upsale = true;
        }
        $post->upsale_text = htmlentities(Request::input('upsale_text'));
        
        if(Request::has('sidebar')){
            $post->sidebar = true;
        }
        $post->sidebar_type = (int) Request::input('sidebar_type');
        $post->sidebar_html = htmlentities(Request::input('sidebar_html'));

        $post->status = htmlspecialchars(Request::input('status'));
        
        if(Request::input('stripe') && Request::input('stripe') !== 0 && Request::input('stripe') !== "0"){
            $post->stripe = htmlspecialchars(Request::input('stripe'));
        } else {
            $post->stripe = null;
        }

        if(Request::has('scheduled') && !empty(Request::input('scheduled'))){
            $post->scheduled = strtotime(htmlspecialchars(Request::input('scheduled'))) - (int) htmlspecialchars(Request::input('offset'));
            if($post->scheduled <= 0 || $post->scheduled > 2147483647){
                $arr['error'] = "Вы ввели некорректную дату!";
                echo json_encode($arr);
                exit();
            }
        } else {
            $post->scheduled = getTimePlus();
        }
        $post->sch2num = htmlspecialchars(Request::input('sch2num'));
        $post->sch2type = htmlspecialchars(Request::input('sch2type'));
        switch($post->sch2type){
            case "days": $post->sch2typename = "дней"; break;
            case "weeks": $post->sch2typename = "недель"; break;
            case "months": $post->sch2typename = "месяцев"; break;
            default: $post->sch2typename = "";
        }
        if(Request::has('header_dim')){
            $post->header_dim = true;
        }
        if(Request::has('comments_enabled')){
            $post->comments_enabled = true;
        }
        if(Request::has('comments_moderate')){
            $post->comments_moderate = true;
        }
        if(Request::has('homework_enabled')){
            $post->homework_enabled = true;
        }
        if(Request::has('homework_check')){
            $post->homework_check = true;
        }
        $post->homework = htmlentities(Request::input('homework'));
        if(Request::has('comingsoon1') || Request::has('comingsoon2')){
            $post->comingsoon = true;
        }
        
        $category = Category::findOrFail((int) Request::input('category_id'));
        if($category->project->user->id !== Auth::guard('backend')->id()){
            $arr['error'] = "Вы выбрали не вашу категорию!";
            echo json_encode($arr);
            exit();
        }
        $post->category()->associate($category);

        $post->order = (int) $category->posts()->max('order') + 1;
        $post->order_all = (int) $project->posts()->max('order_all') + 1;
        
        $post->save();
        
        if(!empty (Request::input('parent_image'))){
            $image_raw = htmlspecialchars(Request::input('parent_image'));
            $image_name = explode('/', $image_raw);
            $image_name = $image_name[count($image_name) - 1];
            $folder_path = "/".Auth::guard('backend')->id().'/'.$project->domain.'/posts/'.$post->id.'/';
            if (!file_exists($folder_path)) {
                Storage::makeDirectory($folder_path, 0775, true, true);
            }
            $db_path = $folder_path.$image_name;
            $full_path = storage_path("app".$db_path);
            if(filter_var($image_raw, FILTER_VALIDATE_URL) && copy($image_raw, $full_path)){
                $post->image = $db_path;
            } else if (\Storage::copy($image_raw, $db_path)){
                $post->image = $db_path;
            }
        } else if (Request::hasFile('image') && empty (Request::input('image_select'))) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/posts/'.$post->id.'/';
            $image_save = imageSave(Request::file('image'), $path, 1920, 400);
            if ($image_save) {
                $post->image = $image_save;
            }
        } else if(!empty (Request::input('image_select'))){
            $post->image = htmlspecialchars(Request::input('image_select'));
        }

        if (Request::hasFile('thumbnail')) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/posts/'.$post->id.'/';
            $image_save = imageSave(Request::file('thumbnail'), $path, 300);
            if ($image_save) {
                $post->thumbnail = $image_save;
            }
        } else if(strlen(Request::input('thumbnail_select'))){
            $post->thumbnail = htmlspecialchars(Request::input('thumbnail_select'));
        }

        $levels = Request::input('levels');
        $post_levels = Array();
        if(count($levels)){
            foreach($levels as $id => $state){
                $level = Level::findOrFail((int) $id);
                if ($level->project->user->id !== Auth::guard('backend')->id()) {
                    $arr['error'] = "Вы выбрали не ваш уровень!";
                    echo json_encode($arr);
                    exit();
                }
                array_push($post_levels, $id);
            }
        }
        $post->levels()->sync($post_levels);

        $homeworks = Request::input('homeworks');
        $post_homeworks = Array();
        if(count($homeworks)){
            foreach($homeworks as $id => $state){
                $homework = Post::findOrFail((int) $id);
                if ($homework->category->project->user->id !== Auth::guard('backend')->id()) {
                    $arr['error'] = "Вы выбрали не вашу публикацию!";
                    echo json_encode($arr);
                    exit();
                }
                array_push($post_homeworks, $id);
            }
        }
        $post->requiredPosts()->sync($post_homeworks);
        $post->save();

        if(Request::hasFile('files')){
            $files = Request::file('files');
            foreach($files as $file){
                fileSave($file, $post);
            }
        }
        if(Request::hasFile('videos')){
            $videos = Request::file('videos');
            foreach($videos as $video){
                videoSave($video, $post);
            }
        }
        $arr['success'] = true;
        echo json_encode($arr);
        exit();
    }

    public function update(\Illuminate\Http\Request $request, $post_id)
    {
        $this->validate($request, [
            'name' => 'required|max:255'
        ], $this->messages);
        
        $arr = [];
        
        $project = Project::findOrFail(Session::get('selected_project'));

        $post = Post::findOrFail($post_id);
        if($post->category->project->user->id !== Auth::guard('backend')->id()){
            $arr['error'] = "Это не ваша публикация!";
            echo json_encode($arr);
            exit();
        }
        
        $post->name = htmlspecialchars(Request::input('name'));

        $post->excerpt = htmlentities(Request::input('excerpt'));
        $post->post_html = htmlentities(Request::input('post_html'));
        $post->embed = htmlentities(Request::input('embed'));
        
        if(Request::has('upsale')){
            $post->upsale = true;
        } else {
            $post->upsale = false;
        }
        $post->upsale_text = htmlentities(Request::input('upsale_text'));
        
        if(Request::has('sidebar')){
            $post->sidebar = true;
        } else {
            $post->sidebar = false;
        }
        $post->sidebar_type = (int) Request::input('sidebar_type');
        $post->sidebar_html = htmlentities(Request::input('sidebar_html'));

        $post->status = htmlspecialchars(Request::input('status'));
        
        if(Request::input('stripe') && Request::input('stripe') !== 0 && Request::input('stripe') !== "0"){
            $post->stripe = htmlspecialchars(Request::input('stripe'));
        } else {
            $post->stripe = null;
        }
        
        if(Request::has('scheduled') && !empty(Request::input('scheduled'))){
            $post->scheduled = strtotime(htmlspecialchars(Request::input('scheduled'))) - (int) htmlspecialchars(Request::input('offset'));
            if($post->scheduled <= 0 || $post->scheduled > 2147483647){
                $arr['error'] = "Вы ввели некорректную дату!";
                echo json_encode($arr);
                exit();
            }
        } else {
            $post->scheduled = getTimePlus();
        }
        
        $post->sch2num = htmlspecialchars(Request::input('sch2num'));
        $post->sch2type = htmlspecialchars(Request::input('sch2type'));
        switch($post->sch2type){
            case "days": $post->sch2typename = "дней"; break;
            case "weeks": $post->sch2typename = "недель"; break;
            case "months": $post->sch2typename = "месяцев"; break;
            default: $post->sch2typename = "";
        }
        if(Request::has('header_dim')){
            $post->header_dim = true;
        } else {
            $post->header_dim = false;
        }
        if(Request::has('comments_enabled')){
            $post->comments_enabled = true;
        } else {
            $post->comments_enabled = false;
        }
        if(Request::has('comments_moderate')){
            $post->comments_moderate = true;
        } else {
            $post->comments_moderate = false;
        }
        if(Request::has('homework_enabled')){
            $post->homework_enabled = true;
        } else {
            $post->homework_enabled = false;
        }
        if(Request::has('homework_check')){
            $post->homework_check = true;
        } else {
            $post->homework_check = false;
        }
        $post->homework = htmlentities(Request::input('homework'));
        if(Request::has('comingsoon1') || Request::has('comingsoon2')){
            $post->comingsoon = true;
        } else {
            $post->comingsoon = false;
        }

        $category = Category::findOrFail((int) Request::input('category_id'));
        if($category->project->user->id !== Auth::guard('backend')->id()){
            $arr['error'] = "Вы выбрали не вашу категорию!";
            echo json_encode($arr);
            exit();
        }
        $post->category()->associate($category);

        $post->save();
        

        if (Request::has('image_remove')) {
            if (Storage::exists($post->image)) {
                Storage::delete($post->image);
            }
            $post->image = "";
        }

        if (Request::hasFile('image') && empty (Request::input('image_select'))) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/posts/'.$post->id.'/';
            $image_save = imageSave(Request::file('image'), $path, 1920, 400);
            if ($image_save) {
                if (Storage::exists($post->image)) {
                    Storage::delete($post->image);
                }
                $post->image = $image_save;
            }
        } else if(!empty (Request::input('image_select'))){
            if (Storage::exists($post->image)) {
                Storage::delete($post->image);
            }
            $post->image = htmlspecialchars(Request::input('image_select'));
        }

        if (Request::has('thumbnail_remove')) {
            if (Storage::exists($post->thumbnail)) {
                Storage::delete($post->thumbnail);
            }
            $post->thumbnail = "";
        }

        if (Request::hasFile('thumbnail')) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/posts/'.$post->id.'/';
            $image_save = imageSave(Request::file('thumbnail'), $path, 300);
            if ($image_save) {
                if (Storage::exists($post->thumbnail)) {
                    Storage::delete($post->thumbnail);
                }
                $post->thumbnail = $image_save;
            }
        } else if(strlen(Request::input('thumbnail_select'))){
            if (Storage::exists($post->thumbnail)) {
                Storage::delete($post->thumbnail);
            }
            $post->thumbnail = htmlspecialchars(Request::input('thumbnail_select'));
        }

        $levels = Request::input('levels');
        $post_levels = Array();
        if(count($levels)){
            foreach($levels as $id => $state){
                $level = Level::findOrFail((int) $id);
                if ($level->project->user->id !== Auth::guard('backend')->id()) {
                    $arr['error'] = "Вы выбрали не ваш уровень!";
                    echo json_encode($arr);
                    exit();
                }
                array_push($post_levels, $id);
            }
        }
        $post->levels()->sync($post_levels);

        $homeworks = Request::input('homeworks');
        $post_homeworks = Array();
        if(count($homeworks)){
            foreach($homeworks as $id => $state){
                $homework = Post::findOrFail((int) $id);
                if ($homework->category->project->user->id !== Auth::guard('backend')->id()) {
                    $arr['error'] = "Вы выбрали не вашу публикацию!";
                    echo json_encode($arr);
                    exit();
                }
                array_push($post_homeworks, $id);
            }
        }
        $post->requiredPosts()->sync($post_homeworks);
        $post->save();
        $this->refreshOrder($project);

        if(Request::hasFile('files')){
            $files = Request::file('files');
            foreach($files as $file){
                fileSave($file, $post);
            }
        }
        $files_delete = Request::input('files_delete');
        if(!empty($files_delete)){
            foreach($files_delete as $file_id){
                fileDelete($file_id);
            }
        }
        
        if(Request::hasFile('videos')){
            $videos = Request::file('videos');
            foreach($videos as $video){
                videoSave($video, $post);
            }
        }
        $videos_delete = Request::input('videos_delete');
        if(!empty($videos_delete)){
            foreach($videos_delete as $video_id){
                videoDelete($video_id);
            }
        }

        // -- -
        $video_delete = Request::input('video_delete');
        if(!empty($video_delete)){
            videoDelete((int) $video_delete);
        }
        if(Request::hasFile('video')){
            $video = Request::file('video');
            videoSave($video, $post);
        }
        
        $arr['success'] = true;
        echo json_encode($arr);
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
            $post = Post::findOrFail($id);
            if($post->category->project->user->id !== Auth::guard('backend')->id()){
                return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваша публикация!']);
            }
            if($action === "delete"){
                $post_files_path = $post->category->project->user->id.'/'.$post->category->project->domain.'/posts/'.$post->id.'/';
                if(Storage::exists($post_files_path)){
                    Storage::deleteDirectory($post_files_path);
                }
                $post->delete();
            } else if($action === "levels"){
                $levels = Request::input('levels');
                $post_levels = Array();
                if(count($levels)){
                    foreach($levels as $id => $state){
                        $level = Level::findOrFail((int) $id);
                        if ($level->project->user->id !== Auth::guard('backend')->id()) {
                            return redirect()->back()->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
                        }
                        array_push($post_levels, $id);
                    }
                }
                $post->levels()->sync($post_levels);
                $post->save();
            } else if($action === "category"){
                $category = Category::findOrFail((int)Request::input('category_id'));
                if($category->project->user->id !== Auth::guard('backend')->id()){
                    return redirect()->back()->with('popup_info', ['Ошибка', 'Вы выбрали не вашу категорию!']);
                }
                $post->category()->associate($category);
                $post->save();
            } else if($action === "comments"){
                if(Request::has('comments_enabled')){
                    $post->comments_enabled = true;
                } else {
                    $post->comments_enabled = false;
                }
                if(Request::has('comments_moderate')){
                    $post->comments_moderate = true;
                } else {
                    $post->comments_moderate = false;
                }
                $post->save();
            } else if($action === "status"){
                $post->status = htmlspecialchars(Request::input('status'));
                $post->sch2num = htmlspecialchars(Request::input('sch2num'));
                $post->sch2type = htmlspecialchars(Request::input('sch2type'));
                switch($post->sch2type){
                    case "days": $post->sch2typename = "дней"; break;
                    case "weeks": $post->sch2typename = "недель"; break;
                    case "months": $post->sch2typename = "месяцев"; break;
                    default: $post->sch2typename = "";
                }
                if($post->status === "scheduled"){
                    if(!Request::has('scheduled') && empty(Request('scheduled'))){
                        return redirect()->back()->with('popup_info', ['Ошибка!', 'Вы не указали дату!']);
                    }
                    $post->scheduled = strtotime(htmlspecialchars(Request::input('scheduled'))) - (int) htmlspecialchars(Request::input('offset'));
                    if($post->scheduled <= 0 || $post->scheduled > 2147483647){
                        return redirect()->back()->with('popup_info', ['Ошибка', 'Вы ввели некорректную дату!']);
                    }
                }
                if(Request::has('comingsoon1') || Request::has('comingsoon2')){
                    $post->comingsoon = true;
                } else {
                    $post->comingsoon = false;
                }
                $post->save();
            }
        }
        $this->refreshOrder(Project::findOrFail(Session::get('selected_project')));
        return redirect()->back();
    }

    public function delete($post_id)
    {
        $post = Post::findOrFail($post_id);
        if($post->category->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваша публикация!']);
        }
        $post_files_path = $post->category->project->user->id.'/'.$post->category->project->domain.'/posts/'.$post->id.'/';
        if(Storage::exists($post_files_path)){
            Storage::deleteDirectory($post_files_path);
        }
        $post->delete();
        $this->refreshOrder(Project::findOrFail(Session::get('selected_project')));
        return redirect()->back();
    }

    public function order($cat_id){
        $posts = Request::all();
        $order = 1;
        foreach ($posts['list'] as $post_id => $parent) {
            $post = Post::findOrFail($post_id);
            $post->order = $order;
            $post->save();
            $order++;
        }
    }

    public function orderAll(){
        $posts = Request::all();
        $order = 1;
        foreach ($posts['list'] as $post_id => $parent) {
            $post = Post::findOrFail($post_id);
            $post->order_all = $order;
            $post->save();
            $order++;
        }
    }
    
    /* -- */
    
    private function refreshOrder($project){
        $count_all = 1;
        foreach($project->posts()->orderBy('order_all', 'asc')->get() as $post){
            $post->order_all = $count_all;
            $post->save();
            $count_all++;
        }
        foreach($project->categories as $cat){
            $count = 1;
            foreach($cat->posts()->orderBy('order', 'asc')->get() as $post_cat){
                $post_cat->order = $count;
                $post_cat->save();
                $count++;
            }
        }
    }
}