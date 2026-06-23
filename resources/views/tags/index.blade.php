@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Tags</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h5>Create Tag</h5></div>
            <div class="card-body">
                <form action="{{ route('tags.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', '#6c757d') }}">
                        @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Tag</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5>All Tags</h5></div>
            <div class="card-body">
                @if($tags->isEmpty())
                    <p class="text-muted">No tags yet.</p>
                @else
                    @foreach($tags as $tag)
                        <span class="badge p-2 me-2 mb-2 fs-6" style="background-color: {{ $tag->color ?? '#6c757d' }}">
                            {{ $tag->name }}
                            <small>({{ $tag->issues_count }} issues)</small>
                        </span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection