@extends('layouts.app')

@section('content')
<h2>Add Monthly Work Days</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('monthly_work_days.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="month" class="form-label">Month (Select first day of month)</label>
        <input type="month" id="month" name="month" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="working_days" class="form-label">Total Working Days in Month</label>
        <input type="number" id="working_days" name="working_days" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label for="govt_holidays" class="form-label">Government Holidays</label>
        <input type="number" id="govt_holidays" name="govt_holidays" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label for="fridays" class="form-label">Fridays</label>
        <input type="number" id="fridays" name="fridays" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label for="special_holidays" class="form-label">Special Holidays</label>
        <input type="number" id="special_holidays" name="special_holidays" class="form-control" min="0" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection
