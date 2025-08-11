@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Clock In / Clock Out Report</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('attendance.report') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <select name="employee_id" class="form-select">
                <option value="">-- Select Employee --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>
        <div class="col-md-2 d-flex">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    @if($attendances->count())
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Clock In</th>
                    <th>Clock In Photo</th>
                    <th>Clock Out</th>
                    <th>Clock Out Photo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $att)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($att->date)->format('m-d-Y') }}</td>
                    <td>{{ $att->employee->name }}</td>
                    <td>{{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('H:i:s') : 'N/A' }}</td>
                    <td>
                        @if($att->clock_in_photo)
                            <img src="{{ asset('storage/' . $att->clock_in_photo) }}" alt="Clock In" width="80" />
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('H:i:s') : 'N/A' }}</td>
                    <td>
                        @if($att->clock_out_photo)
                            <img src="{{ asset('storage/' . $att->clock_out_photo) }}" alt="Clock Out" width="80" />
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $attendances->withQueryString()->links() }}
    @else
        <div class="alert alert-info">No attendance records found.</div>
    @endif
</div>
@endsection
