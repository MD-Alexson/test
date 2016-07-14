<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Level;
use App\Payment;
use App\Project;
use App\Webinar;
use Auth;
use Request;
use Session;
use Storage;
use Validator;

class PaymentsController extends Controller
{
    // VALIDATOR

    private $messages = [
        "item_id.required" => "Вы не указали ID товара!",
        "item_id.max" => "Максимальная длина ID - 40 символов!",
        "key.required" => "Вы не указали секретный ключ!",
        "key.max" => "Максимальная длина ключа - 128 символов!!",
        "action.required" => "Не указано действие!",
        "ids.required" => "Не указаны категории!"
    ];

    // VIEWS

    public function index()
    {
        $project               = Project::findOrFail(Session::get('selected_project'));
        $data['title']         = "Настройки оплат / ".$project->name;
        $payments            = $project->payments()->orderBy('id', 'desc')->get();
        return view('backend.payments.index')->with('data', $data)->with('project', $project)->with('payments', $payments);
    }

    public function add()
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        if(!$project->levels->count()){
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Необходимо создать хотя бы один уровень доступа!']);
        }
        $data['title']        = "Добавить настройки оплаты / ".$project->name;
        return view('backend.payments.add')->with('data', $data)->with('project', $project);
    }

    public function edit($payment_id)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $payment = Payment::findOrFail($payment_id);
        if($payment->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Nope!']);
        }
        $data['title']        = "Редактировать настройки оплаты / ".$project->name;
        return view('backend.payments.edit')->with('data', $data)->with('payment', $payment)->with('project', $project);
    }

    // HANDLERS

    public function store()
    {
        Request::flash();
        $v = Validator::make(Request::all(), ['item_id' => 'required|max:40', 'key' => 'required|max:128'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail(Session::get('selected_project'));
        if(!$project->levels->count()){
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Необходимо создать хотя бы один уровень доступа!']);
        }

        $pay = new Payment();
        $pay->item_id = htmlspecialchars(Request::input('item_id'));
        $pay->key = htmlspecialchars(Request::input('key'));
        $pay->method = htmlspecialchars(Request::input('method'));

        if(Payment::where('project_domain', $project->domain)->where('method', $pay->method)->where('item_id', $pay->key)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Настройка оплаты данного товара уже добавлена в данный проект!']);
        }

        $level = Level::findOrFail((int) Request::input('level_id'));
        if($level->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш уровень доступа!']);
        }
        $pay->level()->associate($level);

        if(Request::has('membership')){
            $pay->membership = true;
        } else {
            $pay->membership = false;
        }

        $pay->membership_num = (int) Request::input('membership_num');
        $pay->membership_type = htmlspecialchars(Request::input('membership_type'));

        $pay->subject = htmlspecialchars(Request::input('subject'));
        $pay->message = htmlspecialchars(Request::input('message'));
        $pay->subject2 = htmlspecialchars(Request::input('subject2'));
        $pay->message2 = htmlspecialchars(Request::input('message2'));
        
        $pay->project()->associate($project);
        $pay->save();

        return redirect('/payments');
    }

    public function update($payment_id)
    {
        $v = Validator::make(Request::all(), ['item_id' => 'required|max:40', 'key' => 'required|max:128'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail(Session::get('selected_project'));
        if(!$project->levels->count()){
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Необходимо создать хотя бы один уровень доступа!']);
        }

        $pay = Payment::findOrFail($payment_id);
        if($pay->project->user->id !== Auth::guard('backend')->id()){
            return redirect('/payments')->with('popup_info', ['Ошибка', 'Nope!']);
        }
        $old_item_id = $pay->item_id;
        $pay->item_id = htmlspecialchars(Request::input('item_id'));
        $pay->key = htmlspecialchars(Request::input('key'));
        $pay->method = htmlspecialchars(Request::input('method'));

        if(Payment::where('project_domain', $project->domain)->where('method', $pay->method)->where('item_id', $pay->key)->where('item_id', '!='. $old_item_id)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Настройка оплаты данного товара уже добавлена в данный проект!']);
        }

        $level = Level::findOrFail((int) Request::input('level_id'));
        if($level->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш уровень доступа!']);
        }
        $pay->level()->associate($level);

        if(Request::has('membership')){
            $pay->membership = true;
        } else {
            $pay->membership = false;
        }

        $pay->membership_num = (int) Request::input('membership_num');
        $pay->membership_type = htmlspecialchars(Request::input('membership_type'));

        $pay->subject = htmlspecialchars(Request::input('subject'));
        $pay->message = htmlspecialchars(Request::input('message'));
        $pay->subject2 = htmlspecialchars(Request::input('subject2'));
        $pay->message2 = htmlspecialchars(Request::input('message2'));

        $pay->project()->associate($project);
        $pay->save();
        return redirect()->back()->with('popup_ok', ['Настройки оплаты', 'Вы успешно сохранили настройки оплаты!']);
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
            $pay = Payment::findOrFail($id);
            if($pay->project->user->id !== Auth::guard('backend')->id()){
                return redirect()->back()->with('popup_info', ['Ошибка', 'Nope!']);
            }
            if($action === "delete"){
                $pay->delete();
            }
        }
        return redirect()->back();
    }

    public function delete($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        if($payment->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Nope!']);
        }
        $payment->delete();
        return redirect()->back();
    }
}