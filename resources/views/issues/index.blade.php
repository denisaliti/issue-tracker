@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">{{ $project->name }}</h1>
        <p class="text-muted mb-1">{{ $project->description }}</p>
        @if($project->start_date)
            <small class="text-muted"><i class="bi bi-calendar"></i> Start: {{ $project->start_date }} | Deadline: {{ $project->deadline ?? 'N/A' }}</small>
        @endif
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.issues.create', $project) }}" class="btn btn-primary"><i class="bi bi-plus"></i> New Issue</a>
        @auth
            @if(auth()->id() === $project->user_id)
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary">Edit Project</a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger">Delete Project</button>
                </form>
            @endif
        @endauth
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search issues by title or description..." value="{{ request('search') }}">
        </div>
        <form method="GET" id="filter-form" class="row g-2">
            <input type="hidden" name="search" id="search-input">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="tag" class="form-select">
                    <option value="">All Tags</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div id="issues-results">
@if($issues->isEmpty())
    <div class="alert alert-info">No issues found.</div>
@else
    <div class="card">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Tags</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $issue)
                <tr>
                    <td><a href="{{ route('projects.issues.show', [$project, $issue]) }}">{{ $issue->title }}</a></td>
                    <td><span class="badge badge-{{ $issue->status }}">{{ $issue->status }}</span></td>
                    <td><span class="badge badge-{{ $issue->priority }}">{{ $issue->priority }}</span></td>
                    <td>
                        @foreach($issue->tags as $tag)
                            <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @foreach($issue->users as $user)
                            <span class="badge bg-secondary">{{ $user->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $issue->due_date ?? '-' }}</td>
                    <td>
                        <a href="{{ route('projects.issues.edit', [$project, $issue]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('projects.issues.destroy', [$project, $issue]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
</div>
@endsection

@section('scripts')
<script>
    let debounceTimer;
    const projectId = {{ $project->id }};

    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const search = this.value;
            fetch(`/projects/${projectId}/issues?search=${encodeURIComponent(search)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('issues-results').innerHTML = data.html;
            });
        }, 400);
    });
</script>
@endsection