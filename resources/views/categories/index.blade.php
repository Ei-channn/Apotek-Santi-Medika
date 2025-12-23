<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Santi Medika</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-apotek.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
                            <a href="{{ route('categories.index') }}" class="nav-link active" data-page="categories">
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
        <main class="main-content">
            @include('layout.header')
            <div class="content-section" id="categories">
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-title">Kategori Obat</h3>
                        <button class="btn">
                            <a href="{{ route('categories.create') }}" style="color:white;">Tambah</a>
                        </button>
                    </div>
                    <input type="text" id="search" placeholder="Cari kategori">
                    <br><br>

                    <table>
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Jumlah Obat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="category-body">
                            @foreach ($categories as $category)
                                <tr>
                                    <td>
                                        {{ $category->name }}
                                    </td>
                                    <td>{{ $category->medicines_count }} item</td>
                                    <td>
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            style="color:blue;">Edit</a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background-color:rgba(240, 248, 255, 0);
                                                        border:none;cursor: pointer;color:red; font-size:11px">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $categories->links() }}
                </div>
            </div>
        </main>
    </div>
</body>

<script>
    function loadCategory(q = '') {
        fetch('/categories/search?q=' + q)
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById('category-body');
                tbody.innerHTML = '';

                data.forEach((cat, index) => {
                    tbody.innerHTML += `
                <tr>
                    <td>${cat.name}</td>
                    <td>${cat.medicines_count}</td>
                    <td>
                        <a href="/categories/${cat.id}/edit">Edit</a>
                        <button onclick="deleteCategory(${cat.id})"
                            style="background:none;border:none;color:red;cursor:pointer;font-size:11px">
                            Hapus
                        </button>
                    </td>
                </tr>`;
                });
            });
    }

    function deleteCategory(id) {
        if (!confirm('Yakin hapus kategori ini?')) return;

        fetch(`/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (res.ok) {
                    loadCategory(document.getElementById('search').value);
                } else {
                    alert('Gagal menghapus kategori');
                }
            });
    }

    // realtime search
    document.getElementById('search').addEventListener('keyup', function() {
        loadCategory(this.value);
    });

    // load awal
    // loadCategory();
</script>
