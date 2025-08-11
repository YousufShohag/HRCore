<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Payslip Report - {{ $formattedMonth }}</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Designation</th>
                <th>Department</th>
                <th>Basic Salary</th>
                <th>Prorated Salary</th>
                <th>Joining Date</th>
                <th>Month</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payslips as $index => $payslip)
                @php
                    $joiningDate = \Carbon\Carbon::parse($payslip->employee->joining_date);
                    $monthStart = \Carbon\Carbon::parse($payslip->month)->startOfMonth();
                    $monthEnd = \Carbon\Carbon::parse($payslip->month)->endOfMonth();
                    $daysInMonth = $monthStart->daysInMonth;

                    if ($joiningDate->between($monthStart, $monthEnd)) {
                        $workedDays = $daysInMonth - $joiningDate->day + 1;
                        $proratedSalary = ($payslip->basic_salary / $daysInMonth) * $workedDays;
                    } else {
                        $proratedSalary = $payslip->basic_salary;
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payslip->employee->name }}</td>
                    <td>{{ $payslip->employee->designation }}</td>
                    <td>{{ $payslip->employee->department }}</td>
                    <td>{{ number_format($payslip->basic_salary, 2) }}</td>
                    <td>{{ number_format($proratedSalary, 2) }}</td>
                    <td>{{ $joiningDate->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($payslip->month)->format('F Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
