<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;    

class ReportController extends Controller
{
    // 🔹 LAPORAN HARIAN
    public function daily()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayTransactions = Transaction::whereDate('transaction_date', $today)->get();
        $yesterdayTransactions = Transaction::whereDate('transaction_date', $yesterday)->get();

        $todayIncome = $todayTransactions->sum('total_price');
        $yesterdayIncome = $yesterdayTransactions->sum('total_price');

        return view('reports.daily', compact(
            'todayTransactions',
            'yesterdayTransactions',
            'todayIncome',
            'yesterdayIncome'
        ));
    }

    // 🔹 LAPORAN BULANAN
    public function monthly()
    {
        $transactions = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->orderBy('transaction_date', 'desc')
            ->get();

        $monthlyIncome = $transactions->sum('total_price');

        return view('reports.monthly', compact(
            'transactions',
            'monthlyIncome'
        ));
    }

    public function dailyReport(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        // untuk tabel (10 data)
        $transactions = Transaction::with('user')
            ->whereDate('transaction_date', $date)
            ->orderBy('transaction_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        // untuk total keseluruhan hari itu
        $totalIncome = Transaction::whereDate('transaction_date', $date)
            ->sum('total_price');

        return view('reports.daily', [
            'transactions'      => $transactions,
            'date'              => $date,
            'totalTransaction'  => $transactions->total(),
            'totalIncome'       => $totalIncome
        ]);
    }

    public function monthlyReport(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        // tabel (pagination)
        $transactions = Transaction::with('user')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->orderBy('transaction_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        // total bulanan (SEMUA DATA)
        $totalIncome = Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('total_price');

        return view('reports.monthly', [
            'transactions'      => $transactions,
            'month'             => $month,
            'year'              => $year,
            'totalTransaction'  => $transactions->total(),
            'totalIncome'       => $totalIncome
        ]);
    }
}
