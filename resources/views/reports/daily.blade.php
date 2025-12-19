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
        @include('layout.nav')
        <main class="main-content">
            <!-- Header -->
            @include('layout.header')

            <div class="content-section" id="medicines">
                <div class="section-card">
                    <div class="section-header">
                        <h3>Laporan Harian</h3>
                    </div>
                    <div style="display: flex; justify-content: space-between">
                        <div style="display:grid; align-items:center;">
                            <p><strong>Tanggal:</strong> {{ $date }}</p>
                            <p><strong>Total Penghasilan:</strong> Rp {{ number_format($totalIncome) }}</p>
                            <p><strong>Total Transaksi:</strong> {{ $totalTransaction }}</p>
                        </div>
                        <form method="GET" class="filter-form" id="filterForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <input type="date" name="date" value="{{ $date }}">
                                </div>
                                <button type="submit" class="btn">Filter</button>
                            </div>
                        </form>
                    </div>

                    <br>

                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Kembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $trx)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $trx->transaction_date }}</td>
                                    <td>{{ $trx->user->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($trx->total_price) }}</td>
                                    <td>Rp {{ number_format($trx->payment) }}</td>
                                    <td>Rp {{ number_format($trx->change) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" align="center">Tidak ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>


                    {{-- Pagination --}}
                    {{ $transactions->links() }}

                </div>
            </div>
        </main>
    </div>
</body>

</html>
