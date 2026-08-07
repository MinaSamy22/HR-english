document.addEventListener('DOMContentLoaded', function () {
    // ----------------------------------------------------
    // Color Palette
    // ----------------------------------------------------
    var simplePalette = [
        '#3b82f6', // Blue
        '#10b981', // Emerald
        '#f59e0b', // Amber
        '#8b5cf6', // Purple
        '#ec4899', // Pink
        '#06b6d4', // Cyan
        '#64748b'  // Slate
    ];

    var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Helper for totals
    var totalEmployees = Array.isArray(employeeCounts) 
        ? employeeCounts.reduce(function (a, b) { return a + Number(b); }, 0) 
        : 0;

    // ----------------------------------------------------
    // 1. Doughnut Chart (Departments Overview)
    // ----------------------------------------------------
    var pieCanvas = document.getElementById('pieChart');
    if (pieCanvas) {
        var ctx1 = pieCanvas.getContext('2d');
        var deptColors = (departmentNames || []).map(function (_, index) {
            return simplePalette[index % simplePalette.length];
        });

        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: departmentNames,
                datasets: [{
                    label: translations.number_of_employees || 'Employees',
                    data: employeeCounts,
                    backgroundColor: deptColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            padding: 14,
                            usePointStyle: true,
                            font: { size: 11, family: 'sans-serif' },
                            color: '#64748b'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                var val = context.parsed || 0;
                                var percentage = totalEmployees > 0 
                                    ? ((val / totalEmployees) * 100).toFixed(1) + '%' 
                                    : '0%';
                                return ' ' + context.label + ': ' + val + ' (' + percentage + ')';
                            }
                        }
                    }
                }
            }
        });
    }

    // ----------------------------------------------------
    // 2. Bar Chart (Top Employees Overtime)
    // ----------------------------------------------------
    var barCanvas = document.getElementById('barChart2');
    if (barCanvas) {
        var ctx2 = barCanvas.getContext('2d');

        var filteredData = (employeeNames || [])
            .map(function (name, index) {
                return {
                    name: name,
                    hours: overtimeHours ? (overtimeHours[index] || 0) : 0
                };
            })
            .filter(function (item) {
                return item.name && item.name !== 'Unknown';
            });

        var filteredNames = filteredData.map(function (item) { return item.name; });
        var filteredHours = filteredData.map(function (item) { return item.hours; });

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: filteredNames.length > 0 ? filteredNames : [translations.employees || 'Employees'],
                datasets: [{
                    label: translations.overtime_hours || 'Overtime Hours',
                    data: filteredHours.length > 0 ? filteredHours : [0],
                    backgroundColor: '#3b82f6',
                    hoverBackgroundColor: '#2563eb',
                    borderRadius: 6,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { stepSize: 1, color: '#64748b', font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return ' ' + (translations.overtime_hours || 'Overtime') + ': ' + context.parsed.y + ' hrs';
                            }
                        }
                    }
                }
            }
        });
    }

    // ----------------------------------------------------
    // 3. Line Chart (Annual Attendance & Absence Trends)
    // ----------------------------------------------------
    var annualCanvas = document.getElementById('annualTrendChart');
    if (annualCanvas) {
        var ctx3 = annualCanvas.getContext('2d');
        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [
                    {
                        label: translations.present || 'Present Days',
                        data: monthlyPresent || [],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    },
                    {
                        label: translations.absent || 'Absences',
                        data: monthlyAbsences || [],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    },
                    {
                        label: translations.vacation || 'Vacations',
                        data: monthlyVacations || [],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    },
                    x: {
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            font: { size: 11 },
                            color: '#64748b'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });
    }

    // ----------------------------------------------------
    // 4. Doughnut Chart (Today's Attendance Status)
    // ----------------------------------------------------
    var todayCanvas = document.getElementById('todayAttendanceChart');
    if (todayCanvas) {
        var ctx4 = todayCanvas.getContext('2d');
        new Chart(ctx4, {
            type: 'doughnut',
            data: {
                labels: [
                    translations.present || 'Present',
                    translations.late || 'Late',
                    translations.absent || 'Absent',
                    translations.halfday || 'Half Day'
                ],
                datasets: [{
                    data: [todayPresent, todayLate, todayAbsent, todayHalfday],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            padding: 14,
                            usePointStyle: true,
                            font: { size: 11 },
                            color: '#64748b'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });
    }

    // ----------------------------------------------------
    // 5. Horizontal Bar Chart (Job Roles Headcount)
    // ----------------------------------------------------
    var jobCanvas = document.getElementById('jobRolesChart');
    if (jobCanvas) {
        var ctx5 = jobCanvas.getContext('2d');
        var jobColors = (jobTitles || []).map(function (_, index) {
            return simplePalette[index % simplePalette.length];
        });

        new Chart(ctx5, {
            type: 'bar',
            data: {
                labels: jobTitles.length > 0 ? jobTitles : ['Default Role'],
                datasets: [{
                    label: translations.employees || 'Employees',
                    data: jobUserCounts.length > 0 ? jobUserCounts : [0],
                    backgroundColor: jobColors,
                    borderRadius: 6,
                    maxBarThickness: 28
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { stepSize: 1, color: '#64748b', font: { size: 11 } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });
    }
});
