{{-- <!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Apotek</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <h2>Dashboard</h2>
    <p>Login sebagai: {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
    <div class="container">
        <!-- Sidebar --> --}}
<aside class="sidebar">
    <div class="logo">
        <h1>Apotek Ku</h1>
        <p>{{ auth()->user()->role }}</p>
    </div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link active" data-page="dashboard">
                <span class="nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
        </li>
        @auth
            @if (auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link" data-page="categories">
                        <span class="nav-icon">📁</span>
                        <span>Kategori Obat</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('medicines.index') }}" class="nav-link" data-page="medicines">
                        <span class="nav-icon">💊</span>
                        <span>Daftar Obat</span>
                    </a>
                </li>
            @endif
        @endauth

        <li class="nav-item">
            <a href="{{ route('transactions.create') }}" class="nav-link" data-page="transactions">
                <span class="nav-icon">💳</span>
                <span>Transaksi</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('transactions.index') }}" class="nav-link" data-page="details">
                <span class="nav-icon">📋</span>
                <span>History Transaksi</span>
            </a>
        </li>
    </ul>
</aside>
{{-- </div>
</body>
</html> --}}
