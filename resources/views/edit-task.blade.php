@extends('layout')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-3">Edit Task</h4>
        <form method="POST" action="{{ route('tasks.update', $task->id) }}">
            @csrf
            @method('PUT')
            @include('task-form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>
@endsection
