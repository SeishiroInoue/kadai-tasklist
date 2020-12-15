<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (Auth::check()) {
            $user = Auth::user();
            $tasks = $user->tasks()->get();
            $data = [
                'tasks' => $tasks,
            ];
            return view('tasks.index', $data);
        } else {
            return view('tasks.index', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = $request->user()->id;
        $task->save();
        
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        $user_id = Auth::id();

        if ($task->user_id == $user_id) {
            return view('tasks.show', [
                'task' => $task,
            ]); } else {
                return redirect('/');
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $task = Task::findOrFail($id);
       $user_id = Auth::id();
       
       if ($task->user_id == $user_id) {
            return view('tasks.edit', [
                'task' => $task,
            ]); } else {
                return redirect('/');
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $task = Task::findOrFail($id);
       $user_id = Auth::id();
       
       if ($task->user_id == $user_id) {
            $request->validate([
                'status' => 'required|max:10',
                'content' => 'required|max:255',
            ]);
            
            $task->status = $request->status;
            $task->content = $request->content;
            $task->user_id = $request->user()->id;
            $task->save();
            
            return redirect('/'); } else {
                return redirect('/');
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $user_id = Auth::id();

        if ($task->user_id == $user_id) {
            $task->delete();
            
            return redirect('/'); } else {
                return redirect('/');
            }
    }
}
