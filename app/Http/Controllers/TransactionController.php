<?php

// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // public function index(Request $request)
    // {
    //     $transactions = Transaction::orderBy('transaction_date')->get();

    //     return view('transactions.index', compact('transactions'));
    // }
// public function index(Request $request)
// {
//     $query = Transaction::query();

//     if ($request->start_date && $request->end_date) {
//         $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
//     }

//     if ($request->type) {
//         $query->where('type', $request->type);
//     }

//     $transactions = $query->orderBy('transaction_date', 'desc')->get();

//     return view('transactions.index', compact('transactions'));
// }

public function index(Request $request)
{
    $query = Transaction::query();

    // Apply filters
    if ($request->filled('start_date')) {
        $query->whereDate('transaction_date', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('transaction_date', '<=', $request->end_date);
    }
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    $transactions = $query->orderBy('transaction_date', 'desc')->get();

    // Totals
    $totalIncome = $transactions->where('type', 'income')->sum('amount');
    $totalExpense = $transactions->where('type', 'expense')->sum('amount');
    $balance = $totalIncome - $totalExpense;

    return view('transactions.index', compact('transactions', 'totalIncome', 'totalExpense', 'balance'));
}


    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date'
        ]);

        Transaction::create($request->all());

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaction added successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')
                         ->with('success', 'Transaction deleted successfully.');
    }

    // Report (date-to-date)
    public function report(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $transactions = Transaction::whereBetween('transaction_date', [$startDate, $endDate])->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('transactions.report', compact('transactions', 'totalIncome', 'totalExpense', 'balance', 'startDate', 'endDate'));
    }
}
