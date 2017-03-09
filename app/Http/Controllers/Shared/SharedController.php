<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Request;
use Validator;
use App\Task;

class SharedController extends Controller
{

    private $messages = [
        'name.required' => "Вы не ввели название!"
    ];

    public function index()
    {
        $data = Task::all();
        return view('shared.index')->with('data', $data);
    }

    /* -- */

    public function store(){
        $v = Validator::make(Request::all(), ['name' => 'required|max:32'], $this->messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v);
        }
        
        $task = new Task();
        $task->name = htmlspecialchars(Request::input('name'));
        $task->comment = htmlspecialchars(Request::input('comment'));
        $task->save();

        return redirect('/');
    }

    public function delete($id){
        $task = Task::findOrFail($id);
        $task->delete();
        
        return redirect('/');
    }
}