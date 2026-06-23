@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5>Edit Issue</h5></div>
            <div class="card-body">
                <form action="{{ route('projects.issues.update', [$project, $issue]) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $issue->title) }}">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $issue->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="open" {{ old('status', $issue->status) == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ old('status', $issue->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="closed" {{ old('status', $issue->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Priority *</label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                                <option value="low" {{ old('priority', $issue->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $issue->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $issue->priority) == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $issue->due_date) }}">
                            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign Members</label>
                        <select name="members[]" class="form-select" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $issue->users->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl to select multiple members</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <select name="tags[]" class="form-select" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ $issue->tags->contains($tag->id) ? 'selected' : '' }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl to select multiple tags</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Issue</button>
                        <a href="{{ route('projects.issues.show', [$project, $issue]) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection