<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense added successfully.');
    }
// public function show($id)
// {
//     $expense = Expense::findOrFail($id);
//     return view('expenses.show', compact('expense'));
// }
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

     public function report()
    {
        return view('expenses.report'); // Show form
    }

    public function reportData(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $expenses = Expense::whereBetween('expense_date', [$request->from_date, $request->to_date])
            ->orderBy('expense_date', 'asc')
            ->get();

        $totalAmount = $expenses->sum('amount');

        return view('expenses.report', [
            'expenses' => $expenses,
            'totalAmount' => $totalAmount,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date
        ]);
    }
}
