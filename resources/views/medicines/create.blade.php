<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Santi Medika</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-apotek.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h1>Apotek Ku</h1>
                <p>{{ auth()->user()->role }}</p>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link " data-page="dashboard">
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
                            <a href="{{ route('medicines.index') }}" class="nav-link active" data-page="medicines">
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
        <main class="main-content">
            @include('layout.header')
            <div class="content-section" id="medicines">
                <div class="section-card">
                    <div class="section-header">
                        <h3>Tambah Obat</h3>
                    </div>

                    @if ($errors->any())
                        <ul style="color:red">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <form action="{{ route('medicines.store') }}" method="POST">
                        @csrf

                        <label>Kode Obat</label><br>
                        <input type="text" name="code" value="{{ old('code') }}" required><br><br>

                        <label>Nama Obat</label><br>
                        <input type="text" name="name" value="{{ old('name') }}" required><br><br>

                        <label>Stok</label><br>
                        <input type="number" name="stock" value="{{ old('stock') }}" required><br><br>

                        <label>Harga</label><br>
                        <input type="number" name="price" value="{{ old('price') }}" required><br><br>
                        <label>Kategori</label><br>

                        <select name="category_id" required style="width:100%">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select><br><br>

                        <label>Tanggal Expired</label><br>
                        <input type="date" name="expired_date" value="{{ old('expired_date') }}" required>
                        <div class="container-btn">
                            <button type="submit" class="btn">Simpan</button>
                            <a href="{{ route('medicines.index') }}" class="back">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
