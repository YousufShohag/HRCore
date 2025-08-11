@extends('layouts.app')

@section('content')
<a href="{{ route('transactions.index') }}" class="btn btn-outline-primary">📄 All Transections</a>
<h2>Add Transaction</h2>
<form action="{{ route('transactions.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-control" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Amount</label>
        <input type="number" name="amount" class="form-control" step="0.01" required>
    </div>
    <div class="mb-3">
        <label>Date</label>
        <input type="date" name="transaction_date" class="form-control" required>
    </div>
    <button class="btn btn-success">Save</button>
</form>
@endsection
