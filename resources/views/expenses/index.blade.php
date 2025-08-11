@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Expense List</h2>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary mb-3">Add Expense</a>
    <a href="{{ route('expenses.report') }}" class="btn btn-primary mb-3">Report</a>

   

    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}


    

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->title }}</td>
                <td>{{ $expense->amount }}</td>
                <td>{{ $expense->expense_date }}</td>
                <td>{{ $expense->description }}</td>
                <td>
                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this expense?')" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $expenses->links() }}
</div>
@endsection
