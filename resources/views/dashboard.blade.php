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

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            @include('layout.header')

            <!-- Dashboard Section -->
            <div class="content-section active" id="dashboard">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <a href="{{ route('reports.daily') }}">
                        <div class="stat-card">
                            <div class="stat-icon">💰</div>
                            <div class="stat-label">Keuangan Hari Ini</div>
                            <div class="stat-value">Rp {{ number_format($todayIncome) }}</div>
                            {{-- <div class="stat-change">↑ 12.5% dari kemarin</div> --}}
                        </div>
                    </a>
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-label">Jumlah Pelanggan Hari Ini</div>
                        <div class="stat-value">{{ $todayCustomers }} pelanggan</div>
                        {{-- <div class="stat-change">↑ 8 pelanggan baru</div> --}}
                    </div>
                    <a href="{{ route('reports.monthly') }}">
                        <div class="stat-card">
                            <div class="stat-icon">📈</div>
                            <div class="stat-label">Keuangan Bulanan</div>
                            <div class="stat-value">Rp {{ number_format($incomeMonth) }}</div>
                            {{-- <div class="stat-change">↑ 18.3% dari bulan lalu</div> --}}
                        </div>
                    </a>
                </div>
            </div>
            <div class="content-section" id="categories">
                <div class="section-card">
                    <div class="section-header">
                        <h3>Grafik Penghasilan Harian (7 Hari)</h3><br>
                    </div>
                    <div style="height:230px;">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="content-section" id="categories">
                <div class="section-card">
                    <div class="section-header">

                        <h3>Grafik Penghasilan Bulanan ({{ now()->year }})</h3>
                    </div>
                    <div style="height:230px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        // Navigation
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.content-section');

        // Animate stats on load
        window.addEventListener('load', () => {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });
        });

        // Interactive stats cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });
        });

        function openModal() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
        new Chart(document.getElementById('dailyChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($dailyChart->pluck('date')) !!},
                datasets: [{
                        label: 'Penghasilan',
                        data: {!! json_encode($dailyChart->pluck('income')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        yAxisID: 'yIncome'
                    },
                    {
                        label: 'Pelanggan',
                        data: {!! json_encode($dailyChart->pluck('customers')) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        yAxisID: 'yCustomer'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yIncome: {
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString()
                        }
                    },
                    yCustomer: {
                        type: 'linear',
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });



        new Chart(document.getElementById('monthlyChart'), {
            data: {
                labels: {!! json_encode($monthlyChart->pluck('month')) !!},
                datasets: [{
                        type: 'bar',
                        label: 'Penghasilan Bulanan',
                        data: {!! json_encode($monthlyChart->pluck('income')) !!},
                        borderColor: 'rgb(79,70,229)',
                        backgroundColor: 'rgba(79,70,229,0.2)',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'yIncome'
                    },
                    {
                        type: 'bar',
                        label: 'Pelanggan Bulanan',
                        data: {!! json_encode($monthlyChart->pluck('customers')) !!},
                        backgroundColor: 'rgba(245,158,11,0.7)',
                        yAxisID: 'yCustomer'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yIncome: {
                        position: 'left',
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString()
                        }
                    },
                    yCustomer: {
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
