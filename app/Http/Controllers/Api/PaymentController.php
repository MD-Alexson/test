<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\NewUser;
use App\Notification;
use App\Payment;
use App\Suser;
use App\SuserPassword;
use App\User;
use Hash;
use Mail;
use Request;
use Storage;

class PaymentController extends Controller
{

    public function abc() {
        $hash = Request::get('hash');

        $order_id = Request::get('id');
        $email = strtolower(Request::get('email'));
        $phone = Request::get('phone');
        $key = '2glolgim6N';
        if(Request::has('additional_field_1')){
            $partner = Request::get('additional_field_1');
        } else {
            $partner = "";
        }
        if(Request::has('additional_field_2')){
            $utm_string = Request::get('additional_field_2');
        } else {
            $utm_string = "";
        }

        $check_hash = md5($order_id . $email . $phone . $key);
        if ($hash !== $check_hash) {
            exit('Wrong hash');
        }

        if((int) Request::get('status') !== 5){
            exit();
        }

        $product_id = (int) Request::get('product_id');
        $invoice = Invoice::findOrFail($product_id);
        $plan = $invoice->plan;

        $check = User::where('email', $email)->first();
        if ($check) {
            $user = $check;
            if ($user->expires <= time()) {
                $user->expires = strtotime("+" . $invoice->term . " month");
            } else {
                $user->expires = strtotime("+" . $invoice->term . " month", $user->expires);
            }
            $user->status = 1;
            $user->payment_term = $invoice->term;
            $user->plan()->associate($plan);
            $user->save();

            $msg = "Здравствуйте, ".Request::get('first_name')."!\r\n";
            $msg.= "Вы успешно продили пользование сервисом!\r\n";
            $msg.= "\r\nСтраница входа: ".config('app.url')."/?modal=login\r\n";
            $msg.= "Забыли пароль? - ".config('app.url')."/login/password\r\n";
            $msg.= "\r\n-----------\r\n";
            $msg.= "С уважением, ABC Кабинет";

            Mail::raw($msg, function($message) use ($email) {
                $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                $message->to($email)->subject("ABC Кабинет - Операция успешна!");
            });
        } else {
            $user = new User();
            $user->name = Request::get('first_name');
            $user->email = $email;
            $user->phone = $phone;
            $user->expires = strtotime("+" . $invoice->term . " month");
            $user->password = Hash::make("62256225devpassword");
            $user->payment_term = $invoice->term;
            $user->partner = $partner;
            $user->utm = $utm_string;
            $user->plan()->associate($plan);
            $user->save();

            $key = str_random(16);

            $new_user      = new NewUser();
            $new_user->key = $key;
            $new_user->user()->associate($user);
            $new_user->save();

            Storage::makeDirectory("/".$user->id."/");

            $msg = "Здравствуйте, ".Request::get('first_name')."!\r\n";
            $msg.= "Все, что Вам осталось сделать для\r\nпользования сервисом - создать пароль:\r\n";
            $msg.= "\r\n".config('app.url')."/register/".$key."\r\n";
            $msg.= "\r\nЕсли по какой-то причине Вам не удалось\r\nсоздать пароль для входа - обязательно\r\nнапишите нам - support@abckabinet.ru\r\n";
            $msg.= "\r\nБлагодарим Вас за покупку!\r\n";
            $msg.= "\r\n-----------\r\n";
            $msg.= "С уважением, ABC Кабинет";

            Mail::raw($msg, function($message) use ($email) {
                $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                $message->to($email)->subject("ABC Кабинет - Операция успешна!");
            });

            $not = new Notification();
            $not->name = "Добро пожаловать в ABC Кабинет!";
            $not->text = "Для того чтобы приступить к работе - создайте ваш первый проект. Если у вас возникнут трудности с сервисом, вы всегда можете обратиться в нашу службу поддержки, написав нам письмо: <a href='mailto:support@abckabinet.ru'>support@abckabinet.ru</a>";
            $not->user()->associate($user);
            $not->save();
        }
    }

    public function eautopay() {
        $method = "E-Autopay";
        $product_id = Request::get('product_id');

        $payments = Payment::where('method', $method)->where('item_id', $product_id)->get();
        if (!count($payments)) {
            exit('false');
        }

        $hash = Request::get('hash');
        $order_id = Request::get('id');
        $email = strtolower(Request::get('email'));
        $phone = Request::get('phone');
        $status = (int) Request::get('status');

        if($status !== 5){
            exit('status');
        }

        foreach ($payments as $payment) {
            $key = $payment->key;

            $check_hash = md5($order_id . $email . $phone . $key);
            if ($hash !== $check_hash) {
                continue;
            }

            $level = $payment->level;
            $project = $payment->project;

            $check = $project->susers()->where('email', $email)->first();
            if (count($check)) {
                $user = $check;
                if ($user->expires <= time()) {
                    $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type);
                } else {
                    $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type, $user->expires);
                }
                if ($payment->membership) {
                    $user->expire = true;
                } else {
                    $user->expire = false;
                }
                $user->status = 1;
                $user->level()->associate($level);
                $user->project()->associate($project);
                $user->save();

                $msg = $payment->message2;
                $sub = $payment->subject2;
                if(strlen($msg) > 0 && strlen($sub) > 0){
                    $msg = str_replace("{username}", $user->name, $msg);
                    if(!empty($project->remote_domain)){
                        $msg = str_replace("{pass_link}", "http://".$project->remote_domain."/pass", $msg);
                    } else {
                        $msg = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass", $msg);
                    }
                    $msg = str_replace("{email}", $email, $msg);
                    $msg = str_replace("{project_name}", $project->name, $msg);
                    $msg = str_replace("{level_name}", $level->name, $msg);

                    $sub = str_replace("{username}", $user->name, $sub);
                    if(!empty($project->remote_domain)){
                        $sub = str_replace("{pass_link}", "http://".$project->remote_domain."/pass", $sub);
                    } else {
                        $sub = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass", $sub);
                    }
                    $sub = str_replace("{password}", $user->password_raw, $sub);
                    $sub = str_replace("{email}", $email, $sub);
                    $sub = str_replace("{project_name}", $project->name, $sub);
                    $sub = str_replace("{level_name}", $level->name, $sub);

                    Mail::raw($msg, function($message) use ($email, $project, $sub) {
                        $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                        $message->to($email)->subject($sub);
                    });
                }
            } else {
                $user = new Suser();
                $user->name = Request::get('last_name') . ' ' . Request::get('first_name') . ' ' . Request::get('middle_name');
                $user->email = $email;
                $user->phone = $phone;
                if ($payment->membership) {
                    $user->expire = true;
                }
                $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type);
                $pass = str_random(8);
                $user->password = Hash::make($pass);
                $user->password_raw = $pass;
                $user->rand = str_random(16);
                $user->level()->associate($level);
                $user->project()->associate($project);
                $user->save();

                $data           = new SuserPassword();
                $data->key      = str_random(16);
                $data->suser()->associate($user);
                $data->project()->associate($project);
                $data->save();

                $msg = $payment->message;
                $sub = $payment->subject;
                if(strlen($msg) > 0 && strlen($sub) > 0){
                    $msg = str_replace("{username}", $user->name, $msg);
                    if(!empty($project->remote_domain)){
                        $msg = str_replace("{pass_link}", "http://".$project->remote_domain."/pass/".$data->key, $msg);
                    } else {
                        $msg = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass/".$data->key, $msg);
                    }
                    $msg = str_replace("{email}", $email, $msg);
                    $msg = str_replace("{project_name}", $project->name, $msg);
                    $msg = str_replace("{level_name}", $level->name, $msg);

                    $sub = str_replace("{username}", $user->name, $sub);
                    if(!empty($project->remote_domain)){
                        $sub = str_replace("{pass_link}", "http://".$project->remote_domain."/pass/".$data->key, $sub);
                    } else {
                        $sub = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass/".$data->key, $sub);
                    }
                    $sub = str_replace("{password}", $user->password_raw, $sub);
                    $sub = str_replace("{email}", $email, $sub);
                    $sub = str_replace("{project_name}", $project->name, $sub);
                    $sub = str_replace("{level_name}", $level->name, $sub);

                    Mail::raw($msg, function($message) use ($email, $project, $sub) {
                        $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                        $message->to($email)->subject($sub);
                    });
                }
            }
        }
    }

    public function justclick() {
        $method = "Justclick";
        $product_id = Request::get('items');

        $payments = Payment::where('method', $method)->where('item_id', $product_id[0]['id'])->get();
        if (!count($payments)) {
            exit('false');
        }

        $hash = Request::get('hash');
        $order_id = Request::get('id');
        $email = strtolower(Request::get('email'));
        $phone = Request::get('phone');
        if (Request::has('paid')) {
            $paid = Request::get('paid');
        } else {
            exit('false2');
        }

        foreach ($payments as $payment) {
            $key = $payment->key;

            $check_hash = md5($order_id . $email . $paid . $key);
            if ($hash !== $check_hash) {
                continue;
            }

            $level = $payment->level;
            $project = $payment->project;

            $check = $project->susers()->where('email', $email)->first();
            if (count($check)) {
                $user = $check;
                if ($user->expires <= time()) {
                    $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type);
                } else {
                    $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type, $user->expires);
                }
                if ($payment->membership) {
                    $user->expire = true;
                } else {
                    $user->expire = false;
                }
                $user->status = 1;
                $user->level()->associate($level);
                $user->project()->associate($project);
                $user->save();

                $msg = $payment->message2;
                $sub = $payment->subject2;
                if(strlen($msg) > 0 && strlen($sub) > 0){
                    $msg = str_replace("{username}", $user->name, $msg);
                    if(!empty($project->remote_domain)){
                        $msg = str_replace("{pass_link}", "http://".$project->remote_domain."/pass", $msg);
                    } else {
                        $msg = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass", $msg);
                    }
                    $msg = str_replace("{email}", $email, $msg);
                    $msg = str_replace("{project_name}", $project->name, $msg);
                    $msg = str_replace("{level_name}", $level->name, $msg);

                    $sub = str_replace("{username}", $user->name, $sub);
                    if(!empty($project->remote_domain)){
                        $sub = str_replace("{pass_link}", "http://".$project->remote_domain."/pass", $sub);
                    } else {
                        $sub = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass", $sub);
                    }
                    $sub = str_replace("{password}", $user->password_raw, $sub);
                    $sub = str_replace("{email}", $email, $sub);
                    $sub = str_replace("{project_name}", $project->name, $sub);
                    $sub = str_replace("{level_name}", $level->name, $sub);

                    Mail::raw($msg, function($message) use ($email, $project, $sub) {
                        $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                        $message->to($email)->subject($sub);
                    });
                }
            } else {
                $user = new Suser();
                $user->name = Request::get('last_name') . ' ' . Request::get('first_name') . ' ' . Request::get('middle_name');
                $user->email = $email;
                $user->phone = $phone;
                if ($payment->membership) {
                    $user->expire = true;
                }
                $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type);
                $pass = str_random(8);
                $user->password = Hash::make($pass);
                $user->password_raw = $pass;
                $user->rand = str_random(16);
                $user->level()->associate($level);
                $user->project()->associate($project);
                $user->save();

                $data           = new SuserPassword();
                $data->key      = str_random(16);
                $data->suser()->associate($user);
                $data->project()->associate($project);
                $data->save();

                $msg = $payment->message;
                $sub = $payment->subject;
                if(strlen($msg) > 0 && strlen($sub) > 0){
                    $msg = str_replace("{username}", $user->name, $msg);
                    if(!empty($project->remote_domain)){
                        $msg = str_replace("{pass_link}", "http://".$project->remote_domain."/pass/".$data->key, $msg);
                    } else {
                        $msg = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass/".$data->key, $msg);
                    }
                    $msg = str_replace("{email}", $email, $msg);
                    $msg = str_replace("{project_name}", $project->name, $msg);
                    $msg = str_replace("{level_name}", $level->name, $msg);

                    $sub = str_replace("{username}", $user->name, $sub);
                    if(!empty($project->remote_domain)){
                        $sub = str_replace("{pass_link}", "http://".$project->remote_domain."/pass/".$data->key, $sub);
                    } else {
                        $sub = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass/".$data->key, $sub);
                    }
                    $sub = str_replace("{password}", $user->password_raw, $sub);
                    $sub = str_replace("{email}", $email, $sub);
                    $sub = str_replace("{project_name}", $project->name, $sub);
                    $sub = str_replace("{level_name}", $level->name, $sub);

                    Mail::raw($msg, function($message) use ($email, $project, $sub) {
                        $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                        $message->to($email)->subject($sub);
                    });
                }
            }
        }
    }
    
    public function fondy(){
        $method = "Fondy";
        $merch_id = Request::get('merchant_id');
        $product_id = Request::get('product_id');
        $payment = Payment::where('method', $method)->where('merch_id', $merch_id)->where('item_id', $product_id)->first();
        if (!count($payment)) {
            return redirect('/api/payment/fail');
        }

        $hash = Request::get('signature');
        $hash_check = explode("|", Request::get('response_signature_string'));
        $key = $payment->key;
        $hash_check[0] = $key;
        $hash_check_string = join("|", $hash_check);
        $check_hash = sha1($hash_check_string);
        if ($hash !== $check_hash) {
            return redirect('/api/payment/fail');
        }
        
        $email = strtolower(Request::get('sender_email'));
        if(!strlen($email)){
            return redirect('/api/payment/fail');
        }

        if(Request::get('order_status') !== "approved"){
            return redirect('/api/payment/fail');
        }
        
        $level = $payment->level;
        $project = $payment->project;

        $check = $project->susers()->where('email', $email)->first();
        if (count($check)) {
            $user = $check;
            if ($user->expires <= time()) {
                $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type);
            } else {
                $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type, $user->expires);
            }
            if ($payment->membership) {
                $user->expire = true;
            } else {
                $user->expire = false;
            }
            $user->status = 1;
            $user->level()->associate($level);
            $user->project()->associate($project);
            $user->save();

            $msg = $payment->message2;
            $sub = $payment->subject2;
            if(strlen($msg) > 0 && strlen($sub) > 0){
                $msg = str_replace("{username}", $user->name, $msg);
                if(!empty($project->remote_domain)){
                    $msg = str_replace("{pass_link}", "http://".$project->remote_domain."/pass", $msg);
                } else {
                    $msg = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass", $msg);
                }
                $msg = str_replace("{email}", $email, $msg);
                $msg = str_replace("{project_name}", $project->name, $msg);
                $msg = str_replace("{level_name}", $level->name, $msg);

                $sub = str_replace("{username}", $user->name, $sub);
                if(!empty($project->remote_domain)){
                    $sub = str_replace("{pass_link}", "http://".$project->remote_domain."/pass", $sub);
                } else {
                    $sub = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass", $sub);
                }
                $sub = str_replace("{password}", $user->password_raw, $sub);
                $sub = str_replace("{email}", $email, $sub);
                $sub = str_replace("{project_name}", $project->name, $sub);
                $sub = str_replace("{level_name}", $level->name, $sub);

                Mail::raw($msg, function($message) use ($email, $project, $sub) {
                    $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                    $message->to($email)->subject($sub);
                });
            }
        } else {
            $user = new Suser();
            $user->name = "Пользователь";
            $user->email = $email;
            if ($payment->membership) {
                $user->expire = true;
            }
            $user->expires = strtotime("+" . $payment->membership_num . " " . $payment->membership_type);
            $pass = str_random(8);
            $user->password = Hash::make($pass);
            $user->password_raw = $pass;
            $user->rand = str_random(16);
            $user->level()->associate($level);
            $user->project()->associate($project);
            $user->save();

            $data           = new SuserPassword();
            $data->key      = str_random(16);
            $data->suser()->associate($user);
            $data->project()->associate($project);
            $data->save();

            $msg = $payment->message;
            $sub = $payment->subject;
            if(strlen($msg) > 0 && strlen($sub) > 0){
                $msg = str_replace("{username}", $user->name, $msg);
                if(!empty($project->remote_domain)){
                    $msg = str_replace("{pass_link}", "http://".$project->remote_domain."/pass/".$data->key, $msg);
                } else {
                    $msg = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass/".$data->key, $msg);
                }
                $msg = str_replace("{email}", $email, $msg);
                $msg = str_replace("{project_name}", $project->name, $msg);
                $msg = str_replace("{level_name}", $level->name, $msg);

                $sub = str_replace("{username}", $user->name, $sub);
                if(!empty($project->remote_domain)){
                    $sub = str_replace("{pass_link}", "http://".$project->remote_domain."/pass/".$data->key, $sub);
                } else {
                    $sub = str_replace("{pass_link}", "http://".$project->domain.".".config('app.domain')."/pass/".$data->key, $sub);
                }
                $sub = str_replace("{password}", $user->password_raw, $sub);
                $sub = str_replace("{email}", $email, $sub);
                $sub = str_replace("{project_name}", $project->name, $sub);
                $sub = str_replace("{level_name}", $level->name, $sub);

                Mail::raw($msg, function($message) use ($email, $project, $sub) {
                    $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                    $message->to($email)->subject($sub);
                });
            }
        }
        return redirect('/api/payment/success');
    }
}