<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger btn-sm">Logout</button>
        </form>
    </div>

    <p>Welcome, <strong>{{ auth()->user()->name }}</strong> — role: <code>{{ auth()->user()->role }}</code></p>

    @if (auth()->user()->isAdmin())
        <a href="{{ route('admin') }}" class="btn btn-warning">Go to Admin Page</a>
    @else
        <p class="text-muted">You're logged in as a regular user. The admin page is hidden from you.</p>
    @endif
</div>
</body>
</html>