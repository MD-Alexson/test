<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Level;
use App\Project;
use Auth;
use Hash;
use Request;
use Session;
use Storage;
use Validator;
use function imageSave;

class ProjectsController extends Controller
{
    private $messages = [
        'domain.unique' => "К сожалению данный домен уже занят",
        'domain.regex' => "Неправильный формат домена",
        'remote_domain.unique' => "Данный удаленный домен уже использован в одном из проектов",
        'remote_domain.regex' => "Неправильный формат удаленного домена",
        'name.required' => "Вы не ввели название проекта!",
        'domain.required' => "Вы не указали желаемый домен!",
        'password.required' => "Вы не ввели пароль!"
    ];

    private $domains = ['admin', 'k17', 'k18', 'k19', 'k20', 'k21', 'k22', 'k23', 'k24', 'k25', 'blog', 'info', 'partners', 'dmitriy', 'maletskyi', 'kovpak', 'dima', 'support', 'www', 'faq'];

    // VIEWS

    public function index()
    {
        return view('backend.projects.index');
    }

    public function dashboard()
    {
        $project = Project::findOrFail(Session::get('selected_project'));
        $data['title'] = $project->name;
        $data['assets']['js'] = ['https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.10/clipboard.min.js'];
        return view('backend.projects.dashboard')->with('data', $data)->with('project', $project);
    }

    public function add()
    {
        $data['title']        = "Добавить проект";
        $data['assets']['js'] = [
            asset('assets/js/ckeditor/ckeditor.js'),
            asset('assets/js/ckeditor/adapters/jquery.js'),
            asset('assets/js/ckeditor/init.js')];
        return view('backend.projects.add')->with('data', $data);
    }

    public function edit()
    {

        $project = Project::findOrFail(Session::get('selected_project'));
        $data['title']        = $project->name." / Редактировать";
        $data['assets']['js'] = [
            asset('assets/js/ckeditor/ckeditor.js'),
            asset('assets/js/ckeditor/adapters/jquery.js'),
            asset('assets/js/ckeditor/init.js')];
        Session::put('selected_project', $project->domain);
        Session::save();
        return view('backend.projects.edit')->with('data', $data)->with('project',
                $project);
    }

    // HANDLERS

    public function store()
    {
        Request::flash();
        $v = Validator::make(Request::all(),
                [
                'domain' => 'min:1|max:40|unique:projects,domain|regex:/^[A-Z0-9]+$/i',
                'remote_domain' => 'min:1|max:40|unique:projects,remote_domain|regex:/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}(:[0-9]{1,5})?(\/.*)?$/i',
                'name' => 'required|max:255',
                'header_text' => 'max:255',
                ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project                = new Project();

        $project->domain        = strtolower(htmlspecialchars(Request::input('domain')));
        if(in_array($project->domain, $this->domains)){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данные домен зарезервирован в системе. Пожалуйста, выберите другой.']);
        }
        $project->remote_domain = strtolower(htmlspecialchars(Request::input('remote_domain')));
        $project->remote_domain = preg_replace('#^https?://#', '', rtrim($project->remote_domain,'/'));

        if(empty($project->domain) && empty($project->remote_domain)){
            return redirect()->back()->with('popup_info', ['Ошибка', '???']);
        }

        if(empty($project->domain) && !empty($project->remote_domain)){
            $project->domain = strtr($project->remote_domain, array('.' => '', ',' => ''));
        }

        $project->name          = htmlspecialchars(Request::input('name'));
        $project->header_text   = htmlspecialchars(Request::input('header_text'));
        $project->vk            = htmlspecialchars(Request::input('vk'));
        $project->fb            = htmlspecialchars(Request::input('fb'));
        $project->tw            = htmlspecialchars(Request::input('tw'));
        $project->yt            = htmlspecialchars(Request::input('yt'));
        $project->insta         = htmlspecialchars(Request::input('insta'));
        $project->blog          = htmlspecialchars(Request::input('blog'));

        $project->login_html           = htmlentities(Request::input('login_html'));
        $project->deactivated_html     = htmlentities(Request::input('deactivated_html'));
        $project->dashboard_html       = htmlentities(Request::input('dashboard_html'));
        $project->custom_copyright     = htmlspecialchars(Request::input('custom_copyright'));
        $project->sidebar_html         = htmlentities(Request::input('sidebar_html'));
        $project->head_end_user_code   = htmlentities(Request::input('head_end_user_code'));
        $project->body_start_user_code = htmlentities(Request::input('body_start_user_code'));

        $project->dashboard_type = Request::input('dashboard_type');

        if (Request::has('header_dim')) {
            $project->header_dim = true;
        } else {
            $project->header_dim = false;
        }
        if (Request::has('disable_copyright')) {
            $project->disable_copyright = true;
        } else {
            $project->disable_copyright = false;
        }
        if (Request::has('sidebar')) {
            $project->sidebar = true;
        } else {
            $project->sidebar = false;
        }

        Storage::makeDirectory("/".Auth::guard('backend')->user()->id."/".$project->domain."/");
        Storage::makeDirectory("/".Auth::guard('backend')->user()->id."/".$project->domain."/project/");
        Storage::makeDirectory("/".Auth::guard('backend')->user()->id."/".$project->domain."/categories/");
        Storage::makeDirectory("/".Auth::guard('backend')->user()->id."/".$project->domain."/posts/");

        if (Request::hasFile('image') && empty (Request::input('image_select'))) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/project/';
            $image_save = imageSave(Request::file('image'), $path, 1920, 400);
            if ($image_save) {
                $project->image = $image_save;
            }
        } else if(!empty (Request::input('image_select'))){
            $project->image = htmlspecialchars(Request::input('image_select'));
        }

        $project->user()->associate(Auth::guard('backend')->user());
        $project->save();

        Session::put('selected_project', $project->domain);
        Session::save();

        $level = new Level();
        $level->name = "По-умолчанию";
        $level->hidden = true;
        $level->project()->associate($project);
        $level->save();

        $cat = new Category();
        $cat->name = "Категория по-умолчанию";
        $cat->project()->associate($project);
        $cat->sidebar = $project->sidebar;
        $cat->sidebar_type = 0;
        $cat->header_dim = $project->header_dim;
        if(!empty ($project->image)){
            $image_raw = htmlspecialchars($project->image);
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
        }
        $cat->save();

        $cat->levels()->sync([$level->id]);
        if(Request::has('preview')){
            return redirect(getPreviewLink('project', $project->domain));
        } else {
            return redirect('/projects')->with('popup_ok', ['Создание проекта', 'Вы успешно создали проект!']);
        }
    }

    public function update()
    {
        $project = Project::findOrFail(Session::get('selected_project'));
        $v = Validator::make(Request::all(),
                [
                'remote_domain' => 'max:255|unique:projects,remote_domain,'.$project->domain.',domain|regex:/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}(:[0-9]{1,5})?(\/.*)?$/i',
                'name' => 'required|max:255',
                'header_text' => 'max:255',
                ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project->remote_domain = strtolower(htmlspecialchars(Request::input('remote_domain')));
        $project->remote_domain = preg_replace('#^https?://#', '', rtrim($project->remote_domain,'/'));
        $project->name          = htmlspecialchars(Request::input('name'));
        $project->header_text   = htmlspecialchars(Request::input('header_text'));
        $project->vk            = htmlspecialchars(Request::input('vk'));
        $project->fb            = htmlspecialchars(Request::input('fb'));
        $project->tw            = htmlspecialchars(Request::input('tw'));
        $project->yt            = htmlspecialchars(Request::input('yt'));
        $project->insta         = htmlspecialchars(Request::input('insta'));
        $project->blog          = htmlspecialchars(Request::input('blog'));

        $project->login_html           = htmlentities(Request::input('login_html'));
        $project->deactivated_html     = htmlentities(Request::input('deactivated_html'));
        $project->dashboard_html       = htmlentities(Request::input('dashboard_html'));
        $project->custom_copyright     = htmlspecialchars(Request::input('custom_copyright'));
        $project->sidebar_html         = htmlentities(Request::input('sidebar_html'));
        $project->head_end_user_code   = htmlentities(Request::input('head_end_user_code'));
        $project->body_start_user_code = htmlentities(Request::input('body_start_user_code'));

        $project->dashboard_type = Request::input('dashboard_type');

        if (Request::has('header_dim')) {
            $project->header_dim = true;
        } else {
            $project->header_dim = false;
        }
        if (Request::has('disable_copyright')) {
            $project->disable_copyright = true;
        } else {
            $project->disable_copyright = false;
        }
        if (Request::has('sidebar')) {
            $project->sidebar = true;
        } else {
            $project->sidebar = false;
        }

        if (Request::has('image_remove')) {
            if (Storage::exists($project->image)) {
                Storage::delete($project->image);
            }
            $project->image = "";
        }

        if (Request::hasFile('image') && empty (Request::input('image_select'))) {
            $path       = "/".Auth::guard('backend')->id().'/'.$project->domain.'/project/';
            $image_save = imageSave(Request::file('image'), $path, 1920, 400);
            if ($image_save) {
                if (Storage::exists($project->image)) {
                    Storage::delete($project->image);
                }
                $project->image = $image_save;
            }
        } else if(!empty (Request::input('image_select'))){
            if (Storage::exists($project->image)) {
                Storage::delete($project->image);
            }
            $project->image = htmlspecialchars(Request::input('image_select'));
        }

        $project->save();
        
        return redirect('/settings')->with('popup_ok', ['Редактирование проекта', 'Настройки проекта успешно сохранены!']);
    }

    public function delete()
    {
        $domain = Session::get('selected_project');
        $v = Validator::make(Request::all(),
                ['password' => 'required|between:8,20'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }
        $pass = htmlspecialchars(Request::input('password'));
        if (!Hash::check($pass, Auth::guard('backend')->user()->password)) {
            return redirect('/settings')->with('popup_info',
                    ['Удаление проекта', 'Неверный пароль!']);
        }

        $user_id     = Auth::guard('backend')->id();
        $project_dir = '/'.$user_id.'/'.$domain.'/';

        if (Storage::exists($project_dir)) {
            Storage::deleteDirectory($project_dir);
        }
        Project::destroy($domain);
        if (Session::has('selected_project') && Session::get('selected_project') === $domain) {
            Session::forget('selected_project');
        }

        return redirect('/projects');
    }

    // AJAX

    public function select($domain, $redirect = false)
    {
	Session::forget('selected_project');
        Session::put('selected_project', $domain);
	Session::save();
        if ($redirect) {
            return redirect('/'.$redirect);
        }
    }
}
