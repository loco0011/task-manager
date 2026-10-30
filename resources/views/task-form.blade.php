<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="form-control @error('title') is-invalid @enderror" required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="due_date" class="form-label">Due date</label>
    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" class="form-control @error('due_date') is-invalid @enderror" required>
    @error('due_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
        @foreach (App\Models\Task::STATUSES as $option)
            <option value="{{ $option }}" @selected(old('status', $task->status) == $option)>{{ $option }}</option>
        @endforeach
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
