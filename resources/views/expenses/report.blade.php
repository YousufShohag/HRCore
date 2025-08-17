@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Expense Report (Date to Date)</h2>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary mb-3">← Back to Expences</a>

    <form action="{{ route('expenses.report.data') }}" method="POST" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ $from_date ?? '' }}" required>
            </div>
            <div class="col-md-4">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ $to_date ?? '' }}" required>
            </div>
            <div class="col-md-4 mt-4">
                <button type="submit" class="btn btn-primary ">Generate Report</button>
            </div>
        </div>
    </form>

    @isset($expenses)
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4>Results from {{ \Carbon\Carbon::parse($from_date)->format('m/d/y') }} to {{ \Carbon\Carbon::parse($to_date)->format('m/d/y') }}</h4>

       
        <button class="btn btn-success" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Report
        </button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('m/d/Y') }}</td>
                    <td>{{ $expense->title }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ number_format($expense->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No expenses found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h5>Total Amount: {{ number_format($totalAmount, 2) }}</h5>
@endisset

</div>
@endsection
