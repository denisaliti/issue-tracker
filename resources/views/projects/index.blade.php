@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Projects</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> New Project
    </a>
</div>

@if($projects->isEmpty())
    <div class="alert alert-info">No projects yet. Create your first one!</div>
@else
    <div class="row">
        @foreach($projects as $project)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($project->description, 100) }}</p>
                    <p class="card-text"><small class="text-muted">{{ $project->issues_count }} issues</small></p>
                    @if($project->deadline)
                        <p class="card-text"><small class="text-muted"><i class="bi bi-calendar"></i> Deadline: {{ $project->deadline }}</small></p>
                    @endif
                </div>
                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('projects.issues.index', $project) }}" class="btn btn-sm btn-outline-primary">View</a>
                    @auth
                        @if(auth()->id() === $project->user_id)
                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection