<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip Report</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        thead { background-color: #f5f5f5; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <div class="center">
        <h3>Company Name</h3>
        <p>Payslip Report - Generated {{ now()->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Month</th>
                <th class="text-end">Salary</th>
                <th class="text-end">Allowance</th>
                <th class="text-end">Deduction</th>
                <th class="text-end">Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($payslips as $p)
                @php $total += $p->net_salary; @endphp
                <tr>
                    <td>{{ $p->employee->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->month)->format('F Y') }}</td>
                    <td class="text-end">{{ number_format($p->salary, 2) }}</td>
                    <td class="text-end">{{ number_format($p->allowance ?? 0, 2) }}</td>
                    <td class="text-end">{{ number_format($p->deduction ?? 0, 2) }}</td>
                    <td class="text-end">{{ number_format($p->net_salary, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">Grand Total</th>
                <th class="text-end">{{ number_format($total, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
