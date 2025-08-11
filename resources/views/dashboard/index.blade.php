@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">Payslip Dashboard</h2>

    <!-- Quick Actions -->
    <div class="mb-3 d-flex flex-wrap gap-2">
        {{-- <a href="{{ route('payslips.create') }}" class="btn btn-success">➕ Add Payslip</a>
        <a href="{{ route('employees.create') }}" class="btn btn-success">➕ Add Employee</a>
        <a href="{{ route('monthly_work_days.create') }}" class="btn btn-success">➕ Add Month</a> --}}
        <a href="{{ route('payslips.index') }}" class="btn btn-outline-primary">📄 All Payslips</a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-primary">👥 All Employees</a>
        <a href="{{ route('monthly_work_days.index') }}" class="btn btn-outline-primary">🗓️ All Months</a>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline-primary">💰 Expences </a>
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary">💳 Transactions</a>
        <a href="{{ route('leaves.index') }}" class="btn btn-outline-primary">💳 Leave</a>
    </div>



    <!-- Filters -->
    <form method="GET" action="{{ route('dashboard.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="year" class="form-label">Year</label>
            <select name="year" id="year" class="form-select">
                <option value="">All</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="month" class="form-label">Month</label>
            <select name="month" id="month" class="form-select">
                <option value="">All</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label for="department" class="form-label">Department</label>
            <select name="department" id="department" class="form-select">
                <option value="">All</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Summary -->
    {{-- <div class="alert alert-info">
        <strong>Total Payslips Issued:</strong> {{ $issuedCount }}
    </div> --}}

    <div class="row">
        <div class="col-md-8">
            <!-- Payslip Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Month</th>
                            <th> Salary</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payslips as $payslip)
                            <tr>
                                <td>{{ $payslip->employee->name }}</td>
                                <td>{{ $payslip->employee->designation }}</td>
                                <td>{{ $payslip->employee->department ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($payslip->month)->format('F Y') }}</td>
                                <td>{{ number_format($payslip->net_salary, 2) }}</td>
                                <td>
                                    <a href="{{ route('payslips.show', $payslip->id) }}" class="btn btn-sm btn-info" target="_blank">👁️</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No payslips found for selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
            </div>

            <!-- Chart -->
            <div class="mb-4" style="height: 300px;">
                <canvas id="monthlyPayslipChart"></canvas>
            </div>
        </div>

        <!-- Right Side Calendar and Stats -->
        <div class="col-md-4">
            <!-- Calendar Widget -->
            <div class="card mb-4">
                <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                    <span>📅 Calendar</span>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="changeMonth(-1)">◀</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="changeMonth(1)">▶</button>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div id="calendar-title" class="text-center fw-bold mb-2"></div>
                    <div id="calendar-widget" class="calendar-widget"></div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3">
                <div class="col-6">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Employees</h5>
                            <p class="card-text fs-3">{{ $totalEmployees }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-6">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">Payslips Issued</h5>
                            <p class="card-text fs-3">{{ $issuedCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card bg-info text-white shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title">Net Balance</h5>
                            <p class="card-text fs-3">{{ number_format($netBalance, 2) }} BDT</p>
                        </div>
                    </div>
                </div>


                <div class="container mt-4">
                    <div class="row">
                        <div class="expences">
                            <h3>Latest Expenses</h3>
                            {{-- <a href="{{ route('expenses.index') }}" class="btn btn-outline-primary">📄 All Expenses</a> --}}
                            <ul>
                            @foreach($expenses as $expense)
                                <li>{{ $expense->title }} - {{ $expense->amount }} ({{ $expense->expense_date }})</li>
                            @endforeach
                            <a href="{{ route('expenses.index') }}" class="text-right">See more </a>
                            </ul>
                            
                        </div>
                    </div>
                </div>

                <div class="container mt-4">
                    <div class="row">
                        <div class="expences">
                            <h3>Latest Transections</h3>
                            {{-- <a href="{{ route('expenses.index') }}" class="btn btn-outline-primary">📄 All Expenses</a> --}}
                            <ul>
                            @foreach($transactions as $transaction)
                                <li>{{ $transaction->title }} - {{ $transaction->amount }} ({{ $transaction->transaction_date }})</li>
                            @endforeach
                            <a href="{{ route('transactions.index') }}" class="text-right">See more </a>
                            </ul>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
{{-- SHOW HERE TRANSECTION SUMMARY --}}
{{-- <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="container mt-4">
    <h2 class="mb-4">Total Transections</h2>
    <div class="row">

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h4>Total Income</h4>
                    <h3>${{ number_format($totalIncome, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h4>Total Expense</h4>
                    <h3>${{ number_format($totalExpense, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h4>Net Balance</h4>
                    <h3>${{ number_format($netBalance, 2) }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-4">
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">Add Transaction</a>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">View Transactions</a>
        <a href="{{ route('transactions.report') }}" class="btn btn-dark">Date-to-Date Report</a>
    </div>
</div>
        </div>
    </div>
</div> --}}

@endsection

@section('scripts')
<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyPayslipChart').getContext('2d');
    const monthlyPayslipChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Payslips Issued',
                data: @json($counts),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0,
                    stepSize: 1,
                    title: { display: true, text: 'Number of Payslips' }
                }
            },
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
</script>

<!-- Simple Calendar -->
<script>
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

function renderSimpleCalendar(month, year) {
    const calendar = document.getElementById('calendar-widget');
    const title = document.getElementById('calendar-title');
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();

    title.textContent = new Date(year, month).toLocaleString('default', { month: 'long', year: 'numeric' });

    let html = '<div class="calendar-grid">';
    dayNames.forEach(d => html += `<div class="calendar-day-name">${d}</div>`);

    for (let i = 0; i < firstDay; i++) {
        html += `<div class="calendar-day empty"></div>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = today.getDate() === day && today.getMonth() === month && today.getFullYear() === year;
        html += `<div class="calendar-day${isToday ? ' today' : ''}">${day}</div>`;
    }

    html += '</div>';
    calendar.innerHTML = html;
}

function changeMonth(offset) {
    currentMonth += offset;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    } else if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderSimpleCalendar(currentMonth, currentYear);
}

document.addEventListener('DOMContentLoaded', () => {
    renderSimpleCalendar(currentMonth, currentYear);
});
</script>

<style>
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    font-size: 13px;
}
.calendar-day-name {
    font-weight: bold;
    text-align: center;
    background: #f1f1f1;
}
.calendar-day {
    padding: 6px;
    text-align: center;
    border-radius: 4px;
    background-color: #f0f0f0;
}
.calendar-day.today {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}
.calendar-day.empty {
    background: transparent;
}
</style>
@endsection
