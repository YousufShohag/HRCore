@extends('layouts.app')

@section('content')

<a href="{{ route('transactions.index') }}" class="btn btn-outline-primary mb-3">📄 All Transactions</a>
<h2>Transaction Report</h2>

<form method="GET" action="{{ route('transactions.report') }}" class="row mb-3">
    <div class="col-md-4">
        <label>Start Date</label>
        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label>End Date</label>
        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control" required>
    </div>
    <div class="col-md-4 mt-4">
        <button class="btn btn-primary mt-2">Filter</button>
    </div>
</form>

{{-- @if(isset($transactions))
    <div class="mb-2">
        <button onclick="window.print()" class="btn btn-success">🖨 Print Report</button>
    </div>

    <div class="card p-3 mb-3">
        <p><strong>Total Income:</strong> {{ number_format($totalIncome, 2) }}</p>
        <p><strong>Total Expense:</strong> {{ number_format($totalExpense, 2) }}</p>
        <p><strong>Balance:</strong> {{ number_format($balance, 2) }}</p>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Title</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('m/d/Y') }}</td>
                <td>
                    <span class="badge bg-{{ $t->type == 'income' ? 'success' : 'danger' }}">
                        {{ ucfirst($t->type) }}
                    </span>
                </td>
                <td>{{ $t->title }}</td>
                <td>{{ number_format($t->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif --}}

@if(isset($transactions))
    <div class="mb-2 no-print">
        <button onclick="window.print()" class="btn btn-success">🖨 Print Report</button>
    </div>

    <div class="card p-3 mb-3 report-totals">
        <p><strong>Total Income:</strong> {{ number_format($totalIncome, 2) }}</p>
        <p><strong>Total Expense:</strong> {{ number_format($totalExpense, 2) }}</p>
        <p><strong>Balance:</strong> {{ number_format($balance, 2) }}</p>
    </div>

    <table class="table table-bordered report-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Title</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('m/d/Y') }}</td>
                <td>
                    <span class="badge bg-{{ $t->type == 'income' ? 'success' : 'danger' }}">
                        {{ ucfirst($t->type) }}
                    </span>
                </td>
                <td>{{ $t->title }}</td>
                <td>{{ number_format($t->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

<style>
@media print {
    body {
        -webkit-print-color-adjust: exact;
    }

    /* Hide buttons in print */
    .no-print {
        display: none;
    }

    /* Show borders on all table cells */
    table.report-table,
    table.report-table th,
    table.report-table td {
        border: 2px solid #000 !important;
        border-collapse: collapse;
    }

    /* Totals section styling */
    .report-totals {
        border: 2px solid #000;
        padding: 10px;
        margin-bottom: 10px;
    }

    /* Optional: ensure table fills width */
    table.report-table {
        width: 100%;
    }
}
</style>


@endsection
