@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">{{ $project->name }}</h1>
        <p class="text-muted">{{ $project->description }}</p>
        @if($project->start_date)
            <small class="text-muted"><i class="bi bi-calendar"></i> Start: {{ $project->start_date }} | Deadline: {{ $project->deadline ?? 'N/A' }}</small>
        @endif
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.issues.create', $project) }}" class="btn btn-primary"><i class="bi bi-plus"></i> New Issue</a>
        <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary">Edit</a>
    </div>
</div>

<div class="card">
    <div class="card-header">Issues ({{ $project->issues->count() }})</div>
    <div class="card-body">
        @if($project->issues->isEmpty())
            <p class="text-muted">No issues yet.</p>
        @else
            <table class="table table-hover">
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
                    @foreach($project->issues as $issue)
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
        @endif
    </div>
</div>
@endsection