@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Add New Member</h2>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left-circle"></i> All Employees
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Whoops! There were some problems with your input:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter full name" required value="{{ old('name') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="designation" class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
                        <input type="text" id="designation" name="designation" class="form-control" placeholder="e.g., Software Engineer" required value="{{ old('designation') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="department" class="form-label fw-semibold">Department</label>
                        <input type="text" id="department" name="department" class="form-control" placeholder="Optional" value="{{ old('department') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="basic_salary" class="form-label fw-semibold">Basic Salary (৳) <span class="text-danger">*</span></label>
                        <input type="number" id="basic_salary" name="basic_salary" step="0.01" min="0" class="form-control" placeholder="e.g., 25000.00" required value="{{ old('basic_salary') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="dob" class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="form-control" value="{{ old('dob') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="bank_name" class="form-label fw-semibold">Bank Name</label>
                        <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="e.g., City Bank" value="{{ old('bank_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="bank_account_number" class="form-label fw-semibold">Bank Account Number</label>
                        <input type="text" id="bank_account_number" name="bank_account_number" class="form-control" placeholder="Account number" value="{{ old('bank_account_number') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="image" class="form-label fw-semibold">Profile Image</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-6">
                        <label for="document" class="form-label fw-semibold">Document (PDF, Word, etc.)</label>
                        <input type="file" id="document" name="document" class="form-control" accept=".pdf,.doc,.docx,.txt">
                    </div>

                    <div class="col-md-6">
                        <label for="join_date" class="form-label fw-semibold">Join Date</label>
                        <input type="date" id="join_date" name="join_date" class="form-control" value="{{ old('join_date') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="resign_date" class="form-label fw-semibold">Resign Date</label>
                        <input type="date" id="resign_date" name="resign_date" class="form-control" value="{{ old('resign_date') }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Focus Name --}}
<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('name').focus();
    });
</script>
@endsection
