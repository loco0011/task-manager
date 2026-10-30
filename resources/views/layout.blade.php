<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar bg-white border-bottom mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Task Manager</a>
            @auth
                <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center">
                    @csrf
                    <span class="me-3 text-muted">{{ auth()->user()->name }}</span>
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>

</html>
