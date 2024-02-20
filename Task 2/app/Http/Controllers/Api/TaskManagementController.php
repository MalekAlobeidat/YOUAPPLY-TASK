<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskManagement;
use Illuminate\Http\Request;

class TaskManagementController extends Controller
{
    public function index()
    {
        return TaskManagement::all();
    }

    public function store(Request $request)
    {
        $task = TaskManagement::create($request->all());
        return response()->json($task, 201);
    }

    public function show($id)
    {
        return TaskManagement::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $task = TaskManagement::findOrFail($id);
        $task->update($request->all());
        return response()->json($task, 200);
    }

    public function destroy($id)
    {
        $task = TaskManagement::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}
