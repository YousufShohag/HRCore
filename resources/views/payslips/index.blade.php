@extends('layouts.app')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Payslip List</h2>
        <div class="d-flex gap-2">
            <a href="#" onclick="window.print()" class="btn btn-outline-secondary no-print"><i class="bi bi-printer"></i> Print</a>
            
            <a href="{{ route('payslips.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add New</a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-dark"><i class="bi bi-arrow-left-circle"></i> Back</a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('payslips.index') }}" class="row g-2 mb-4">
        <div class="col-md-3">
            <input type="text" name="employee_name" class="form-control" value="{{ request('employee_name') }}" placeholder="Search by Name">
        </div>
        <div class="col-md-2">
            <select name="month" class="form-select">
                <option value="">All Months</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="min_salary" class="form-control" placeholder="Min Salary" value="{{ request('min_salary') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_salary" class="form-control" placeholder="Max Salary" value="{{ request('max_salary') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary w-50"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('payslips.index') }}" class="btn btn-secondary w-50"><i class="bi bi-arrow-clockwise"></i> Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-left">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>Employee</th>
                    <th>Month</th>
                    <th>Net Salary</th>
                    <th class="no-print">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payslips as $payslip)
                    <tr>
                         <td>
                            @if($payslip->employee->image)
                                <img src="{{ asset('storage/' . $payslip->employee->image) }}" alt="Image" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $payslip->employee->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($payslip->month)->format('F Y') }}</td>
                        <td>৳{{ number_format($payslip->net_salary, 2) }}</td>
                        <td class="no-print d-flex gap-1 justify-content-center">
                            <a href="{{ route('payslips.show', $payslip->id) }}" class="btn btn-sm btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                            <button 
                                class="btn btn-sm btn-outline-warning edit-btn" 
                                title="Edit" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editPayslipModal"
                                data-id="{{ $payslip->id }}"
                                data-employee="{{ $payslip->employee->name }}"
                                data-month="{{ \Carbon\Carbon::parse($payslip->month)->format('Y-m') }}"
                                data-salary="{{ $payslip->salary }}"
                                data-allowance="{{ $payslip->allowance ?? 0 }}"
                                data-deduction="{{ $payslip->deduction ?? 0 }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('payslips.destroy', $payslip->id) }}" method="POST" onsubmit="return confirm('Delete this payslip?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash3"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPayslipModal" tabindex="-1" aria-labelledby="editPayslipModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editPayslipForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          {{-- <h5 class="modal-title" id="editPayslipModalLabel">Edit Payslip</h5> --}}
          <h5 class="modal-title" id="editPayslipModalLabel">Edit Payslip for <span id="modal-employee-name" class="text-white fw-bold"></span></h5>

          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Month</label>
                <input type="month" name="month" id="editMonth" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Salary</label>
                <input type="number" name="salary" id="editSalary" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Allowance</label>
                <input type="number" name="allowance" id="editAllowance" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Deduction</label>
                <input type="number" name="deduction" id="editDeduction" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">💾 Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const employee = button.dataset.employee;
                const month = button.dataset.month;
                const salary = button.dataset.salary;
                const allowance = button.dataset.allowance;
                const deduction = button.dataset.deduction;

                document.getElementById('editPayslipForm').action = `/payslips/${id}`;
                document.getElementById('editMonth').value = month;
                document.getElementById('editSalary').value = salary;
                document.getElementById('editAllowance').value = allowance;
                document.getElementById('editDeduction').value = deduction;

                  // Set employee name in modal title
                document.getElementById('modal-employee-name').textContent = employee;
            });
        });
    });
</script>
@endsection
