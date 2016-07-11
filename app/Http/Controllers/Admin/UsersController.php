<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Plan;
use App\User;
use Auth;
use Hash;
use Request;
use Session;
use Storage;
use Validator;
use function getDatetime;
use function getTimePlus;

class UsersController extends Controller
{

    private $messages = [
        "name.required" => "Вы не ввели имя!",
        "name.max" => "Максимальная длина имени - 32 символа!",
        "email.required" => "Вы не ввели email!",
        "email.unique" => "Данный email уже зарегистрирован!",
        "email.max" => "Максимальная длина email - 32 символа!",
        "phone.max" => "Максимальная длина номера телефона - 20 символов!",
        "password.required" => "Вы не ввели пароль!",
        "password.between" => "Пароль должен быть от 8 до 20 символов!",
        "action.required" => "Не указано действие!",
        "ids.required" => "Не указаны пользователи!",
        "file.required" => "Вы не прикрепили CSV файл импорта!",
        "file.mimes" => "Файл импорт обязан быть корректным CSV файлом!",
        "query.required" => "Вы не указали поисковый запрос!",
        "query.max" => "Максимальная длина запроса - 40 символов!"
    ];
    
    // VIEWS

    public function index()
    {
        $data['title'] = "Пользователи";
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        
        $users = User::select('*');
        if(Session::get('users_filter_created_at') !== -1 && Session::get('users_filter_created_at') !== "") {
            $created_at = (int) Session::get('users_filter_created_at');
            if($created_at){
                $past = getDatetime(strtotime("-".$created_at." day"));
                $users = $users->where('created_at', '>', $past);
            } elseif(Session::get('users_filter_created_at_from') !== "" && Session::get('users_filter_created_at_to') !== "") {
                $users = $users->whereBetween('created_at', array(getDatetime(Session::get('users_filter_created_at_from')), getDatetime(Session::get('users_filter_created_at_to'))));
            }
        }
        if(Session::get('users_filter_plan_id') !== -1 && Session::get('users_filter_plan_id') !== "") {
            $users = $users->where('plan_id', (int) Session::get('users_filter_plan_id'));
        }
        if(Session::get('users_filter_payment_term') !== -1 && Session::get('users_filter_payment_term') !== "") {
            $users = $users->where('payment_term', (int) Session::get('users_filter_payment_term'));
        }
        if(Session::get('users_filter_status') !== -1 && Session::get('users_filter_status') !== "") {
            $users = $users->where('status', (int) Session::get('users_filter_status'));
        }
        $data['count'] = $users->count();
        $users = $users->orderBy(Session::get('users_order_by'), Session::get('users_order'))->paginate((int) Session::get('perpage'));
        return view('admin.users.index')->with('users', $users)->with('data', $data);
    }

    public function add()
    {
        $data['title']        = "Добавить пользователя";
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        return view('admin.users.add')->with('data', $data)->with('data', $data);
    }

    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        $data['title']        = "Редактировать пользователя";
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        return view('admin.users.edit')->with('data', $data)->with('user', $user);
    }

    // HANDLERS

    public function view($user_id){
        $user = User::findOrFail($user_id);
        Auth::guard('backend')->logout();
        Auth::guard('backend')->login($user);
        return redirect(url(config("app.url")."/projects/?sid=".Session::getId()));
    }

    public function store()
    {
        Request::flash();
        $v = Validator::make(Request::all(),
                [
                'name' => 'required|max:32',
                'email' => 'required|max:32|unique:users',
                'phone' => 'max:20',
                'password' => 'required|between:8,20'
                ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $plan = Plan::findOrFail(Request::input('plan_id'));

        $user = new User();
        $user->name = htmlspecialchars(Request::input('name'));
        $user->email = strtolower(htmlspecialchars(Request::input('email')));
        $user->phone = htmlspecialchars(Request::input('phone'));
        $user->password = Hash::make(htmlspecialchars(Request::input('password')));
        $user->status = (int) htmlspecialchars(Request::input('status'));
        if(Request::has('expires') && !empty(Request::input('expires'))){
            $user->expires = strtotime(htmlspecialchars(Request::input('expires'))) - (int) htmlspecialchars(Request::input('offset'));
        } else {
            $user->expires = getTimePlus();
        }
        $user->plan()->associate($plan);
        $user->save();
        Storage::makeDirectory('/'.$user->id.'/');
        return redirect('/users');
    }

    public function update($user_id)
    {
        $v = Validator::make(Request::all(),
                [
                'name' => 'required|max:32',
                'email' => 'required|max:32',
                'phone' => 'max:20',
                'password' => 'between:8,20'
                ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $plan = Plan::findOrFail(Request::input('plan_id'));
        
        $user = User::findOrFail($user_id);
        $old_email = $user->email;
        $user->name = htmlspecialchars(Request::input('name'));
        $user->email = strtolower(htmlspecialchars(Request::input('email')));
        if(User::where('email', $user->email)->where('email', '!=', $old_email)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данный email уже зарегистрирован!']);
        }
        $user->phone = htmlspecialchars(Request::input('phone'));
        if(!empty(Request::input('password'))){
            $user->password = Hash::make(htmlspecialchars(Request::input('password')));
        }
        $user->status = (int) htmlspecialchars(Request::input('status'));
        if(!empty(Request::input('expires'))){
            $user->expires = strtotime(htmlspecialchars(Request::input('expires'))) - (int) htmlspecialchars(Request::input('offset'));
        }
        $user->plan()->associate($plan);
        $user->save();
        return redirect()->back()->with('popup_ok', ['Редактировать пользователя', 'Настройки пользователя сохранены успешно!']);

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
            $user = User::findOrFail($id);
            if($action === "delete"){
                foreach($user->comments as $comment){
                    $comment->delete();
                }
                $user_dir = '/'.$user->id.'/';
                if(Storage::exists($user_dir)){
                    Storage::deleteDirectory($user_dir);
                }
                $user->delete();
            } elseif($action === "plan"){
                $plan = Plan::findOrFail(Request::input('plan_id'));
                $user->plan()->associate($plan);
                $user->save();
            } elseif($action === "status"){
                $user->status = (int) htmlspecialchars(Request::input('status'));
                $user->save();
            } elseif($action === "expires"){
                if(Request::has('expires') && Request::has('offset') && !empty(Request::input('expires')) && !empty(Request::input('offset'))){
                    $user->expires = strtotime(htmlspecialchars(Request::input('expires'))) - (int) htmlspecialchars(Request::input('offset'));
                    $user->save();
                }
            }
        }
        return redirect()->back();
    }

    public function filters(){
        Session::put('users_filter_created_at', (int) Request::input('created_at'));
        if(!empty(Request::input('created_at_from'))){
            Session::put('users_filter_created_at_from', strtotime(Request::input('created_at_from')) - (int) htmlspecialchars(Request::input('offset')));
        } else {
            Session::put('users_filter_created_at_from', "");
        }
        if(!empty(Request::input('created_at_to'))){
            Session::put('users_filter_created_at_to', strtotime(Request::input('created_at_to')) - (int) htmlspecialchars(Request::input('offset')));
        } else {
            Session::put('users_filter_created_at_to', "");
        }
        Session::put('users_filter_plan_id', (int) Request::input('plan_id'));
        Session::put('users_filter_payment_term', (int) Request::input('payment_term'));
        Session::put('users_filter_status', (int) Request::input('status'));
        Session::save();
        return redirect('/users');
    }

    public function filtersReset(){
        Session::put('users_filter_created_at', -1);
        Session::put('users_filter_created_at_from', "");
        Session::put('users_filter_created_at_to', "");
        Session::put('users_filter_plan_id', -1);
        Session::put('users_filter_payment_term', -1);
        Session::put('users_filter_status', -1);
        Session::save();
        return redirect('/users');
    }

    public function delete($user_id)
    {
        $user = User::findOrFail($user_id);
        foreach($user->comments as $comment){
            $comment->delete();
        }
        $user_dir = '/'.$user_id.'/';
        if(Storage::exists($user_dir)){
            Storage::deleteDirectory($user_dir);
        }
        $user->delete();
        return redirect()->back();
    }

    public function search(){
        $v = Validator::make(Request::all(), ['query' => 'required|max:40'], $this->messages);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $query = htmlspecialchars(Request::input('query'));

        $search = User::whereRaw("to_tsvector(users.name) @@ plainto_tsquery('" . $query . "')")->orWhereRaw("to_tsvector(users.email) @@ plainto_tsquery('" . $query . "')");
        $data['count'] = $search->count();
        $data['query'] = $query;
        $users = $search->paginate((int) Session::get('perpage'));

        $data['title'] = "Пользователи / Поиск";
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        return view("admin.users.search")->with('data', $data)->with('users', $users);
    }

    public function sort($order_by, $order){
        Session::put('users_order_by', $order_by);
        Session::put('users_order', $order);
        Session::save();
        return redirect('/users');
    }
}