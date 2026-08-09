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
@endsection

@section('scripts')
    <script>
        // Load data from Blade (Laravel backend)
        const dashboardData = {
            companies: @json($totalCompanies),
            employees: @json($getEmployeeCount),
            admins: @json($totalAdmins),
            hrs: @json($totalHRs),
            employeesByCompany: @json($employeesByCompany ?? [])
        };

        // Update statistics
        function updateStats() {
            document.getElementById('companiesCount').textContent = dashboardData.companies;
            document.getElementById('employeesCount').textContent = dashboardData.employees;
            document.getElementById('adminsCount').textContent = dashboardData.admins;
            document.getElementById('hrsCount').textContent = dashboardData.hrs;
        }

        // Create bar chart
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
                            titleFont: {
                                family: isRTL ? 'Cairo' : 'Inter',
                                size: 14
                            },
                            bodyFont: {
                                family: isRTL ? 'Cairo' : 'Inter',
                                size: 12
                            },
                            rtl: isRTL,
                            textDirection: isRTL ? 'rtl' : 'ltr',
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    const percentage = ((value / dashboardData.employees) * 100).toFixed(1);
                                    return `${value} {{ __("Admin-Interface.employees_text") }} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Admin-Interface.number_of_employees") }}',
                                font: {
                                    family: isRTL ? 'Cairo' : 'Inter',
                                    size: 13
                                }
                            },
                            ticks: {
                                font: {
                                    family: isRTL ? 'Cairo' : 'Inter'
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Admin-Interface.company") }}',
                                font: {
                                    family: isRTL ? 'Cairo' : 'Inter',
                                    size: 13
                                }
                            },
                            ticks: {
                                font: {
                                    family: isRTL ? 'Cairo' : 'Inter',
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            updateStats();
            createChart();
        });

        // Add interactive animations
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
