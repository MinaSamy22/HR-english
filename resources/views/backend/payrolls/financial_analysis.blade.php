@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('h_payroll.financial_analysis') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('dashboard.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/payroll') }}">{{ __('h_payroll.payroll_list') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_payroll.financial_analysis') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <section class="content">
        <div class="container-fluid">
            <div class="card" style="background-color: rgba(255, 255, 255, 0.9);">
                <div class="card-header">
                    <h3 class="card-title">{{ __('h_payroll.filters') }}</h3>
                </div>
                <form method="get" action="">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>{{ __('h_payroll.year') }}</label>
                                <select name="year" class="form-control">
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 5;
                                        $endYear = $currentYear + 1;
                                    @endphp
                                    @for ($i = $endYear; $i >= $startYear; $i--)
                                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>{{ __('h_payroll.month') }}</label>
                                <select name="month" class="form-control">
                                    <option value="">{{ __('h_payroll.all_months') }}</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                                <div class="form-group col-md-3">
                                    <label>{{ __('h_employee.branch') }}</label>
                                    <select name="filter_branch_id" class="form-control">
                                        <option value="">{{ __('h_employee.all') }}</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group col-md-3">
                                <label>{{ __('h_payroll.payroll_type') }}</label>
                                <select name="payroll_type" class="form-control">
                                    <option value="">{{ __('h_payroll.all_types') }}</option>
                                    <option value="monthly" {{ $payrollType == 'monthly' ? 'selected' : '' }}>
                                        {{ __('h_payroll.monthly') }}
                                    </option>
                                    <option value="weekly" {{ $payrollType == 'weekly' ? 'selected' : '' }}>
                                        {{ __('h_payroll.weekly') }}
                                    </option>
                                    <option value="daily" {{ $payrollType == 'daily' ? 'selected' : '' }}>
                                        {{ __('h_payroll.daily') }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-filter"></i> {{ __('h_payroll.apply_filters') }}
                                </button>
                                <a href="{{ url('admin/financial-analysis') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> {{ __('h_payroll.reset') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Summary + Payroll Type + Composition -->
            <div class="row">
                <!-- Left Section (2 summary cards + payroll type analysis) -->
                <div class="col-lg-6">
                    <div class="row">
                        <!-- Total Net Payroll -->
                        <div class="col-md-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ number_format($summary['total_net_pay'], 2) }}</h3>
                                    <p>{{ __('h_payroll.total_net_payroll') }}</p>
                                    @if(isset($growthRates['payroll_growth']))
                                        <small>
                                            <i class="fas fa-arrow-{{ $growthRates['payroll_growth'] >= 0 ? 'up' : 'down' }}"></i>
                                            {{ number_format(abs($growthRates['payroll_growth']), 1) }}% {{ __('h_payroll.vs_previous') }}
                                        </small>
                                    @endif
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Employees -->
                        <div class="col-md-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $summary['total_employees'] }}</h3>
                                    <p>{{ __('h_payroll.total_employees') }}</p>
                                    @if(isset($growthRates['employee_growth']))
                                        <small>
                                            <i class="fas fa-arrow-{{ $growthRates['employee_growth'] >= 0 ? 'up' : 'down' }}"></i>
                                            {{ number_format(abs($growthRates['employee_growth']), 1) }}% {{ __('h_payroll.vs_previous') }}
                                        </small>
                                    @endif
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Payroll Type Analysis (moved here) -->
                        <div class="col-12">
                            <div class="card" style="background-color: rgba(255,255,255,0.9);">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('h_payroll.payroll_type_analysis') }}</h3>
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{ __('h_payroll.type') }}</th>
                                                    <th>{{ __('h_payroll.employees') }}</th>
                                                    <th>{{ __('h_payroll.avg_salary') }}</th>
                                                    <th>{{ __('h_payroll.total_cost') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payrollTypeAnalysis as $type)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-pill
                                                                @if($type->payroll_type == 'daily') badge-warning
                                                                @elseif($type->payroll_type == 'weekly') badge-info
                                                                @elseif($type->payroll_type == 'monthly') badge-primary
                                                                @endif">
                                                                {{ __('h_payroll.payroll_types.' . strtolower($type->payroll_type)) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $type->employee_count }}</td>
                                                        <td>{{ number_format($type->avg_salary, 2) }}</td>
                                                        <td>{{ number_format($type->total_net_pay, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section (Composition) -->
                <div class="col-lg-6">
                    <div class="card" style="background-color: rgba(255,255,255,0.9);">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('h_payroll.payroll_composition') }}</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>{{ __('h_payroll.basic_salary') }}</strong></td>
                                    <td class="text-right">{{ number_format($summary['total_basic_salary'], 2) }}</td>
                                    <td class="text-right">100%</td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>{{ __('h_payroll.bonuses') }}</strong></td>
                                    <td class="text-right">+{{ number_format($summary['total_bonuses'], 2) }}</td>
                                    <td class="text-right">+{{ number_format($summary['bonus_percentage'], 1) }}%</td>
                                </tr>
                                <tr class="table-danger">
                                    <td><strong>{{ __('h_payroll.deductions') }}</strong></td>
                                    <td class="text-right">-{{ number_format($summary['total_deductions'] + $summary['total_attendance_deductions'], 2) }}</td>
                                    <td class="text-right">-{{ number_format($summary['deduction_percentage'], 1) }}%</td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>{{ __('h_payroll.taxes_insurance') }}</strong></td>
                                    <td class="text-right">-{{ number_format($summary['total_taxes'], 2) }}</td>
                                    <td class="text-right">-{{ number_format($summary['tax_percentage'], 1) }}%</td>
                                </tr>
                                <tr class="table-info">
                                    <td><strong>{{ __('h_payroll.net_pay') }}</strong></td>
                                    <td class="text-right">{{ number_format($summary['total_net_pay'], 2) }}</td>
                                    <td class="text-right">{{ number_format(($summary['total_net_pay'] / $summary['total_basic_salary']) * 100, 1) }}%</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Cost Breakdown & Deduction Analysis -->
                <div class="row">
                    <!-- Cost Breakdown -->
                    <div class="col-4">
                        <div class="card" style="background-color: rgba(255, 255, 255, 0.9);">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_payroll.cost_breakdown') }}</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="costBreakdownChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Deduction Analysis -->
                    <div class="col-4">
                        <div class="card" style="background-color: rgba(255, 255, 255, 0.9);">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_payroll.deduction_analysis') }}</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="deductionChart" height="250"></canvas>
                                <div class="mt-3">
                                    <p><strong>{{ __('h_payroll.attendance_deductions') }}:</strong> {{ number_format($deductionAnalysis['attendance_deductions'], 2) }}</p>
                                    <p><strong>{{ __('h_payroll.other_deductions') }}:</strong> {{ number_format($deductionAnalysis['other_deductions'], 2) }}</p>
                                    <p><strong>{{ __('h_payroll.employees_with_attendance_deductions') }}:</strong> {{ $deductionAnalysis['employees_with_attendance_deductions'] }}</p>
                                    <p><strong>{{ __('h_payroll.employees_with_other_deductions') }}:</strong> {{ $deductionAnalysis['employees_with_other_deductions'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trend -->
                <div class="card" style="background-color: rgba(255, 255, 255, 0.9);">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('h_payroll.monthly_trend') }} ({{ __('h_payroll.last_12_months') }})</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyTrendChart" height="80"></canvas>
                    </div>
                </div>


                @if($topEmployees->count() > 0)
                    <div class="card mt-4" style="background-color: rgba(255, 255, 255, 0.95);">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="fas fa-trophy text-warning"></i>
                                {{ __('Top Performing Employees This Month') }}
                            </h3>
                            <span class="badge badge-success p-2">
                                {{ $month ? date('F', mktime(0,0,0,$month,1)) . ' ' . $year : $year }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Employee Name') }}</th>
                                            <th>{{ __('Basic Salary') }}</th>
                                            <th>{{ __('Bonus') }}</th>
                                            <th>{{ __('Net Pay') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topEmployees as $index => $emp)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $emp->employee->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($emp->total_basic, 2) }}</td>
                                                <td>{{ number_format($emp->total_bonus, 2) }}</td>
                                                <td>
                                                    <strong class="text-success">{{ number_format($emp->total_net_pay, 2) }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    // Cost Breakdown Chart
    const costCtx = document.getElementById('costBreakdownChart').getContext('2d');
    new Chart(costCtx, {
        type: 'doughnut',
        data: {
            labels: [
                '{{ __('h_payroll.basic_salary') }}',
                '{{ __('h_payroll.bonuses') }}',
                '{{ __('h_payroll.deductions') }}',
                '{{ __('h_payroll.taxes_insurance') }}'
            ],
            datasets: [{
                data: [
                    {{ $summary['total_basic_salary'] }},
                    {{ $summary['total_bonuses'] }},
                    {{ $summary['total_deductions'] + $summary['total_attendance_deductions'] }},
                    {{ $summary['total_taxes'] }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 206, 86, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(context.parsed);
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Monthly Trend Chart
    const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    const monthlyData = @json($monthlyTrend);

    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const labels = monthlyData.map(item => monthNames[item.month - 1] + ' ' + item.year).reverse();

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: '{{ __('h_payroll.net_pay') }}',
                    data: monthlyData.map(item => item.total_net_pay).reverse(),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '{{ __('h_payroll.basic_salary') }}',
                    data: monthlyData.map(item => item.total_salary).reverse(),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: '{{ __('h_payroll.deductions') }}',
                    data: monthlyData.map(item => item.total_deductions).reverse(),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('en-US', {
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value);
                        }
                    }
                }
            }
        }
    });

    // Deduction Analysis Chart
    const deductionCtx = document.getElementById('deductionChart').getContext('2d');
    new Chart(deductionCtx, {
        type: 'pie',
        data: {
            labels: [
                '{{ __('h_payroll.attendance_deductions') }}',
                '{{ __('h_payroll.other_deductions') }}'
            ],
            datasets: [{
                data: [
                    {{ $deductionAnalysis['attendance_deductions'] }},
                    {{ $deductionAnalysis['other_deductions'] }}
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(context.parsed);
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
