<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Issue Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #2d3748 !important; }
        .navbar-brand, .nav-link { color: #fff !important; }
        .nav-link:hover { color: #a0aec0 !important; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .badge-open { background-color: #48bb78; }
        .badge-in_progress { background-color: #ed8936; }
        .badge-closed { background-color: #a0aec0; }
        .badge-low { background-color: #68d391; }
        .badge-medium { background-color: #f6ad55; }
        .badge-high { background-color: #fc8181; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('projects.index') }}">
                <i class="bi bi-bug"></i> Issue Tracker
            </a>
            <div class="navbar-nav ms-auto d-flex flex-row align-items-center gap-3">
                <a class="nav-link" href="{{ route('projects.index') }}">Projects</a>
                <a class="nav-link" href="{{ route('tags.index') }}">Tags</a>
                @auth
                    <span class="nav-link text-light">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                    </form>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>