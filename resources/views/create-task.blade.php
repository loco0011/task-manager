@extends('layout')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-3">New Task</h4>
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            @include('task-form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>
@endsection
