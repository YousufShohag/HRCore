@extends('layouts.app')

@section('content')


<div class="ontainer">
    <div class="row">
        <div class="col-md-6">
            <div class="mt-4">
                <h2 class="fw-bold mb-3">👥 All Transactions</h2>
                <div class="row mt-2">
                    <div class="col-md-4"><a href="{{ route('transactions.create') }}" class="btn btn-primary ">
                      </i> Add New Transactions
                    </a></div>
                    <div class="col-md-4"><a href="{{ route('transactions.report') }}" class="btn btn-primary">
                        <i class="bi bi-file-earmark-text"></i> Transactions Report
                    </a></div>
                    <div class="col-md-4"><a href="{{ route('dashboard.index') }}" class="btn btn-success">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a></div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            @if($transactions->count())
                <div class="mt-4">
                    <table class="table table-bordered">
                        <tr>
                            <th>Total Received</th>
                            <td class="text-success fw-bold">{{ number_format($totalIncome, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Expense</th>
                            <td class="text-danger fw-bold">{{ number_format($totalExpense, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Balance</th>
                            <td class="fw-bold {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($balance, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Filter Form -->
<form method="GET" action="{{ route('transactions.index') }}" class="row g-3 mb-4">
    <div class="col-md-3">
        <label>Start Date</label>
        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>
    <div class="col-md-3">
        <label>End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
    </div>
    <div class="col-md-3">
        <label>Type</label>
        <select name="type" class="form-control">
            <option value="">All</option>
            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
        </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary ms-2">Clear</a>
    </div>
</form>




<table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Title</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $t)
        <tr>
            <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('m/d/Y') }}</td>
            <td>
                <span class="badge bg-{{ $t->type == 'income' ? 'success' : 'danger' }}">
                    {{ ucfirst($t->type) }}
                </span>
            </td>
            <td>{{ $t->title }}</td>
            <td>{{ number_format($t->amount, 2) }}</td>
            <td>
                <!-- Edit Button -->
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTransactionModal{{ $t->id }}">
                    Edit
                </button>

                <!-- Delete Form -->
                <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this?')">Del</button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editTransactionModal{{ $t->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('transactions.update', $t->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Date</label>
                                <input type="date" name="transaction_date" class="form-control" value="{{ $t->transaction_date }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="income" {{ $t->type == 'income' ? 'selected' : '' }}>Income</option>
                                    <option value="expense" {{ $t->type == 'expense' ? 'selected' : '' }}>Expense</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $t->title }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Amount</label>
                                <input type="number" name="amount" class="form-control" step="0.01" value="{{ $t->amount }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <tr><td colspan="5" class="text-center">No transactions found.</td></tr>
        @endforelse
    </tbody>
</table>


@endsection
