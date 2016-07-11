<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Mail;
use Request;
use Session;
use Validator;

class SharedController extends Controller
{

    private $messages = [
        'email.required' => "Вы не ввели email!",
        'email.max' => "Максимальная длина email - 40 символов!",
        'name.required' => "Вы не ввели имя!",
        'name.max' => "Максимальная длина имени - 32 символа!",
        'message.required' => "Вы не указали сообщение!",
        'message.max' => "Максимальная длина сообщения - 1024 символов!"
    ];

    public function index()
    {
        $this->catchUTM();
        $data['title'] = "";
        $data['codes'] = ['vk1', 'fb', 'chat'];
        return view('shared.landing')->with('data', $data);
    }

    public function contacts()
    {
        $this->catchUTM();
        $data['title'] = "/ Контакты";
        $data['codes'] = ['vk1', 'fb', 'chat'];
        return view('shared.contacts')->with('data', $data);
    }

    public function plans()
    {
        $this->catchUTM();
        $data['title'] = " / Тарифы";
        $data['codes'] = ['vk1', 'fb', 'chat'];
        return view('shared.plans')->with('data', $data);
    }

    public function payment()
    {
        $this->catchUTM();
        $data['title'] = " / Оплата";
        $data['codes'] = ['vk1', 'fb', 'chat'];

        $utm = Session::get('utm');
        $data['utm_string'] = "";
        if($utm){
            foreach($utm as $k => $v){
                $data['utm_string'] .= $k . ': ' . $v . "\r\n";
            }
        }
        return view('shared.payment')->with('data', $data);
    }

    public function terms()
    {
        $this->catchUTM();
        $data['title'] = " / Пользовательское соглашение";
        $data['codes'] = ['vk1', 'fb', 'chat'];
        return view('shared.terms')->with('data', $data);
    }

    /* -- */

    public function send(){
        $v = Validator::make(Request::all(), [
            'name' => 'required|max:32',
            'email' => 'required|max:40',
            'message' => 'required|max:1024'
            ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $name = htmlspecialchars(Request::input('name'));
        $email = htmlspecialchars(Request::input('email'));
        $msg = htmlspecialchars(Request::input('message'));

        $msg .= "\r\n \r\n"
            . "-----------\r\n"
            . $name."\r\n"
            . $email."\r\n";

        Mail::raw($msg, function($message) {
            $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
            $message->to("support@abckabinet.ru")->subject("ABC: Сообщение со страницы контактов");
        });

        return redirect('/contacts')->with('my_modal', ['Спасибо!', 'Ваше сообщение успешно отправлено!']);
    }

    /* -- */

    private function catchUTM(){
        if(Request::has('utm_source')){
            Session::put('utm.source', Request::input('utm_source'));
        }
        if(Request::has('utm_medium')){
            Session::put('utm.medium', Request::input('utm_medium'));
        }
        if(Request::has('utm_content')){
            Session::put('utm.content', Request::input('utm_content'));
        }
        if(Request::has('utm_campaign')){
            Session::put('utm.campaign', Request::input('utm_campaign'));
        }
    }
}