<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        return response()->json(Auth::user()->tasks);
    }

    public function show($id) {
        $task = Auth::user()->tasks()->find($id);
        if(!$task) {
            return response()->json(['Message' => 'Task not found'], 401);
        }
        return response()->json($task);
    }

    public function store(Request $request) {
        $request->validate([
           'name' => 'required|string|max:50',
           'description' => 'required|string|max:250',
           'price' => 'required|numeric|min:0',
           'completed' => 'required|boolean',
        ]);

        $task = Auth::user()->tasks()->create([$request->all()]);
        return response()->json($task, 201);
    }

    public function update(Request $request, $id) {
        $task = Auth::user()->tasks->find($id);
        if(!$task) {
            return response()->json(['Message' => 'Task not found'], 404);
        }
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:250',
            'price' => 'required|numeric|min:0',
            'completed' => 'required|boolean',
        ]);
        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy($id) {
        $task = Auth::user()->tasks()->find($id);
        if(!$task) {
            return response()->json(['Message' => 'Task not found'], 401);
        }
        $task->delete();
        return response()->json(null, 204);
    }

}
