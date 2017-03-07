<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Hash;
use Mail;
use Request;
use Session;
use Storage;
use Validator;

class AccountController extends Controller
{
    private $messages = [
        'email.required' => "Вы не ввели email!",
        'email.exists' => "Данного email нет в базе пользователей!",
        'password.required' => "Вы не ввели пароль!",
        'password.between' => "Пароль должен быть от :min до :max символов в длину!",
        'password_check.required' => "Вы не ввели подтверждение пароля!",
        'password_check.same' => "Пароли не совпадают!",
        'name.required' => "Вы не ввели имя!",
        'accepted.accepted' => "Вы не приняли пользовательское соглашение!",

        'subject.required' => "Вы не указали тему сообщения!",
        'subject.max' => "Максимальная длина темы - 40 символов!",
        'message.required' => "Вы не указали сообщение!",
        'message.max' => "Максимальная длина сообщения - 2048 символов!"
    ];

    public function index()
    {
        return view('backend.account.index');
    }

    public function plans(){
        if(strlen(Auth::guard('backend')->user()->partner)){
            $data['link'] = "/p/".Auth::guard('backend')->user()->partner.'/again/';
        } else {
            $data['link'] = "/account/payment/";
        }
        return view('backend.account.plans')->with('data', $data);
    }

    public function payment(){
        return view('backend.account.payment');
    }

    public function expired(){
        if(Auth::guard('backend')->user()->status){
            return redirect('/projects');
        }
        if(strlen(Auth::guard('backend')->user()->partner)){
            $data['link'] = "/p/".Auth::guard('backend')->user()->partner.'/again/';
        } else {
            $data['link'] = "/account/payment/";
        }
        return view('backend.account.expired')->with('data', $data);
    }

    public function logout()
    {
        Auth::guard('backend')->logout();
        Session::flush();
        return redirect('/');
    }

    public function faq(){
        $data['title'] = "F.A.Q";
        return view('backend.account.faq')->with('data', $data);
    }

    /* -- */

    public function update(){
        $v = Validator::make(Request::all(), [
            'name' => 'required|max:32',
            'email' => 'required|max:32',
            'password' => 'between:8,20',
            'password_check' => 'same:password',
            'phone' => 'max:20',
            'site' => 'max:40',
            'vk' => 'max:40',
            'fb' => 'max:40',
            'linkedin' => 'max:40',
            ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $user = Auth::guard('backend')->user();
        $user->name = htmlspecialchars(Request::input('name'));
        $user->email = htmlspecialchars(Request::input('email'));
        $user->phone = htmlspecialchars(Request::input('phone'));
        $user->site = htmlspecialchars(Request::input('site'));
        $user->vk = htmlspecialchars(Request::input('vk'));
        $user->fb = htmlspecialchars(Request::input('fb'));
        $user->linkedin = htmlspecialchars(Request::input('linkedin'));
        $user->save();

        if(Request::has('password') && Request::has('password_check')){
            $user->password = Hash::make(htmlspecialchars(Request::input('password')));
            $user->save();
            return redirect('/account')->with('popup_ok', ['Настройки профиля', 'Настройки профиля и новый пароль успешно сохранены!']);
        } else {
            return redirect('/account')->with('popup_ok', ['Настройки профиля', 'Настройки профиля успешно сохранены!']);
        }
    }

    public function delete(){
        $v = Validator::make(Request::all(), ['password' => 'required|between:8,20'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }
        $pass = htmlspecialchars(Request::input('password'));
        if(!Hash::check($pass, Auth::guard('backend')->user()->password)){
            return redirect('/account')->with('popup_info', ['Удаление аккаунта', 'Неверный пароль!']);
        }
        
        $user_id = Auth::guard('backend')->user()->id;
        $user_dir = '/'.$user_id.'/';

        Auth::guard('backend')->logout();

        if(Storage::exists($user_dir)){
            Storage::deleteDirectory($user_dir);
        }
        User::destroy($user_id);
        
        return redirect('/')->with('my_modal', ['Прощайте :(', 'Вы успешно удалили свой аккаунт']);
    }

    // -----------------

    public function perpage($perpage)
    {
        Session::put('perpage', $perpage);
        Session::save();
    }

    public function message(){
        $v = Validator::make(Request::all(), [
            'subject' => 'required|max:40',
            'message' => 'required|max:2048'
            ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $subject = htmlspecialchars(Request::input('subject'));
        $msg = htmlspecialchars(Request::input('message'));

        $msg .= "\r\n \r\n"
            . "-----------\r\n"
            . Auth::guard('backend')->user()->name."\r\n"
            . Auth::guard('backend')->user()->email."\r\n"
            . Auth::guard('backend')->id();

        Mail::raw($msg,
            function($message) use($subject) {
            $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
            $message->to("support@abckabinet.ru")->subject("ABC: Сообщение от пользователя - ".$subject);
        });

        return redirect()->back()->with('popup_ok', ['Спасибо!', 'Ваше сообщение успешно отправлено!']);
    }
    
    public function sort($type, $order_by, $order){
        Session::put('sort.'.$type.'.order_by', $order_by);
        Session::put('sort.'.$type.'.order', $order);
        return redirect()->back();
    }

}