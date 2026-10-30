<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $tasks = $request->user()->tasks()->orderBy('due_date');

        if (in_array($status, Task::STATUSES)) {
            $tasks->where('status', $status);
        }

        return view('task', [
            'tasks' => $tasks->get(),
            'status' => $status,
        ]);
    }

    public function create()
    {
        return view('create-task', ['task' => new Task(['status' => 'Pending'])]);
    }

    public function store(Request $request)
    {
        $request->user()->tasks()->create($this->validateTask($request));

        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    public function edit(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        return view('edit-task', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->update($this->validateTask($request));

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->tasks()->findOrFail($id)->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    private function validateTask(Request $request)
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'status' => ['required', Rule::in(Task::STATUSES)],
        ]);
    }
}
