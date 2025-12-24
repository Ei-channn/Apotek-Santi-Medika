<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Santi Medika</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-apotek.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css?v=2') }}">
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
                    <a href="{{ route('transactions.index') }}" class="nav-link active" data-page="details">
                        <span class="nav-icon">📋</span>
                        <span>History Transaksi</span>
                    </a>
                </li>
            </ul>
        </aside>
        <main class="main-content">
            <!-- Header -->
            @include('layout.header')

            {{-- <input type="date" name="start_date" value="{{ request('start_date') }}"> --}}
            <div class="content-section" id="categories">
                <div class="section-card">
                    <div class="section-header">
                        <h3>History Transaksi</h3>

                        <form method="GET" class="filter-form" id="filterForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Dari Tanggal</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                                </div>

                                <div class="form-group">
                                    <label>Sampai Tanggal</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}">
                                </div>
                                <button type="submit" class="btn">Filter</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-wrapper">
                        <table>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Kembali</th>
                                <th>Aksi</th>
                            </tr>

                            @foreach ($transactions as $trx)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $trx->transaction_date }}</td>
                                    <td>{{ $trx->user->name }}</td>
                                    <td>{{ number_format($trx->total_price) }}</td>
                                    <td>{{ number_format($trx->payment) }}</td>
                                    <td>{{ number_format($trx->change) }}</td>
                                    <td>
                                        <a href="{{ route('transactions.show', $trx->id) }}">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div><br>

                    {{-- {{ $transactions->links() }} --}}
                    {{ $transactions->withQueryString()->links() }}

                </div>
            </div>
        </main>
    </div>
</body>

</html>
