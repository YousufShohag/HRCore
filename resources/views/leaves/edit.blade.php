@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h3>Edit Leave Request</h3>

    @if($errors->any())
        <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('leaves.update', $leave) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Employee</label>
            <select class="form-select" disabled>
                <option>{{ $leave->employee->name }}</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Reason</label>
            <input type="text" name="reason" value="{{ old('reason', $leave->reason) }}" class="form-control">
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
