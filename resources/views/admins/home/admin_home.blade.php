@extends('admins/layouts.admin-layout')

@section('title', __('Admin-Interface.page_title'))

@section('page_title', __('Admin-Interface.control_panel'))

@section('content')
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card companies">
            <div class="icon">
                <i class="fas fa-building"></i>
            </div>
            <h3>{{ __('Admin-Interface.total_companies') }}</h3>
            <div class="number" id="companiesCount">{{ $totalCompanies }}</div>
        </div>

        <div class="stat-card employees">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>{{ __('Admin-Interface.total_employees') }}</h3>
            <div class="number" id="employeesCount">{{ $getEmployeeCount }}</div>
        </div>

        <div class="stat-card admins">
            <div class="icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h3>{{ __('Admin-Interface.total_admins') }}</h3>
            <div class="number" id="adminsCount">{{ $totalAdmins }}</div>
        </div>

        <div class="stat-card hr">
            <div class="icon" style="background-color: #4a5568;">
                <i class="fas fa-user-tie" style="color: #ffffff;"></i>
            </div>
            <h3>{{ __('Admin-Interface.total_hrs') }}</h3>
            <div class="number" id="hrsCount">{{ $totalHRs }}</div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Chart Container -->
        <div class="chart-container">
            <h2>
                <i class="fas fa-chart-pie" style="margin-right: 6px; color: #667eea;"></i>
                {{ __('Admin-Interface.employees_distribution') }}
            </h2>
            <div class="chart-wrapper">
                <canvas id="employeeChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>
                <i class="fas fa-bolt" style="margin-right: 6px; color: #667eea;"></i>
                {{ __('Admin-Interface.quick_actions') }}
            </h2>

            <div class="action-group">
                <h3>
                    <i class="fas fa-building" style="margin-right: 4px;"></i>
                    {{ __('Admin-Interface.companies_management') }}
                </h3>
                <div class="action-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="fas fa-building"></i>
                        {{ __('Admin-Interface.add_company') }}
                    </a>
                    <a href="{{ route('admin.companies') }}" class="btn btn-danger">
                        <i class="fas fa-building"></i>
                        {{ __('Admin-Interface.manage_companies') }}
                    </a>
                </div>
            </div>

            <div class="action-group">
                <h3>
                    <i class="fas fa-user-shield" style="margin-right: 4px;"></i>
                    {{ __('Admin-Interface.admins_management') }}
                </h3>
                <div class="action-buttons">
                    <a href="{{ route('admin.register') }}" class="btn btn-success">
                        <i class="fas fa-user-shield"></i>
                        {{ __('Admin-Interface.add_admin') }}
                    </a>
                    <a href="{{ route('admin.admins.manage') }}" class="btn btn-danger">
                        <i class="fas fa-user-cog"></i>
                        {{ __('Admin-Interface.manage_admins') }}
                    </a>   
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="charts-row charts-row--full">
        <!-- Line Chart: System Growth Trend -->
        <div class="chart-container">
            <div class="chart-header-row">
                <h2>
                    <i class="fas fa-chart-line" style="margin-right: 6px; color: #43e97b;"></i>
                    {{ __("Admin-Interface.system_growth_trend") }} ({{ $selectedYear }})
                </h2>
                <form action="{{ route('admin.landing') }}" method="GET" class="year-filter-form">
                    <label for="yearSelect" class="year-filter-label">
                        <i class="fas fa-calendar-alt"></i> {{ __("Admin-Interface.select_year") }}:
                    </label>
                    <select name="year" id="yearSelect" class="year-filter-select" onchange="this.form.submit()">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="chart-wrapper" style="height: 320px;">
                <canvas id="growthLineChart"></canvas>
            </div>
            <div class="growth-legend-row" id="growthLegend"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Load data from Blade (Laravel backend)
        const dashboardData = {
            companies: @json($totalCompanies),
            employees: @json($getEmployeeCount),
            admins: @json($totalAdmins),
            hrs: @json($totalHRs),
            employeesByCompany: @json($employeesByCompany ?? []),
            monthlyGrowth: @json($monthlyGrowth ?? ['labels' => [], 'employees' => [], 'companies' => []])
        };

        // Update statistics
        function updateStats() {
            document.getElementById('companiesCount').textContent = dashboardData.companies;
            document.getElementById('employeesCount').textContent = dashboardData.employees;
            document.getElementById('adminsCount').textContent = dashboardData.admins;
            document.getElementById('hrsCount').textContent = dashboardData.hrs;
        }

        // Create bar chart (employees per company)
        function createChart() {
            const ctx = document.getElementById('employeeChart').getContext('2d');
            const isRTL = document.documentElement.dir === 'rtl';

            const colors = [
                '#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#43e97b',
                '#f9ca24', '#c0392b', '#6ab04c', '#30336b'
            ];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dashboardData.employeesByCompany.map(item => item.company),
                    datasets: [{
                        label: '{{ __("Admin-Interface.employees") }}',
                        data: dashboardData.employeesByCompany.map(item => item.employees),
                        backgroundColor: colors,
                        borderRadius: 5,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                            rtl: isRTL,
                            textDirection: isRTL ? 'rtl' : 'ltr'
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { family: isRTL ? 'Cairo' : 'Inter', size: 14 },
                            bodyFont:  { family: isRTL ? 'Cairo' : 'Inter', size: 12 },
                            rtl: isRTL,
                            textDirection: isRTL ? 'rtl' : 'ltr',
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    const total = dashboardData.employees || 1;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${value} {{ __("Admin-Interface.employees_text") }} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: isRTL ? 'right' : 'left',
                            title: {
                                display: true,
                                text: '{{ __("Admin-Interface.number_of_employees") }}',
                                font: { family: isRTL ? 'Cairo' : 'Inter', size: 13 }
                            },
                            ticks: { font: { family: isRTL ? 'Cairo' : 'Inter' } }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Admin-Interface.company") }}',
                                font: { family: isRTL ? 'Cairo' : 'Inter', size: 13 }
                            },
                            ticks: { font: { family: isRTL ? 'Cairo' : 'Inter', size: 12 } },
                            reverse: isRTL
                        }
                    }
                }
            });
        }

        // ── NEW CHART: Line — System Growth Trend ───────────────────────────
        function createGrowthChart() {
            const ctx = document.getElementById('growthLineChart').getContext('2d');
            const isRTL = document.documentElement.dir === 'rtl';

            const labels = dashboardData.monthlyGrowth.labels || [];
            const empData = dashboardData.monthlyGrowth.employees || [];
            const compData = dashboardData.monthlyGrowth.companies || [];

            // Create gradients for background fills
            const empGradient = ctx.createLinearGradient(0, 0, 0, 250);
            empGradient.addColorStop(0, 'rgba(102, 126, 234, 0.35)');
            empGradient.addColorStop(1, 'rgba(102, 126, 234, 0.01)');

            const compGradient = ctx.createLinearGradient(0, 0, 0, 250);
            compGradient.addColorStop(0, 'rgba(237, 137, 54, 0.35)');
            compGradient.addColorStop(1, 'rgba(237, 137, 54, 0.01)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '{{ __("Admin-Interface.new_employees") }}',
                            data: empData,
                            borderColor: '#667eea',
                            backgroundColor: empGradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#667eea',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: '{{ __("Admin-Interface.new_companies") }}',
                            data: compData,
                            borderColor: '#ed8936',
                            backgroundColor: compGradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ed8936',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.82)',
                            titleFont: { family: isRTL ? 'Cairo' : 'Inter', size: 13 },
                            bodyFont:  { family: isRTL ? 'Cairo' : 'Inter', size: 12 },
                            rtl: isRTL
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: isRTL ? 'Cairo' : 'Inter' } },
                            position: isRTL ? 'right' : 'left'
                        },
                        x: {
                            ticks: { font: { family: isRTL ? 'Cairo' : 'Inter', size: 11 } },
                            reverse: isRTL
                        }
                    }
                }
            });

            // Build custom legend for growth chart
            const legend = document.getElementById('growthLegend');
            const totalEmps = empData.reduce((a, b) => a + b, 0);
            const totalComps = compData.reduce((a, b) => a + b, 0);

            legend.innerHTML = `
                <div class="donut-legend-item">
                    <span class="donut-legend-dot" style="background:#667eea"></span>
                    <span class="donut-legend-label">{{ __("Admin-Interface.new_employees") }}</span>
                    <span class="donut-legend-value">${totalEmps} <em>({{ __("Admin-Interface.total_registrations") }})</em></span>
                </div>
                <div class="donut-legend-item">
                    <span class="donut-legend-dot" style="background:#ed8936"></span>
                    <span class="donut-legend-label">{{ __("Admin-Interface.new_companies") }}</span>
                    <span class="donut-legend-value">${totalComps} <em>({{ __("Admin-Interface.total_registrations") }})</em></span>
                </div>
            `;
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            updateStats();
            createChart();
            createGrowthChart();
        });

        // Add interactive animations to stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
@endsection
