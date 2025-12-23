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
                    <a href="{{ route('transactions.create') }}" class="nav-link active" data-page="transactions">
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
            <!-- Header -->
            @include('layout.header')
            <div class="content-section" id="categories">
                <div class="section-card">
                    <div class="section-header">

                        <h2>Transaksi Penjualan</h2>
                        <div
                            style="
                            display:flex;
                            gap:10px;
                            align-items:center;
                        ">

                            <label>Cari Obat</label><br>
                            <input type="text" id="search" placeholder="kode / nama obat">
                        </div>
                    </div>

                    <table id="result">
                        <thead>
                            <tr>
                                {{-- <th>Kode</th> --}}
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Expired</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicines as $m)
                                <tr onclick='addItem(@json($m))' style="cursor:pointer;">
                                    {{-- <td>{{ $m->code }}</td> --}}
                                    <td>{{ $m->name }}</td>
                                    <td>{{ $m->category->name }}</td>
                                    <td>{{ number_format($m->price) }}</td>
                                    <td>{{ $m->stock }}</td>
                                    <td>{{ $m->expired_date ?? '-' }}</td>
                                    {{-- <td>
                                        <button type="button" onclick="event.stopPropagation()">
                                            Pilih
                                        </button>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br><br>

                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf

                        <table id="cart">
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <br><br>

                        <p>
                            Total: Rp <span id="totalText">0</span>
                            <input type="hidden" name="total" id="totalInput">
                        </p><br>
                        <div style="display: flex; align-items:center; gap:5px">
                            <label>Bayar</label><br>
                            <input type="number" name="payment" class="payment"
                            required>
                        </div>

                        <br><br>
                        <button type="submit" class="btn">Simpan Transaksi</button>
                    </form>

                    <script>
                        let cart = [];

                        function renderCart() {
                            const tbody = document.querySelector('#cart tbody');
                            tbody.innerHTML = '';
                            let total = 0;

                            cart.forEach((item, index) => {
                                const price = Number(item.price);
                                const subtotal = price * Number(item.qty);

                                total += subtotal;

                                tbody.innerHTML += `
                                <tr>
                                    <td>
                                        ${item.name}
                                        <input type="hidden" name="items[${index}][medicine_id]" value="${item.id}">
                                    </td>
                                    <td>Rp ${price.toLocaleString()}</td>
                                    <td>
                                        <input type="number"
                                            min="1"
                                            max="${item.stock}"
                                            value="${item.qty}"
                                            name="items[${index}][quantity]"
                                            onchange="updateQty(${index}, this.value)">
                                    </td>
                                    <td>
                                        Rp ${subtotal.toLocaleString()}
                                        <input type="hidden" name="items[${index}][subtotal]" value="${subtotal}">
                                    </td>
                                </tr>
                                `;
                            });

                            document.getElementById('totalText').innerText = total.toLocaleString();
                            document.getElementById('totalInput').value = total;
                        }


                        function updateQty(index, qty) {
                            qty = parseInt(qty);
                            cart[index].qty = qty;
                            cart[index].subtotal = cart[index].price * qty;
                            renderCart();
                        }

                        function addItem(med) {
                            if (cart.find(i => i.id === med.id)) {
                                alert('Obat sudah ada di keranjang');
                                return;
                            }

                            cart.push({
                                id: med.id,
                                name: med.name,
                                price: Number(med.price),
                                stock: Number(med.stock),
                                qty: 1,
                                subtotal: Number(med.price)
                            });

                            renderCart();
                        }


                        // SEARCH OBAT
                        document.getElementById('search').addEventListener('keyup', function() {
                            fetch(`/medicines/search?q=${this.value}`)
                                .then(res => res.json())
                                .then(data => {
                                    const tbody = document.querySelector('#result tbody');
                                    tbody.innerHTML = '';

                                    data.forEach(med => {
                                        tbody.innerHTML += `
                        <tr onclick='addItem(${JSON.stringify(med)})'
                            style="cursor: pointer;">
                            <td>${med.code}</td>
                            <td>${med.name}</td>
                            <td>${med.category.name}</td>
                            <td>${med.price}</td>
                            <td>${med.stock}</td>
                            <td>${med.expired_date ?? '-'}</td>
                        </tr>
                    `;
                                    });
                                });
                        });
                    </script>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
