@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Expense</h2>
    <form action="{{ route('expenses.update', $expense) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-2">
            <label>Title</label>
            <input type="text" name="title" value="{{ $expense->title }}" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Amount</label>
            <input type="number" name="amount" value="{{ $expense->amount }}" step="0.01" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Date</label>
            <input type="date" name="expense_date" value="{{ $expense->expense_date }}" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $expense->description }}</textarea>
        </div>
        <button class="btn btn-success">Update</button>
    </form>
</div>
@endsection
