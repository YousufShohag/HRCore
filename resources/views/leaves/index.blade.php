@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between mb-3">
        <h3>Employee Leave Management</h3>
        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>


    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <!-- Create form -->
    <div class="card mb-4">
        <div class="card-header">Request Leave</div>
        <div class="card-body">
            <form action="{{ route('leaves.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Employee</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">-- select employee --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} ({{ $emp->designation }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Reason (optional)</label>
                    <input type="text" name="reason" class="form-control" value="{{ old('reason') }}">
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-success">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="employee" value="{{ request('employee') }}" class="form-control" placeholder="Search employee name...">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="year" class="form-select">
                <option value="">All years</option>
                @foreach(range(now()->year, now()->year - 5) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- List -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Period</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                        <tr>
                            <td>{{ $loop->iteration + ($leaves->currentPage()-1)*$leaves->perPage() }}</td>
                            <td>
                                {{ $leave->employee->name }}<br>
                                <small class="text-muted">{{ $leave->employee->designation ?? '' }}</small>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                                — {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                            </td>
                            <td>{{ $leave->days }}</td>
                            <td>{{ $leave->reason ?? '—' }}</td>
                            <td>
                                @if($leave->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($leave->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $leave->created_at->diffForHumans() }}</td>
                            <td class="text-end">
                                @if($leave->status == 'pending')
                                    <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" onclick="return confirm('Approve?')">Approve</button>
                                    </form>
                                    <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Reject?')">Reject</button>
                                    </form>
                                    <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                @endif
                                <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">No leave requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
