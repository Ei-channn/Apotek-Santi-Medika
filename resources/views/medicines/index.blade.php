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
                        <h3 class="section-title">Daftar Obat</h3>

                        <button class="btn"><a href="{{ route('medicines.create') }}" style="color:white;">Tambah</a></button>
                    </div>
                    <input type="text" id="search" placeholder="Cari kode / nama / kategori" >
                    <br><br>
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>Expired</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="medicine-body">
                            @foreach ($medicines as $m)
                                <tr>
                                    <td>{{ $m->code }}</td>
                                    <td>{{ $m->name }}</td>
                                    <td>{{ $m->stock }}</td>
                                    <td>{{ number_format($m->price) }}</td>
                                    <td>{{ $m->category->name }}</td>
                                    <td>{{ $m->expired_date }}</td>
                                    <td>
                                        <a href="{{ route('medicines.edit', $m->id) }}" style="color:blue;">Edit</a>
                                        <form action="{{ route('medicines.destroy', $m->id) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background-color:rgba(240, 248, 255, 0);
                                                                        border:none;cursor: pointer;color:red; font-size:11px">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

<script>
    const csrf = '{{ csrf_token() }}';
    document.getElementById('search').addEventListener('keyup', function() {
        fetch('/medicines/search?q=' + this.value)
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById('medicine-body');
                tbody.innerHTML = '';

                data.forEach(m => {
                    tbody.innerHTML += `
                <tr>
                    <td>${m.code}</td>
                    <td>${m.name}</td>
                    <td>${m.category.name}</td>
                    <td>${m.stock}</td>
                    <td>Rp ${Number(m.price).toLocaleString()}</td>
                    <td>${m.expired_date ?? '-'}</td>
                    <td>
                        <a href="/medicines/${m.id}/edit">Edit</a>

                        <form action="/medicines/${m.id}" method="POST" style="display:inline">
                            <input type="hidden" name="_token" value="${csrf}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" style="background-color:rgba(240, 248, 255, 0);
                                border:none;cursor: pointer;color:red; font-size:11px"">Hapus</button>
                        </form>
                    </td>
                </tr>`;
                });
            });
    });
</script>


{{ $medicines->links() }}
