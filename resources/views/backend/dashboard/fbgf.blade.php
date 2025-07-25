@extends('backend.layouts.app')
@section('content')

<style>
/* Modern Card Styles */
.modern-stat-card {
    position: relative;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 0;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    min-height: 140px;
}

/* Dark Mode Styles */
.dark-mode .modern-stat-card {
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(148, 163, 184, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.modern-stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.dark-mode .modern-stat-card:hover {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

.card-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    transition: height 0.3s ease;
}

.modern-stat-card:hover .card-gradient {
    height: 8px;
}

.employees-card .card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.present-card .card-gradient {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.late-card .card-gradient {
    background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
}

.absent-card .card-gradient {
    background: linear-gradient(135deg, #e17055 0%, #d63031 100%);
}

.card-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 22px;
    height: 120px;
}

.stat-info {
    flex: 1;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 6px;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.dark-mode .stat-number {
    color: #f1f5f9;
}

.stat-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dark-mode .stat-label {
    color: #cbd5e1;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #94a3b8;
    font-weight: 500;
}

.dark-mode .stat-trend {
    color: #94a3b8;
}

.stat-trend i {
    font-size: 0.8rem;
}

.stat-icon {
    position: relative;
}

.icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s ease;
}

.employees-card .icon-wrapper {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    color: #667eea;
}

.present-card .icon-wrapper {
    background: linear-gradient(135deg, rgba(17, 153, 142, 0.1), rgba(56, 239, 125, 0.1));
    color: #11998e;
}

.late-card .icon-wrapper {
    background: linear-gradient(135deg, rgba(255, 234, 167, 0.2), rgba(253, 203, 110, 0.2));
    color: #fdcb6e;
}

.absent-card .icon-wrapper {
    background: linear-gradient(135deg, rgba(225, 112, 85, 0.2), rgba(214, 48, 49, 0.2));
    color: #d63031;
}

.icon-wrapper i {
    font-size: 1.6rem;
    transition: all 0.3s ease;
}

.modern-stat-card:hover .icon-wrapper {
    transform: scale(1.1);
}

.card-footer {
    padding: 0 20px 15px;
}

.progress-bar {
    height: 4px;
    background: rgba(148, 163, 184, 0.2);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 0.6s ease;
}

.employees-card .progress-fill {
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.present-card .progress-fill {
    background: linear-gradient(90deg, #11998e, #38ef7d);
}

.late-card .progress-fill {
    background: linear-gradient(90deg, #ffeaa7, #fdcb6e);
}

.absent-card .progress-fill {
    background: linear-gradient(90deg, #e17055, #d63031);
}

/* Modern Chart Card */
.modern-chart-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.modern-chart-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.modern-chart-card .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    padding: 20px 25px;
    border-radius: 20px 20px 0 0;
}

.modern-chart-card .card-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
}

.modern-chart-card .card-body {
    padding: 25px;
}




/* Responsive Design */
@media (max-width: 1199.98px) {
    .stat-number {
        font-size: 2rem;
    }

    .icon-wrapper {
        width: 55px;
        height: 55px;
    }

    .icon-wrapper i {
        font-size: 1.5rem;
    }
}

@media (max-width: 991.98px) {
    .card-content {
        padding: 18px;
        height: 90px;
    }

    .stat-number {
        font-size: 1.9rem;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
    }

    .icon-wrapper i {
        font-size: 1.3rem;
    }
}

@media (max-width: 767.98px) {
    .modern-stat-card {
        margin-bottom: 20px;
        min-height: 130px;
    }

    .card-content {
        padding: 16px;
        height: 85px;
    }

    .stat-number {
        font-size: 1.8rem;
    }

    .stat-label {
        font-size: 0.85rem;
    }

    .icon-wrapper {
        width: 45px;
        height: 45px;
    }

    .icon-wrapper i {
        font-size: 1.2rem;
    }
}

@media (max-width: 575.98px) {
    .card-content {
        flex-direction: column;
        text-align: center;
        height: auto;
        padding: 18px 15px;
    }

    .stat-info {
        margin-bottom: 12px;
    }

    .stat-number {
        font-size: 2rem;
    }

    .icon-wrapper {
        width: 55px;
        height: 55px;
    }

    .icon-wrapper i {
        font-size: 1.4rem;
    }

    .modern-stat-card {
        min-height: 150px;
    }
}

/* Animation for cards loading */
@keyframes slideInUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modern-stat-card {
    animation: slideInUp 0.6s ease-out;
}

.modern-stat-card:nth-child(1) { animation-delay: 0.1s; }
.modern-stat-card:nth-child(2) { animation-delay: 0.2s; }
.modern-stat-card:nth-child(3) { animation-delay: 0.3s; }
.modern-stat-card:nth-child(4) { animation-delay: 0.4s; }

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .modern-stat-card,
    .modern-chart-card {
        background: rgba(30, 41, 59, 0.95);
        color: #e2e8f0;
    }

    .stat-number {
        color: #f1f5f9;
    }

    .stat-label {
        color: #cbd5e1;
    }

    .modern-chart-card .card-title {
        color: #f1f5f9;
    }
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper dashboard" style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text">Welcome, {{ Auth::user()->name }} 👋</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text">Home</a></li>
                        <li class="breadcrumb-item active text">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Stat boxes (Responsive) -->
<div class="container-fluid">
            <!-- Modern Stat Cards -->
            <div class="row g-4 mb-4">
                <!-- Employees Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="modern-stat-card employees-card">
                        <div class="card-gradient"></div>
                        <div class="card-content">
                            <div class="stat-info">
                                <div class="stat-number">{{ $getEmployeeCount ?? '0' }}</div>
                                <div class="stat-label">Total Employees</div>
                                <div class="stat-trend">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>Active workforce</span>
                                </div>
                            </div>
                            <div class="stat-icon">
                                <div class="icon-wrapper">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($getEmployeeCount ?? 0) > 0 ? '100' : '0' }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Present Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="modern-stat-card present-card">
                        <div class="card-gradient"></div>
                        <div class="card-content">
                            <div class="stat-info">
                                <div class="stat-number">{{ $presentCount }}</div>
                                <div class="stat-label">Present Today</div>
                                <div class="stat-trend">
                                    <i class="fas fa-check-circle"></i>
                                    <span>On time arrival</span>
                                </div>
                            </div>
                            <div class="stat-icon">
                                <div class="icon-wrapper">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($getEmployeeCount ?? 0) > 0 ? round(($presentCount / ($getEmployeeCount ?? 1)) * 100) : '0' }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Late Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="modern-stat-card late-card">
                        <div class="card-gradient"></div>
                        <div class="card-content">
                            <div class="stat-info">
                                <div class="stat-number">{{ $lateCount + $halfdayCount }}</div>
                                <div class="stat-label">Late Arrivals</div>
                                <div class="stat-trend">
                                    <i class="fas fa-clock"></i>
                                    <span>Delayed check-in</span>
                                </div>
                            </div>
                            <div class="stat-icon">
                                <div class="icon-wrapper">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($getEmployeeCount ?? 0) > 0 ? round((($lateCount + $halfdayCount) / ($getEmployeeCount ?? 1)) * 100) : '0' }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Absent Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="modern-stat-card absent-card">
                        <div class="card-gradient"></div>
                        <div class="card-content">
                            <div class="stat-info">
                                <div class="stat-number">{{ $absentCount }}</div>
                                <div class="stat-label">Absent Today</div>
                                <div class="stat-trend">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Not present</span>
                                </div>
                            </div>
                            <div class="stat-icon">
                                <div class="icon-wrapper">
                                    <i class="fas fa-user-times"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($getEmployeeCount ?? 0) > 0 ? round(($absentCount / ($getEmployeeCount ?? 1)) * 100) : '0' }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->

        </div><!-- /.container-fluid -->

            <!-- Bar Chart and Quick Access Section -->
            <div class="row mt-4">
                <!-- Bar Chart Section (Responsive) -->
                <div class="col-lg-8 col-md-12">
                    <div class="card shadow-sm" style="border-radius: 10px; border: none; border-left: 4px solid #007bff;">
                        <div class="card-header" style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                            <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #007bff;">
                                <i class="fas fa-chart-bar mr-2"></i>Monthly Performance Analytics
                            </h3>
                        </div>
                        <div class="card-body" style="background: white; padding: 1rem;">
                            <div class="chart-container">
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Access Section -->
                <div class="col-lg-4 col-md-12">
                    <div class="card shadow-sm" style="border-radius: 10px; border: none; border-left: 4px solid #007bff;">
                        <div class="card-header" style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                            <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #007bff;">
                                <i class="fas fa-bolt mr-2"></i>Quick Access
                            </h3>
                        </div>
                        <div class="card-body" style="background: white; padding: 1.5rem;">
                             <!-- Reports Section -->
                             <div class="main-actions-section">
                    <h5 class="mb-3" style="color: #6c757d; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-tasks mr-2"></i>Main Actions
                    </h5>
                            <!-- Add Attendance Manually -->
                            <div class="quick-access-item mb-3">
    <a href="{{ route('attendance.index') }}" class="btn btn-light btn-block text-left quick-btn" style="border-radius: 8px; padding: 12px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
        <i class="fas fa-user-check mr-3" style="color: #007bff;"></i>
        <span style="font-weight: 500; color: #333;">Add Attendance Manually</span>
    </a>
</div>


                            <!-- Add Payroll -->
                            <div class="quick-access-item mb-3">
                                <a href="{{ route('payroll') }}" class="btn btn-light btn-block text-left quick-btn" style="border-radius: 8px; padding: 12px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                    <i class="nav-icon fa fa-coins" style="color: #007bff;"></i>
                                    <span style="font-weight: 500; color: #333;">Add Payroll</span>
                                </a>
                            </div>

                        </div>
                            <!-- Reports Section -->
                            <div class="reports-section">
                                <h5 class="mb-3" style="color: #6c757d; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-file-alt mr-2"></i>Reports
                                </h5>

                                <!-- Payslip Report -->
                                <div class="quick-access-item mb-2">
                                    <a href="{{ route('payslip') }}" class="btn btn-light btn-block text-left quick-btn" style="border-radius: 8px; padding: 10px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                        <i class="fas fa-receipt mr-3" style="color: #007bff;"></i>
                                        <span style="font-weight: 500; color: #333;">Payslip Report</span>
                                    </a>
                                </div>

                                <!-- Attendance Report -->
                                <div class="quick-access-item mb-2">
                                    <a href="{{ route('report') }}" class="btn btn-light btn-block text-left quick-btn" style="border-radius: 8px; padding: 10px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                        <i class="fas fa-calendar-check mr-3" style="color: #007bff;"></i>
                                        <span style="font-weight: 500; color: #333;">Attendance Report</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->

        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Custom CSS for quick access & Barcharts grapgh -->
<style>
    .quick-btn:hover {
        background-color: #f8f9fa !important;
        border-color: #007bff !important;
        border-left: 4px solid #007bff !important;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
    }

    .quick-btn:hover i {
        color: #007bff !important;
    }

    .quick-btn:hover span {
        color: #007bff !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1) !important;
    }

    /* Responsive Chart Container */
    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }

    .chart-container canvas {
        border-radius: 8px;
        max-width: 100%;
        height: auto !important;
    }

    /* Mobile-specific adjustments */
    @media (max-width: 991.98px) {
        .col-lg-8, .col-lg-4 {
            margin-bottom: 1rem;
        }

        .chart-container {
            height: 250px;
        }
    }

    @media (max-width: 767.98px) {
        .chart-container {
            height: 220px;
        }

        .card-body {
            padding: 0.75rem !important;
        }

        .card-title {
            font-size: 1rem !important;
        }
    }

    @media (max-width: 575.98px) {
        .chart-container {
            height: 200px;
        }

        .quick-access-item .btn {
            padding: 10px 12px !important;
            font-size: 0.9rem;
        }

        .quick-access-item i {
            margin-right: 8px !important;
        }
    }
    /* Dark Mode Styles */
.dark-mode .card {
    background-color: #1e1e2f !important;
    border-left-color: #4e9fff !important;
}

.dark-mode .card-header {
    background-color: #2c2c3a !important;
    color: #f0f0f0 !important;
    border-bottom: 1px solid #444 !important;
}

.dark-mode .card-title {
    color: #4e9fff !important;
}

.dark-mode .card-body {
    background-color: #1e1e2f !important;
}

.dark-mode .main-actions-section h5,
.dark-mode .reports-section h5 {
    color: #aaa !important;
}

.dark-mode .quick-btn {
    background-color: #2a2a3b !important;
    border: 1px solid #444 !important;
}

.dark-mode .quick-btn i {
    color: #4e9fff !important;
}

.dark-mode .quick-btn span {
    color: #f0f0f0 !important;
}

</style>

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Configuration Script -->
<script>
    // Pass data from Laravel to JavaScript
    var chartDataPresent = @json($Present ?? []);
    var chartDataAbsent = @json($absences ?? []);
    var chartDataVacations = @json($vacations ?? []);

    // Function to determine if device is mobile
    function isMobileDevice() {
        return window.innerWidth <= 767;
    }

    // Function to get responsive chart options
    function getResponsiveOptions() {
        const isMobile = isMobileDevice();

        return {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: isMobile ? 'bottom' : 'top',
                    align: isMobile ? 'center' : 'end',
                    labels: {
                        padding: isMobile ? 15 : 25,
                        font: {
                            size: isMobile ? 11 : 13,
                            weight: '600',
                            family: 'Inter, system-ui, sans-serif'
                        },
                        color: '#374151',
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: isMobile ? 6 : 8,
                        boxHeight: isMobile ? 6 : 8
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.98)',
                    titleColor: '#1f2937',
                    bodyColor: '#374151',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 12,
                    displayColors: true,
                    padding: isMobile ? 8 : 12,
                    titleFont: {
                        size: isMobile ? 12 : 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: isMobile ? 11 : 13
                    },
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw} employees`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(156, 163, 175, 0.1)',
                        drawBorder: false,
                        lineWidth: 1
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: isMobile ? 10 : 12,
                            weight: '500'
                        },
                        padding: isMobile ? 5 : 10,
                        maxTicksLimit: isMobile ? 5 : 8,
                        callback: function(value) {
                            return value % 1 === 0 ? value : '';
                        }
                    },
                    title: {
                        display: !isMobile,
                        text: 'Number of Employees',
                        color: '#374151',
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        padding: 15
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: isMobile ? 10 : 12,
                            weight: '500'
                        },
                        padding: isMobile ? 5 : 10,
                        maxRotation: isMobile ? 45 : 0,
                        minRotation: isMobile ? 45 : 0
                    },
                    title: {
                        display: !isMobile,
                        text: 'Months',
                        color: '#374151',
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        padding: 15
                    }
                }
            },
            animation: {
                duration: 1200,
                easing: 'easeInOutCubic',
                delay: (context) => {
                    return context.dataIndex * 100;
                }
            },
            hover: {
                animationDuration: 200
            },
            elements: {
                bar: {
                    borderSkipped: false,
                }
            }
        };
    }

    // Function to get responsive datasets
    function getResponsiveDatasets() {
        const isMobile = isMobileDevice();
        const barThickness = isMobile ? 12 : 20;

        return [{
            label: 'Present',
            data: chartDataPresent,
            backgroundColor: 'rgba(75,192,192,5)', // Semi-transparent green
            borderColor: 'rgba(75,192,192,1)',
            borderWidth: 0,
            borderRadius: isMobile ? 4 : 6,
            borderSkipped: false,
            barThickness: barThickness,
            categoryPercentage: 0.8,
            barPercentage: 0.9,
        }, {
            label: 'Absent',
            data: chartDataAbsent,
            backgroundColor: 'rgba(255,99,132,5)', // Semi-transparent red
            borderColor: 'rgba(255,99,132,1)', // Solid red
            borderWidth: 0,
            borderRadius: isMobile ? 4 : 6,
            borderSkipped: false,
            barThickness: barThickness,
            categoryPercentage: 0.8,
            barPercentage: 0.9,
        }, {
            label: 'Vacations',
            data: chartDataVacations,
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: 'rgba(60,141,188,0.8)',
            borderWidth: 0,
            borderRadius: isMobile ? 4 : 6,
            borderSkipped: false,
            barThickness: barThickness,
            categoryPercentage: 0.8,
            barPercentage: 0.9,
        }];
    }

    let chartInstance;

    // Function to create/update chart
    function createChart() {
        const ctx = document.getElementById('barChart');

        if (!ctx) {
            console.error('Canvas element with id "barChart" not found');
            return;
        }

        // Destroy existing chart if it exists
        if (chartInstance) {
            chartInstance.destroy();
        }

        // Create new chart with responsive options
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: getResponsiveDatasets()
            },
            options: getResponsiveOptions()
        });
    }

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        createChart();
    });

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            createChart();
        }, 250);
    });
</script>

@endsection
