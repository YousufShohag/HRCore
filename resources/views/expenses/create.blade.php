@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Expense</h2>
    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        <div class="form-group mb-2">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Amount</label>
            <input type="number" name="amount" step="0.01" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Date</label>
            <input type="date" name="expense_date" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
