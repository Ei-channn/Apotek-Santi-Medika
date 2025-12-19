<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with('user')
            ->orderBy('transaction_date', 'desc');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('transaction_date', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::where('stock', '>', 0)->get();
        return view('transactions.create', compact('medicines'));
    }

    public function search(Request $request)
    {
        return Medicine::with('category')
        ->where('name', 'like', '%' . $request->q . '%')
        ->orWhere('code', 'like', '%' . $request->q . '%')
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'payment' => 'required|numeric|min:0',
        'items' => 'required|array|min:1',
        'items.*.medicine_id' => 'required|exists:medicines,id',
        'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;
        $items = [];

        foreach ($request->items as $item) {
            $medicine = Medicine::findOrFail($item['medicine_id']);

            if ($item['quantity'] > $medicine->stock) {
                return back()->withErrors([
                    'stock' => "Stok {$medicine->name} tidak cukup"
                ]);
            }

            $subtotal = $medicine->price * $item['quantity'];
            $total += $subtotal;

            $items[] = [
                'medicine' => $medicine,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal
            ];
        }

        if ($request->payment < $total) {
            return back()->withErrors([
                'payment' => 'Uang bayar kurang'
            ]);
        }

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'total_price' => $total,
            'payment' => $request->payment,
            'change' => $request->payment - $total,
            'transaction_date' => now()
        ]);

        foreach ($items as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'medicine_id' => $item['medicine']->id,
                'quantity' => $item['quantity'],
                'price' => $item['medicine']->price,
                'subtotal' => $item['subtotal']
            ]);

            $item['medicine']->decrement('stock', $item['quantity']);
        }

        return redirect()->route('transactions.show', $transaction->id)
            ->with('success', 'Transaksi berhasil');
    }


    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('details.medicine.category', 'user');
        return view('transactions.show', compact('transaction'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
