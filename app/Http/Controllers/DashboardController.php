<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        // ======================
        // HARIAN
        // ======================
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayIncome = Transaction::whereDate('transaction_date', $today)
            ->sum('total_price');

        // jumlah "pelanggan" = jumlah transaksi hari ini
        $todayCustomers = Transaction::whereDate('transaction_date', $today)
            ->count();

        // transaksi kemarin (untuk popup)
        $yesterdayTransactions = Transaction::with('user')
            ->whereDate('transaction_date', $yesterday)
            ->orderBy('transaction_date', 'desc')
            ->get();

        // ======================
        // GRAFIK HARIAN (7 HARI)
        // ======================
        // $dailyChart = Transaction::selectRaw(
        //         'DATE(transaction_date) as date,
        //         SUM(total_price) as income,
        //         COUNT(id) as customers'
        //     )
        //     ->whereDate('transaction_date', '>=', now()->subDays(6))
        //     ->groupBy('date')
        //     ->orderBy('date')
        //     ->get();

        $dailyChart = Transaction::selectRaw('DATE(transaction_date) as date, SUM(total_price) as income, COUNT(*) as customers')
            ->whereDate('transaction_date', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();


        // ======================
        // GRAFIK BULANAN (TAHUN INI)
        // ======================
        $monthlyChart = Transaction::selectRaw(
                'MONTH(transaction_date) as month,
                SUM(total_price) as income,
                COUNT(id) as customers'
            )
            ->whereYear('transaction_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // return view('dashboard', compact(
        //     'todayIncome',
        //     'todayCustomers',
        //     'yesterdayTransactions',
        //     'dailyChart',
        //     'monthlyChart'
        // ));

        // Bulan ini
        $incomeMonth = Transaction::whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->sum('total_price');

        return view('dashboard', compact(
            'todayIncome',
            'todayCustomers',
            'yesterdayTransactions',
            'dailyChart',
            'monthlyChart',
            'incomeMonth',
        ));
    }
}
