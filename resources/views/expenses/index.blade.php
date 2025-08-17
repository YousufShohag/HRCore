@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Expense List</h2>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary mb-3">Add Expense</a>
    <a href="{{ route('expenses.report') }}" class="btn btn-primary mb-3">Report</a>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">← Back to Dashboard</a>

   

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
        @php $total = 0; @endphp
        @foreach($expenses as $expense)
         @php $total += $expense->amount; @endphp
            <tr>
                <td>{{ $expense->title }}</td>
                <td>{{ $expense->amount }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('m/d/Y')  }}</td>
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
        <tfoot>
            <tr>
                <th colspan="1">Total</th>
                <th>{{ number_format($total, 2) }}</th>
                <th colspan="3"></th>
            </tr>
        </tfoot>
    </table>

    {{ $expenses->links() }}
</div>
@endsection
