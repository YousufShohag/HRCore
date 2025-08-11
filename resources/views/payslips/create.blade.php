@extends('layouts.app')

@section('content')
    <h2>Create Payslip</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


  {{-- <form action="{{ route('payslips.store') }}" method="POST"> --}}
<form action="{{ route('payslips.store') }}" method="POST" enctype="multipart/form-data">

    @csrf

    <div class="mb-3">
        <label>Employee</label>
        <select name="employee_id" id="employeeSelect" class="form-control" required>
            <option value="">-- Select Employee --</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" data-salary="{{ $emp->basic_salary }}">{{ $emp->name }} ({{ $emp->designation }})</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Month</label>
        <input type="month" name="month" class="form-control" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Total Working Hours</label>
            <input type="number" name="total_hours" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Average Daily Hours</label>
            <input type="number" name="average_hours" step="0.1" class="form-control" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label><strong>Absent Days</strong></label>  <!-- ✅ NEW -->
            <input type="number" name="absent_days" class="form-control" min="0" value="0">
        </div>
        <div class="col-md-6 mb-3">
            <label><strong>Sick Leave Days</strong></label>  <!-- ✅ NEW -->
            <input type="number" name="sick_leave_days" class="form-control" min="0" value="0">
        </div>
    </div>

    <div class="mb-3">
        <label>Salary</label>
        <input type="number" id="salaryInput" name="salary" class="form-control" readonly required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Allowance</label>
            <input type="number" name="allowance" class="form-control" value="0">
        </div>
        <div class="col-md-6 mb-3">
            <label>Deduction</label>
            <input type="number" name="deduction" class="form-control" value="0">
        </div>
    </div>

    <div class="mb-3">
    <label>Total Worked Days</label>
    <input type="number" name="total_worked_days" class="form-control" required>
</div>

<div class="mb-3">
    <label>Attach Document</label>
    <input type="file" name="document" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
</div>

<div class="mb-3">
    <label>Notes</label>
    <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
</div>

    <button class="btn btn-success">Create Payslip</button>
</form>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const employeeSelect = document.getElementById('employeeSelect');
        const salaryInput = document.getElementById('salaryInput');

        employeeSelect.addEventListener('change', function () {
            const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
            const salary = selectedOption.getAttribute('data-salary');
            salaryInput.value = salary || '';
        });
    });
</script>
@endsection
