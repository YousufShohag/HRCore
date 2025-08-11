<!DOCTYPE html>
<html>
<head>
    <title>Payslip Report - {{ $rangeLabel }}</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; border: 1px solid #ccc; }
        h3 { text-align: center; }
    </style>
</head>
<body>
    <h3>Payslip Report ({{ $rangeLabel }})</h3>

    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Employee</th>
                <th>Designation</th>
                <th>Basic</th>
                <th>Days</th>
                <th>Hours</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payslips as $payslip)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payslip->month)->format('F Y') }}</td>
                    <td>{{ $payslip->employee->name }}</td>
                    <td>{{ $payslip->employee->designation }}</td>
                    <td>{{ $payslip->basic_salary }}</td>
                    <td>{{ $payslip->working_days }}</td>
                    <td>{{ $payslip->total_hours }}</td>
                    <td>{{ $payslip->final_salary }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
