@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">My Tasks</h4>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">New Task</a>
</div>

<form method="GET" action="{{ route('tasks.index') }}" class="mb-3">
    <select name="status" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">All</option>
        @foreach (App\Models\Task::STATUSES as $option)
            <option value="{{ $option }}" @selected($status == $option)>{{ $option }}</option>
        @endforeach
    </select>
</form>

<div class="table-responsive bg-white">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td class="text-nowrap">{{ $task->due_date->format('d M Y') }}</td>
                    <td class="text-nowrap">{{ $task->status }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="d-inline" onsubmit="return confirm('Delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
