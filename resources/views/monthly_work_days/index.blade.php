@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📅 Monthly Work Days</h2>
        <div class="d-flex gap-2">
            <a href="#" onclick="window.print()" class="btn btn-outline-secondary no-print">
                <i class="bi bi-printer"></i> Print
            </a>
            <a href="{{ route('monthly_work_days.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-dark">
                <i class="bi bi-arrow-left-circle"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th>Month</th>
                            <th>Total<br>Working Days</th>
                            <th>Govt.<br>Holidays</th>
                            <th>Fridays</th>
                            <th>Special<br>Holidays</th>
                            <th>Total Effective<br>Working Days</th>
                            <th>Total<br>Working Hour</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @forelse($months as $month)
                            <tr>
                                <td class="fw-medium text-nowrap">{{ \Carbon\Carbon::parse($month->month)->format('F Y') }}</td>
                                <td>{{ $month->working_days }}</td>
                                <td>{{ $month->govt_holidays }}</td>
                                <td>{{ $month->fridays }}</td>
                                <td>{{ $month->special_holidays }}</td>
                                <td class="fw-semibold">{{ $month->total_working_days }}</td>
                                <td class="text-success fw-semibold">{{ $month->total_hours }} hrs</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
