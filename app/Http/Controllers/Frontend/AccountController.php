<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Level;
use App\Project;
use App\Suser;
use App\SuserPassword;
use Auth;
use Hash;
use Mail;
use Request;
use Session;
use Validator;

class AccountController extends Controller
{
    private $messages = [
        'email.required' => "Вы не ввели email!",
        'email.exists' => "Данного email нет в базе пользователей!",
        'email.max' => "Максимальная длина email - 32 символа",
        'password.required' => "Вы не ввели пароль!",
        'password.between' => "Пароль должен быть от :min до :max символов в длину!",
        'password_check.required' => "Вы не ввели подтверждение пароля!",
        'password_check.same' => "Пароли не совпадают!",
        'name.required' => "Вы не ввели имя!",
        'name.max' => "Максимальная длина имени - 32 символа",
        'accepted.accepted' => "Вы не приняли пользовательское соглашение!",
        'phone.max' => 'Максимальная длина телефона - 20 символов',

        'subject.required' => "Вы не указали тему сообщения!",
        'subject.max' => "Максимальная длина темы - 40 символов!",
        'message.required' => "Вы не указали сообщение!",
        'message.max' => "Максимальная длина сообщения - 2048 символов!"
    ];

    public function loginView($domain){
        $project = Project::findOrFail($domain);
        $data['title'] = 'Вход / '. $project->name;
        return view('frontend_old.auth.login')->with('project', $project)->with('data', $data);
    }

    public function login($domain){
        $project = Project::findOrFail($domain);
        $email = strtolower(htmlspecialchars(Request::input('email')));
        $pass = htmlspecialchars(Request::input('password'));

        $user = $project->susers->where('email', $email)->first();
        if(!$user){
            return redirect('/login')->with('popup_info', ['Ошибка', 'Данный email не зарегистрирован в проекте!']);
        }
        if(Hash::check($pass, $user->password)){
            Auth::guard('frontend')->logout();
            Auth::guard('frontend')->login($user);
            return redirect('/');
        } else {
            return redirect('/login')->with('popup_info', ['Ошибка', 'Неправильный пароль!']);
        }
    }

    public function registerView($domain){
        $project = Project::findOrFail($domain);
        $data['title'] = 'Регистрация / '. $project->name;
        return view('$sub.auth.register')->with('project', $project)->with('data', $data);
    }

    public function register($domain){
        $v = Validator::make(Request::all(), ['name' => 'required|max:40', 'email' => 'required|max:40', 'password' => 'required|between:8,20', 'password_check' => 'required|same:password'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $name  = htmlspecialchars(Request::input('name'));
        $email = htmlspecialchars(Request::input('email'));
        $password = htmlspecialchars(Request::input('password'));

        $project = Project::findOrFail($domain);
        if($project->susers()->where('email', $email)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данный email уже зарегистрирован в проекте!']);
        }

        $level = $project->levels()->where('open', true)->first();

        $user = new Suser;
        $user->name = $name;
        $user->email = $email;
        $user->password_raw = $password;
        $user->password = Hash::make($password);
        $user->rand = str_random(8);
        $user->project()->associate($project);
        $user->level()->associate($level);
        $user->save();

        Auth::guard('frontend')->login($user);
        return redirect('/');
    }

    public function passwordView($domain){
        $project = Project::findOrFail($domain);
        $data['title'] = 'Восстановление пароля / '. $project->name;
        return view('$sub.auth.pass')->with('project', $project)->with('data', $data);
    }

    public function password($domain){
        $v = Validator::make(Request::all(), ['email' => 'required|max:40'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail($domain);
        $email = htmlspecialchars(Request::input('email'));
        $user = $project->susers()->where('email', $email)->first();
        if(!$user){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данный email не зарегистрирован в проекте!']);
        }
        if(SuserPassword::where('suser_id', $user->id)->count()){
            $data = SuserPassword::where('suser_id', $user->id)->first();
        } else{
            $data           = new SuserPassword();
            $data->key      = str_random(16);
            $data->suser()->associate($user);
            $data->project()->associate($project);
            $data->save();
        }

        $msg = "Здавствуйте, ".$user->name."!\r\n"
            ."Недавно вы создавали запрос\r\n"
            ."на восстановление пароля  к\r\n"
            .$project->name."\r\n"
            ."\r\n"
            ."Чтоб приступить к созданию нового \r\n"
            ."пароля - перейдите по ссылке:\r\n"
            ."http://".$project->domain.".".config('app.domain')."/pass/".$data->key."\r\n"
            ."\r\n"
            ."После создания нового пароля\r\n"
            ."данная ссылка станет недействительна!";

        Mail::raw($msg,
            function($message) use($project, $email) {
            $message->from('hostmaster@abckabinet.ru', $project->name);
            $message->to($email)->subject($project->name." - Восстановение пароля");
        });

        return redirect('/pass/sent');
    }

    public function passwordSentView($domain){
        $project = Project::findOrFail($domain);
        $data['title'] = 'Восстановление пароля / '. $project->name;
        return view('$sub.auth.pass_sent')->with('project', $project)->with('data', $data);
    }

    public function passwordChangeView($domain, $key){
        $project = Project::findOrFail($domain);
        $passdata = SuserPassword::find($key);
        if (!$passdata) {
            return redirect('/pass')->with('popup_info', ['Внимание!', 'Данный ключ недействителен! Попробуйте восстановить пароль еще раз']);
        }
        $data['title'] = 'Восстановление пароля / '. $project->name;
        return view('$sub.auth.pass_change')->with('project', $project)->with('data', $data)->with('passdata', $passdata);
    }

    public function passwordChange($domain, $key){
        $project = Project::findOrFail($domain);
        $data = SuserPassword::find($key);
        if (!$data) {
            return redirect('/pass')->with('popup_info', ['Внимание!', 'Данный ключ недействителен! Попробуйте восстановить пароль еще раз']);
        }
        $user = $data->suser;

        $v = Validator::make(Request::all(), [
            'password' => 'required|between:8,20',
            'password_check' => 'required|same:password'
        ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $user->password = Hash::make(htmlspecialchars(Request::input('password')));
        $user->password_raw = htmlspecialchars(Request::input('password'));
        $user->save();
        $data->delete();

        Auth::guard('frontend')->login($user);

        return redirect('/')->with('popup_info', ['Восстановление пароля', 'Пароль успешно обновлен!']);
    }

    public function mainPage($domain){
        $project = Project::findOrFail($domain);
        if($project->dashboard_type){
            return redirect('/posts');
        } else {
            return redirect('/categories');
        }
    }

    public function logout($domain)
    {
        if(Session::get('guard') === 'frontend'){
            Auth::guard('frontend')->logout();
            return redirect('/login');
        } else {
            \Session::flush();
            return redirect(config('app.url').'/projects');
        }
    }

    public function select($domain, $level_id){
        $project = Project::findOrFail($domain);
        $level = Level::findOrFail($level_id);
        if(Auth::guard('backend')->check() && Auth::guard('backend')->id() === $project->user->id){
            Session::put('level_id', $level->id);
            Session::save();
        }
    }

    public function expiredView($domain){
        $project = Project::findOrFail($domain);
        $data['title'] = 'Ваш аккаунт деактивирован / '. $project->name;
        if(Session::has('guard') && Auth::guard(Session::get('guard'))->user()->status){
            return redirect('/');
        }
        return view('$sub.auth.expired')->with('project', $project)->with('data', $data);
    }

    public function edit($domain){
        $project = Project::findOrFail($domain);
        $data['header_bg'] = config("app.url")."/assets/images/thumbnails/headers/0.jpg";
        if(!empty($project->image)){
            $data['header_bg'] = pathTo($project->image, "imagepath");
        }
        $data['title'] = 'Редактировать аккаунт / '. $project->name;
        $guard = Session::get('guard');
        if($guard !== 'frontend'){
            return redirect('/');
        }
        $user = Auth::guard('frontend')->user();
        $menu = Request::input('allowed')['categories'];
        return view('$sub.account.edit')->with('project', $project)->with('data', $data)->with('user', $user)->with('menu', $menu);
    }

    public function update($domain){
        $v = Validator::make(Request::all(),
                [
                'name' => 'required|max:32',
                'email' => 'required|max:32',
                'phone' => 'max:20',
                'password' => 'between:8,20',
                'password_check' => 'same:password'
                ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }
        $project = Project::findOrFail($domain);

        $guard = Session::get('guard');
        if($guard !== 'frontend'){
            return redirect('/');
        }
        $user = Auth::guard('frontend')->user();

        if(!$project->susers()->where('id', $user->id)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Что?']);
        }

        $old_email = $user->email;

        $user->name = htmlspecialchars(Request::input('name'));
        $user->email = strtolower(htmlspecialchars(Request::input('email')));
        $user->phone = htmlspecialchars(Request::input('phone'));
        if(!empty(Request::input('password')) && !empty(Request::input('password_check'))){
            $user->password = Hash::make(htmlspecialchars(Request::input('password')));
            $user->password_raw = htmlspecialchars(Request::input('password'));
        }
        
        if($project->susers()->where('email', $user->email)->where('email', '!=', $old_email)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данный email уже зарегистрирован в данном проекте!']);
        }

        $user->save();
        return redirect('/account')->with('popup_info', ['Редактировать аккаунт', 'Настройки аккаунта сохранены успешно!']);
    }
    
    public function comments($domain){
        $project = Project::findOrFail($domain);
        $data['header_bg'] = config("app.url")."/assets/images/thumbnails/headers/0.jpg";
        if(!empty($project->image)){
            $data['header_bg'] = pathTo($project->image, "imagepath");
        }
        $data['title'] = 'Мои комментарии / '. $project->name;
        $guard = Session::get('guard');
        if($guard !== 'frontend'){
            return redirect('/');
        }
        $user = Auth::guard('frontend')->user();
        $menu = Request::input('allowed')['categories'];
        return view('$sub.account.comments')->with('project', $project)->with('data', $data)->with('user', $user)->with('menu', $menu);
    }
    
    public function commentDestroy($domain, $comment_id){
        $project = Project::findOrFail($domain);
        $comment = \App\Comment::findOrFail($comment_id);
        if($comment->commentable->id !== \Auth::guard('frontend')->id()){
            exit('Не ваш комментарий! Как вы сюда попали?');
        } elseif ($comment->project->domain !== $domain){
            exit('Комментарий не из этого проекта!');
        }
        $comment->delete();
        return redirect()->back();
    }
    
    public function homeworks($domain){
        $project = Project::findOrFail($domain);
        $data['header_bg'] = config("app.url")."/assets/images/thumbnails/headers/0.jpg";
        if(!empty($project->image)){
            $data['header_bg'] = pathTo($project->image, "imagepath");
        }
        $data['title'] = 'Мои домашние задания / '. $project->name;
        $guard = Session::get('guard');
        if($guard !== 'frontend'){
            return redirect('/');
        }
        $user = Auth::guard('frontend')->user();
        $menu = Request::input('allowed')['categories'];
        return view('$sub.account.homeworks')->with('project', $project)->with('data', $data)->with('user', $user)->with('menu', $menu);
    }

}