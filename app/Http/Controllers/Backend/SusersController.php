<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Level;
use App\Project;
use App\Suser;
use App\SuserPassword;
use Auth;
use Hash;
use Request;
use Session;
use Validator;
use Excel;
use Mail;

class SusersController extends Controller
{
    // VALIDATOR

    private $messages = [
        "name.required" => "Вы не ввели имя!",
        "name.max" => "Максимальная длина имени - 32 символа!",
        "email.required" => "Вы не ввели email!",
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
        $project       = Project::findOrFail(Session::get('selected_project'));
        $data['title'] = "Пользователи / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        $users = $project->susers()->orderBy(\Session::get('sort.users.order_by'), \Session::get('sort.users.order'))->orderBy('id', 'desc')->paginate((int) Session::get('perpage'));
        return view('backend.susers.index')->with('data', $data)->with('project', $project)->with('users', $users);
    }

    public function indexByLevel($level_id){
        $project       = Project::findOrFail(Session::get('selected_project'));
        $level = Level::findOrFail($level_id);
        if ($level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
        }
        $data['title'] = "Пользователи / ".$level->name." / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        $users = $project->susers()->where('level_id', $level->id)->orderBy(\Session::get('sort.users.order_by'), \Session::get('sort.users.order'))->orderBy('id', 'desc')->paginate((int) Session::get('perpage'));
        return view('backend.susers.index_by_level')->with('data', $data)->with('project', $project)->with('level', $level)->with('users', $users);
    }

    public function add()
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        if(!$project->levels->count()){
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Необходимо создать хотя бы один уровень доступа!']);
        }
        $data['title']        = "Добавить пользователя / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        return view('backend.susers.add')->with('data', $data)->with('data', $data)->with('project', $project);
    }

    public function edit($user_id)
    {
        $project       = Project::findOrFail(Session::get('selected_project'));
        $user = Suser::findOrFail($user_id);
        if($user->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш пользователь!']);
        }
        $data['title']        = "Редактировать пользователя / ".$project->name;
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        return view('backend.susers.edit')->with('data', $data)->with('project', $project)->with('user', $user);
    }

    public function importView(){
        $project       = Project::findOrFail(Session::get('selected_project'));
        if(!$project->levels->count()){
            return redirect('/levels')->with('popup_info', ['Ошибка', 'Необходимо создать хотя бы один уровень доступа!']);
        }
        $data['assets']['css'] = [asset("assets/css/datetime.css")];
        $data['assets']['js'] = [asset("assets/js/datetime.js")];
        $data['title'] = "Пользователи / Импорт / ".$project->name;
        return view('backend.susers.import')->with('data', $data)->with('project', $project);
    }

    public function exportView(){
        $project       = Project::findOrFail(Session::get('selected_project'));
        $data['title'] = "Пользователи / Экспорт / ".$project->name;
        return view('backend.susers.export')->with('data', $data)->with('project', $project);
    }

    // HANDLERS

    public function store()
    {
        Request::flash();
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

        $project = Project::findOrFail(Session::get('selected_project'));
        $level = Level::findOrFail(Request::input('level_id'));

        if ($level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect()->back()->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
        }
        
        if(!empty(Request::input('password'))){
            $pass = htmlspecialchars(Request::input('password'));
        } else {
            $pass = str_random(8);
        }

        $user = new Suser();
        $user->name = htmlspecialchars(Request::input('name'));
        $user->email = strtolower(htmlspecialchars(Request::input('email')));
        $user->phone = htmlspecialchars(Request::input('phone'));
        $user->password = Hash::make($pass);
        $user->password_raw = $pass;
        $user->status = (int) htmlspecialchars(Request::input('status'));
        if(Request::has('expire')){
            $user->expire = true;
        }
        if(Request::has('expires') && !empty(Request::input('expires'))){
            $user->expires = strtotime(htmlspecialchars(Request::input('expires'))) - (int) htmlspecialchars(Request::input('offset'));
        } else {
            $user->expires = getTimePlus();
        }
        $user->rand = str_random(8);
        $user->project()->associate($project);
        $user->level()->associate($level);

        if(Suser::where('project_domain', $project->domain)->where('email', $user->email)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данный email уже зарегистрирован в данном проекте!']);
        }

        $user->save();
        if(Request::has('send_data')){
            $this->sendData($project, $user->id);
            return redirect('/users/'.$user->id.'/edit')->with('popup_ok', ['Добавление пользователя', 'Настройки пользователя сохранены успешно! Вы успешно отправили данные доступа пользователю!']);
        } else {
            return redirect('/users/'.$user->id.'/edit')->with('popup_ok', ['Добавление пользователя', 'Настройки пользователя сохранены успешно!']);
        }
    }

    public function update($user_id)
    {
        $v = Validator::make(Request::all(),
                [
                'name' => 'required|max:32',
                'email' => 'required|max:32',
                'phone' => 'max:20',
                'password' => 'required|between:8,20'
                ], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }
        $project = Project::findOrFail(Session::get('selected_project'));
        $level = Level::findOrFail(Request::input('level_id'));

        if ($level->project->user->id !== Auth::guard('backend')->id()) {
            return redirect()->back()->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
        }

        $user = Suser::findOrFail($user_id);
        if($user->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш пользователь']);
        }

        $old_email = $user->email;

        $user->name = htmlspecialchars(Request::input('name'));
        $user->email = strtolower(htmlspecialchars(Request::input('email')));
        $user->phone = htmlspecialchars(Request::input('phone'));
        if(Request::input('password') !== "OLD PASSWORD"){
            $user->password = Hash::make(htmlspecialchars(Request::input('password')));
            $user->password_raw = htmlspecialchars(Request::input('password'));
        }
        $user->status = (int) htmlspecialchars(Request::input('status'));
        if(Request::has('expire')){
            $user->expire = true;
        } else {
            $user->expire = false;
        }
        $user->expires = strtotime(htmlspecialchars(Request::input('expires'))) - (int) htmlspecialchars(Request::input('offset'));
        $user->level()->associate($level);

        if(Suser::where('project_domain', $project->domain)->where('email', $user->email)->where('email', '!=', $old_email)->count()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Данный email уже зарегистрирован в данном проекте!']);
        }

        $user->save();
        if(Request::has('send_data')){
            $this->sendData($project, $user->id);
            return redirect()->back()->with('popup_ok', ['Данные доступа', 'Настройки пользователя сохранены успешно! Вы успешно отправили данные доступа пользователю!']);
        } else {
            return redirect()->back()->with('popup_ok', ['Редактировать пользователя', 'Настройки пользователя сохранены успешно!']);
        }
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

        $project       = Project::findOrFail(Session::get('selected_project'));
        $msg = false;

        foreach ($arr as $id){
            $user = Suser::findOrFail($id);
            if ($user->project->user->id !== Auth::guard('backend')->id()) {
                return redirect('/users')->with('popup_info', ['Ошибка', 'Вы выбрали не вашего пользователя!']);
            }
            if($action === "delete"){
                foreach($user->comments as $comment){
                    $comment->delete();
                }
                $user->delete();
            } elseif($action === "level"){
                $level = Level::findOrFail(htmlspecialchars(Request::input('level')));
                if ($level->project->user->id !== Auth::guard('backend')->id()) {
                    return redirect('/users')->with('popup_info', ['Ошибка', 'Вы выбрали не ваш уровень!']);
                }
                $user->level()->associate($level);
                $user->save();
            } elseif($action === "status"){
                $user->status = (int) htmlspecialchars(Request::input('status'));
                $user->save();
            } elseif($action === "expire"){
                $user->expire = (int) htmlspecialchars(Request::input('expire'));
                $user->save();
                if($user->expire && Request::has('expires') && Request::has('offset') && !empty(Request::input('expires')) && !empty(Request::input('offset'))){
                    $user->expires = strtotime(htmlspecialchars(Request::input('expires'))) - (int) htmlspecialchars(Request::input('offset'));
                    $user->save();
                }
            } elseif($action === "data"){
                $this->sendData($project, $user->id);
                $msg = true;
            }
        }
        if($msg){
            return redirect()->back()->with('popup_ok', ['Данные доступа', 'Вы успешно отправили данные доступа пользователям!']);
        } else {
            return redirect()->back();
        }
    }

    public function import(){
        $project       = Project::findOrFail(Session::get('selected_project'));
        $v = Validator::make(Request::all(), ['file' => 'required|mimes:csv,txt'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $csv = Request::file('file');
        if (!$csv->isValid()) {
            return redirect()->back()->with('popup_info', ['Ошибка', 'Файл поврежден или неправильного формата!']);
        }

        $level = Level::find((int) (Request::input('level_id')));
        $password = htmlspecialchars(Request::input('password'));
        $expire = false;
        if(Request::has('expire')){
            $expire = true;
        }
        $expires = false;
        if(Request::has('expires') && !empty(Request::input('expires'))){
            $expires = strtotime(htmlspecialchars(Request::input('expires'))) - (int) htmlspecialchars(Request::input('offset'));
        }
        $status = (int) (Request::input('status'));

        $res = Excel::load($csv->getRealPath())->get();
        $import = $res->toArray();
        foreach($import as $im){
            if(empty($im['email']) || $project->susers()->where('email', $im['email'])->count()){
                continue;
            } else if($project->susers->count() >= Auth::guard('backend')->user()->plan->susers){
                break;
            }
            $user = new Suser();
            $user->project()->associate($project);
            $user->rand = str_random(8);
            $user->email = $im['email'];
            if(!empty($im['name'])){
                $user->name = $im['name'];
            } else {
                $user->name = $user->email;
            }
            if(!empty($im['phone'])){
                $user->phone = $im['phone'];
            }
            if($level){
                $user->level()->associate($level);
            } else if(!empty($im['level_name']) && $project->levels()->where('name', $im['level_name'])->count()){
                $ulevel = $project->levels()->where('name', $im['level_name'])->first();
                $user->level()->associate($ulevel);
            } else {
                $ulevel = $project->levels()->first();
                $user->level()->associate($ulevel);;
            }
            if(!empty($password)){
                $user->password = Hash::make($password);
                $user->password_raw = $password;
            } else if(!empty($im['password']) && $im['password'] !== "OLD PASSWORD"){
                $user->password = Hash::make($im['password']);
                $user->password_raw = $im['password'];
            } else {
                $user->password = Hash::make("62256225");
                $user->password_raw = "62256225";
            }
            if($status){
                switch ($status){
                    case 11: $user->status = true; break;
                    case 10: $user->status = false; break;
                    default: $user->status = true;
                }
            } else if(isset($im['status'])){
                $user->status = (int) $im['status'];
            } else {
                $user->status = true;
            }
            if($expire){
                $user->expire = true;
            } else if(isset($im['expire'])){
                $user->expire = (int) $im['expire'];
            } else {
                $user->expire = false;
            }
            if($expires){
                $user->expires = $expires;
            } else if(!empty ($im['expires'])){
                $uexp = strtotime(htmlspecialchars($im['expires']));
                if($uexp > 0 && $uexp <= 2147483647){
                    $user->expires = $uexp;
                } else {
                    $user->expires = getTimePlus();
                }
            } else {
                $user->expires = getTimePlus();
            }
            $user->save();
        }

        return redirect('/users/import')->with('popup_ok', ['Импорт пользователей', 'Вы успешно импортировали пользователей! Если вы заметили какие-то ошибки в импорированных пользователях - проверьте файл импорта']);
    }

    public function importManual(){
        $project       = Project::findOrFail(Session::get('selected_project'));
        $v = Validator::make(Request::all(), ['import' => 'required'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $level = Level::find((int) (Request::input('level_id')));
        $password = htmlspecialchars(Request::input('password'));
        $expire = false;
        if(Request::has('expire')){
            $expire = true;
        }
        $expires = false;
        if(Request::has('expires') && !empty(Request::input('expires'))){
            $expires = strtotime(htmlspecialchars(Request::input('expires'))) - (int) htmlspecialchars(Request::input('offset'));
        }
        $status = (int) (Request::input('status'));

        $import = htmlspecialchars(Request::input('import'));
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $import) as $line){
            $arr = explode(",", $line);
            if(!empty($arr[0])){
                $email = trim($arr[0]);
            } else {
                continue;
            }
            if(!empty($arr[1])){
                $name = trim($arr[1]);
            } else {
                $name = $email;
            }
            if(!empty($arr[2])){
                $phone = trim($arr[2]);
            } else {
                $phone = "";
            }

            if($project->susers()->where('email', $email)->count()){
                continue;
            } else if($project->susers->count() >= Auth::guard('backend')->user()->plan->susers){
                break;
            }
            $user = new Suser();
            $user->project()->associate($project);
            $user->rand = str_random(8);
            $user->email = $email;
            $user->name = $name;
            $user->phone = $phone;
            $user->level()->associate($level);
            $user->password = Hash::make($password);
            $user->password_raw = $password;
            $user->status = $status;
            if($expire){
                $user->expire = true;
            }
            if($expires){
                $user->expires = $expires;
            } else {
                $user->expires = getTimePlus();
            }
            $user->save();
        }
        return redirect('/users/import')->with('popup_ok', ['Импорт пользователей', 'Вы успешно импортировали пользователей!']);
    }

    public function exportCSV(){
        $project       = Project::findOrFail(Session::get('selected_project'));
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="export.csv"');
        header('Cache-Control: max-age=0');
        echo "\xEF\xBB\xBF";
        echo "name,email,level_name,phone,password,status,expire,expires\r\n";
        foreach ($project->susers as $user){
            echo $user->name.','.$user->email.','.$user->level->name.','.$user->phone.','.$user->password_raw.','.(int) $user->status.','.(int) $user->expire.','.getDatetime($user->expires)."\r\n";
        }
    }
    
    public function exportXLS(){
        Excel::create('Экспорт пользователей', function($excel) {
            $excel->sheet('Экспорт пользователей', function($sheet) {
                $sheet->row(1, ["Имя", "Email", "Уровень доступа", "Телефон", "Пароль", "Статус", "Ограничивать?", "Ограничить до", "Дата добавления"]);
                $count = 2;
                $sheet->setOrientation('landscape');
                $project       = Project::findOrFail(Session::get('selected_project'));
                foreach ($project->susers()->orderBy('created_at', 'desc')->get() as $user){
                    $sheet->row($count, [$user->name, $user->email, $user->level->name, $user->phone, $user->password_raw, $user->status, (int) $user->expire, getDatetime($user->expires), $user->created_at]);
                    $count++;
                }
            });
        })->export('xls');
    }

    public function delete($user_id)
    {
        $user = Suser::findOrFail($user_id);
        if($user->project->user->id !== Auth::guard('backend')->id()){
            return redirect()->back()->with('popup_info', ['Ошибка', 'Это не ваш пользователь']);
        }
        foreach($user->comments as $comment){
            $comment->delete();
        }
        $user->delete();
        return redirect()->back();
    }

    public function dataAll(){
        $project       = Project::findOrFail(Session::get('selected_project'));
        foreach($project->susers as $user){
            $this->sendData($project, $user->id);
        }
        return redirect()->back()->with('popup_ok', ['Данные доступа', 'Вы успешно отправили данные доступа пользователям!']);
    }

    public function dataByLevel($level_id){
        $project       = Project::findOrFail(Session::get('selected_project'));
        $level = Level::findOrFail($level_id);
        if($level->project->domain !== $project->domain){
            return redirect()->back()->with('popup_info', ['Неизвесная ошибка', 'Попробуйте еще раз']);
        }
        foreach($level->susers as $user){
            $this->sendData($project, $user->id);
        }
        return redirect()->back()->with('popup_ok', ['Данные доступа', 'Вы успешно отправили данные доступа пользователям!']);
    }

    public function data($user_id){
        $project       = Project::findOrFail(Session::get('selected_project'));
        $this->sendData($project, $user_id);
        return redirect()->back()->with('popup_ok', ['Данные доступа', 'Вы успешно отправили данные доступа пользователю!']);
    }

    public function search(){
        $v = Validator::make(Request::all(), ['query' => 'required|max:40'], $this->messages);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }

        $project = Project::findOrFail(Session::get('selected_project'));
        $query = htmlspecialchars(Request::input('query'));

        $search = Suser::whereRaw("to_tsvector(susers.name) @@ plainto_tsquery('" . $query . "') AND susers.project_domain = '".$project->domain."'")->orWhereRaw("to_tsvector(susers.email) @@ plainto_tsquery('" . $query . "') AND susers.project_domain = '".$project->domain."'")->orWhereRaw("to_tsvector(susers.rand) @@ plainto_tsquery('" . $query . "') AND susers.project_domain = '".$project->domain."'");
        $data['count'] = $search->count();
        $data['query'] = $query;
        $users = $search->paginate((int) Session::get('perpage'));

        $data['title'] = "Поиск / Пользователи / ".$project->name;
        return view("backend.susers.search")->with('project', $project)->with('data', $data)->with('users', $users);
    }

    /* -- */

    private function sendData($project, $user_id){
        if(!empty($project->remote_domain)){
            $link = "http://".$project->remote_domain."/";
        } else {
            $link = "http://".$project->domain.".".config('app.domain')."/";
        }
        $user = Suser::find($user_id);
        if($user){
            $msg = "Здравствуйте, " . $user->name . "!\r\n"
                . "Напоминаем Вам данные доступа к проекту:\r\n"
                . $link . "login\r\n"
                . "Email: " . $user->email . "\r\n";
                if(!empty($user->password_raw) && $user->password_raw !== "OLD PASSWORD"){
                    $msg .= "Пароль: ".$user->password_raw."\r\n";
                } else {
                    $current = SuserPassword::where('project_domain', $project->domain)->where('suser_id', $user->id)->first();
                    if($current){
                        $key = $current->key;
                    } else {
                        $data           = new SuserPassword();
                        $data->key      = str_random(16);
                        $data->suser()->associate($user);
                        $data->project()->associate($project);
                        $data->save();
                        $key = $data->key;
                    }
                    $msg .= "Пароль: Необходимо создать новый:\r\n"
                    .$link . "pass/".$key;
                }
            Mail::raw($msg, function($message) use ($user, $project) {
                $message->from('postmaster@abckabinet.ru', $project->name . ' - ABC Кабинет');
                $message->to($user->email)->subject($project->name . ' - данные доступа');
            });
        }
    }
}