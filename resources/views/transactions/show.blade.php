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
                        <h3>Detail Transaksi</h3>
                    </div><br>

                    <p>
                        <strong>Tanggal :</strong> {{ $transaction->transaction_date }} <br>
                        <strong>Kasir :</strong> {{ $transaction->user->name }} <br>
                        <strong>ID Transaksi :</strong> {{ $transaction->id }}
                    </p>

                    <br><br>

                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                {{-- <th>Kode Obat</th> --}}
                                <th>Nama Obat</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->details as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- <td>{{ $detail->medicine->code }}</td> --}}
                                    <td>{{ $detail->medicine->name }}</td>
                                    <td>{{ $detail->medicine->category->name }}</td>
                                    <td>{{ number_format($detail->price) }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->subtotal) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br><br>

                    <h3>Ringkasan Pembayaran</h3>
                    <table>
                        <tr>
                            <td>Total Harga</td>
                            <td>:</td>
                            <td><strong>{{ number_format($transaction->total_price) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Bayar</td>
                            <td>:</td>
                            <td>{{ number_format($transaction->payment) }}</td>
                        </tr>
                        <tr>
                            <td>Kembalian</td>
                            <td>:</td>
                            <td>{{ number_format($transaction->change) }}</td>
                        </tr>
                    </table>

                    <br>
                    <button class="btn">
                        <a href="{{ route('transactions.index') }}" style="color:white;">← Kembali ke History</a>
                    </button>
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST"
                                style="display:inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="back">
                                        Hapus Transaksi
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </main>
    </div>
</body>

</html>
