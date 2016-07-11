<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\NewUser;
use App\Notification;
use App\Plan;
use App\User;
use App\UserPassword;
use Auth;
use Hash;
use Mail;
use Request;
use Session;
use Storage;
use Validator;
use function getTime;
use function getTimePlus;

class AuthController extends Controller
{

    private $messages = [
        'email.required' => "Вы не ввели email!",
        'phone.required' => "Вы не ввели номер телефона!",
        'email.exists' => "Данного email нет в базе пользователей!",
        'password.required' => "Вы не ввели пароль!",
        'password.between' => "Пароль должен быть от :min до :max символов в длину!",
        'password_check.required' => "Вы не ввели подтверждение пароля!",
        'password_check.same' => "Пароли не совпадают!",
        'name.required' => "Вы не ввели имя!",
        'accepted.accepted' => "Вы не приняли пользовательское соглашение!"
    ];

    public function register()
    {
        $v = Validator::make(Request::all(), ['name' => 'required|max:32', 'email' => 'required|max:32', 'phone' => 'required|max:20'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $name  = htmlspecialchars(Request::input('name'));
        $email = htmlspecialchars(Request::input('email'));
        $phone = htmlspecialchars(Request::input('phone'));
        $partner = Request::cookie('abc_partner');
        $utm = Session::get('utm');
        $key = "";

        if (User::where('email', $email)->count()) {
            $user = User::where('email', $email)->first();
            $key_exists = NewUser::where('user_id', $user->id)->first();
            if(!$key_exists){
                return redirect()->back()->with('my_modal', ['Внимание!', 'Данный email уже зарегистрирован, используте форму входа!']);
            } else {
                $user->name = $name;
                $user->phone = $phone;
                $user->save();
                $key = $key_exists->key;
            }
        } else {
            $plan = Plan::findOrFail(0);

            $user           = new User();
            $user->plan()->associate($plan);
            $user->name     = $name;
            $user->email    = strtolower($email);
            $user->phone    = $phone;
            $user->password = Hash::make("62256225devpassword");
            $user->expires  = getTimePlus(getTime(), "14", "days");
            $user->payment_term = 0;
            if($partner){
                $user->partner = $partner;
            }
            $utm_string = "";
            if($utm){
                foreach($utm as $k => $v){
                    $utm_string .= $k . ': ' . $v . "\r\n";
                }
            }
            $user->utm = $utm_string;

            $user->save();

            $key   = str_random(16);

            $new_user      = new NewUser();
            $new_user->key = $key;
            $new_user->user()->associate($user);
            $new_user->save();

            Storage::makeDirectory("/".$user->id."/");
            
            $not = new Notification();
            $not->name = "Добро пожаловать в ABC Кабинет!";
            $not->text = "Для того чтобы приступить к работе - создайте ваш первый проект. Если у вас возникнут трудности с сервисом, вы всегда можете обратиться в нашу службу поддержки, написав нам письмо: <a href='mailto:support@abckabinet.ru'>support@abckabinet.ru</a>";
            $not->user()->associate($user);
            $not->save();
        }

        $msg = "Здавствуйте, ".$name."!\r\n"
            ."Вы успешно зарегистрировались\r\n"
            ."в ABC Кабинет!\r\n"
            ."\r\n"
            ."Все, что осталось сделать - \r\n"
            ."создать пароль. Для этого перейдите\r\n"
            ."по ссылке:\r\n"
            .config('app.url')."/register/".$key."\r\n"
            ."\r\n"
            ."2016, ABC Кабинет\r\n";

        Mail::raw($msg,
            function($message) use($email) {
            $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
            $message->to($email)->subject("ABC Кабинет - Регистрация");
        });

        return redirect('/register/success/1');
    }

    public function newUserView($key)
    {
        $data = NewUser::find($key);
        if (!$data) {
            return redirect('/')->with('my_modal', ['Внимание!', 'Данный ключ недействителен! Если вы забыли пароль - используйте форму восстановления пароля!']);
        }
        $user = $data->user;
        $data['title'] = ' / Создание пароля';
        return view('shared.auth.newuser')->with('user', $user)->with('key', $key)->with('data', $data);
    }

    public function newUser($key)
    {
        $data = NewUser::find($key);
        if (!$data) {
            return redirect('/')->with('my_modal', ['Внимание!', 'Данный ключ недействителен! Если вы забыли пароль - используйте форму восстановления пароля!']);
        }
        $user = $data->user;

        $v = Validator::make(Request::all(),
            ['password' => 'required|between:8,20',
            'password_check' => 'required|same:password',
            'accepted' => 'accepted'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $password = htmlspecialchars(Request::input('password'));

        $user->password = Hash::make($password);
        $user->save();

        Auth::guard('backend')->login($data->user);
        $data->delete();

        return redirect('/register/success/2');
    }

    public function login()
    {
        $v = Validator::make(Request::all(), ['email' => 'required|exists:users,email', 'password' => 'required|min:8|max:20'], $this->messages);

        if ($v->fails()) {
            return redirect('/')->withErrors($v);
        }
        
        $email    = strtolower(htmlspecialchars(Request::input('email')));
        $pass     = htmlspecialchars(Request::input('password'));
        $remember = false;
        if (Request::has('remember')) {
            $remember = true;
        }

        if (Auth::guard('backend')->attempt(['email' => $email, 'password' => $pass], $remember)) {
            return redirect('/projects');
        } else {
            return redirect('/')->with('my_modal', ['Внимание!', 'Неверный пароль!']);
        }
    }

    public function loginAdmin()
    {
        $v = Validator::make(Request::all(), ['password' => 'required|min:8|max:20'], $this->messages);

        if ($v->fails()) {
            return redirect('/')->withErrors($v);
        }

        $pass     = htmlspecialchars(Request::input('password'));
        $remember = false;
        if (Request::has('remember')) {
            $remember = true;
        }

        if (Auth::guard('admin')->attempt(['id' => 1, 'password' => $pass], $remember)) {
            return redirect("http://admin.".config('app.domain').'/?sid='.Session::getId());
        } else {
            return redirect('/')->with('my_modal', ['Внимание!', 'Неверный пароль!']);
        }
    }

    public function passwordSendEmailView()
    {
        $data['title'] = ' / Восстановление пароля';
        return view('shared.auth.passwordSendEmail')->with('data', $data);
    }

    public function passwordSendEmail()
    {
        $v = Validator::make(Request::all(), ['email' => 'required|exists:users,email'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $email = htmlspecialchars(Request::input('email'));
        $user  = User::where('email', $email)->first();

        if(UserPassword::where('user_id', $user->id)->count()){
            $data = UserPassword::where('user_id', $user->id)->first();
        } else{
            $data           = new UserPassword();
            $data->key      = str_random(16);
            $data->user()->associate($user);
            $data->save();
        }

        $msg = "Здавствуйте, ".$user->name."!\r\n"
            ."Недавно вы создавали запрос\r\n"
            ."на восстановление пароля  к\r\n"
            ."ABC Кабинет!\r\n"
            ."\r\n"
            ."Чтоб приступить к созданию нового \r\n"
            ."пароля - перейдите по ссылке:\r\n"
            .config('app.url')."/login/password/".$data->key."\r\n"
            ."\r\n"
            ."После создания нового пароля\r\n"
            ."данная ссылка станет недействительна!\r\n"
            ."\r\n"
            ."2016, ABC Кабинет\r\n";

        Mail::raw($msg,
            function($message) use($email) {
            $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
            $message->to($email)->subject("ABC Кабинет - Восстановение пароля");
        });

        return redirect('/')->with('my_modal', ['Восстановление пароля', 'Ожидайте письмо на ваш email с дальнейшими инструкциями!']);
    }

    public function passwordResetView($key)
    {
        $data = UserPassword::find($key);
        if (!$data) {
            return redirect('/')->with('my_modal', ['Внимание!', 'Данный ключ недействителен! Попробуйте восстановить пароль еще раз']);
        }
        $user = $data->user;
        $data['title'] = ' / Восстановление пароля - создайте пароль';
        return view('shared.auth.passwordReset')->with('user', $user)->with('key', $key)->with('data', $data);;
    }

    public function passwordReset($key)
    {
        $data = UserPassword::find($key);
        if (!$data) {
            return redirect('/')->with('my_modal', ['Внимание!', 'Данный ключ недействителен! Попробуйте восстановить пароль еще раз']);
        }
        $user = $data->user;

        $v = Validator::make(Request::all(), [
            'password' => 'required|between:8,20',
            'password_check' => 'required|same:password'
        ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $password = htmlspecialchars(Request::input('password'));

        $user->password = Hash::make($password);
        $user->save();

        $data->delete();

        return redirect('/')->with('my_modal', ['Восстановление пароля', 'Пароль успешно обновлен! Входите :)']);
    }

    public function successView($id){
        $id = (int) $id;
        if($id !== 1 && $id !== 2 && $id !== 3 && $id !== 4 && $id !== 5){
            return redirect('/');
        }
        if($id === 1){
            $data['codes'] = ["vk2"];
            header("refresh:1;url=/?modal=success");
        } elseif($id === 2) {
            header("refresh:1;url=/projects");
        } else {
            $data['codes'] = ["vk3"];
            header("refresh:1;url=/?modal=success");
        }
        $data['title'] = ' / Регистрация успешна!';
        return view('shared.auth.success')->with('data', $data);
    }
}