@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Payslip Dashboard</h2>

    <!-- Filters -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="year" class="form-label">Year</label>
            <select name="year" id="year" class="form-select">
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="month" class="form-label">Month</label>
            <select name="month" id="month" class="form-select">
                <option value="">All Months</option>
                @foreach($months as $key => $value)
                    <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="department" class="form-label">Department</label>
            <select name="department" id="department" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ $selectedDepartment == $dept ? 'selected' : '' }}>
                        {{ $dept }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Stats -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card border-success h-100">
                <div class="card-body text-success">
                    <h5 class="card-title">Total Payslips Issued</h5>
                    <p class="card-text fs-4">{{ $totalPayslips }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-danger h-100">
                <div class="card-body text-danger">
                    <h5 class="card-title">Avg. Absent Days</h5>
                    <p class="card-text fs-4">{{ $averageAbsents }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-warning h-100">
                <div class="card-body text-warning">
                    <h5 class="card-title">Avg. Sick Leaves</h5>
                    <p class="card-text fs-4">{{ $averageSickLeaves }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional: Add table of payslips -->
    <div class="table-responsive mt-4">
        <h5>Payslip Records</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Employee</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Month</th>
                    <th>Absent</th>
                    <th>Sick Leave</th>
                    <th>Net Salary (৳)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payslips as $payslip)
                    <tr>
                        <td>{{ $payslip->employee->name }}</td>
                        <td>{{ $payslip->employee->designation }}</td>
                        <td>{{ $payslip->employee->department }}</td>
                        <td>{{ \Carbon\Carbon::parse($payslip->month)->format('F Y') }}</td>
                        <td>{{ $payslip->absent_days }}</td>
                        <td>{{ $payslip->sick_leave_days }}</td>
                        <td>{{ number_format($payslip->net_salary, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No payslips found for selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
