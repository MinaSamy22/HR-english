// workhours-days.js - Consolidated Employee Management Script
document.addEventListener('DOMContentLoaded', function() {
    // Get routes and tokens
    const workHoursRoute = document.querySelector('meta[name="employees-work-hours-route"]').getAttribute('content');
    const workingDaysRoute = '/attendance/update-employee-working-days'; // Add this route to your backend
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Get translated messages from meta tags or data attributes
    const messages = {
        selectOneEmployee: document.querySelector('meta[name="msg-select-employee"]')?.getAttribute('content') || 'Please select at least one employee',
        invalidHours: document.querySelector('meta[name="msg-invalid-hours"]')?.getAttribute('content') || 'Work hours must be between 1 and 24',
        updating: document.querySelector('meta[name="msg-updating"]')?.getAttribute('content') || 'Updating...',
        assignHours: document.querySelector('meta[name="msg-assign-hours"]')?.getAttribute('content') || 'Assign Hours to Selected Employees',
        assignWorkingDays: document.querySelector('meta[name="msg-assign-working-days"]')?.getAttribute('content') || 'Assign Working Days to Selected Employees',
        updateFailed: document.querySelector('meta[name="msg-update-failed"]')?.getAttribute('content') || 'Failed to update',
        selectWorkingDays: 'Please select at least one working day',
        hrs: document.querySelector('meta[name="msg-hrs"]')?.getAttribute('content') || 'hrs'
    };

    // Get DOM elements
    const selectAllCheckbox = document.getElementById('select_all');
    const employeeCheckboxes = document.querySelectorAll('.employee_check');
    const assignHoursBtn = document.getElementById('assign_hours_btn');
    const assignWorkingDaysBtn = document.getElementById('assign_working_days_btn');
    const assignHoursInput = document.getElementById('assign_hours');
    const workingDayCheckboxes = document.querySelectorAll('.working-day-checkbox');

    // ==========================================================================
    // CHECKBOX FUNCTIONALITY
    // ==========================================================================

    // Select all checkbox functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            employeeCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Individual checkbox functionality
    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const total = employeeCheckboxes.length;
            const checked = document.querySelectorAll('.employee_check:checked').length;
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = (total === checked);
                selectAllCheckbox.indeterminate = checked > 0 && checked < total;
            }
        });
    });

    // ==========================================================================
    // WORK HOURS ASSIGNMENT
    // ==========================================================================

    if (assignHoursBtn) {
        assignHoursBtn.addEventListener('click', function() {
            const selected = document.querySelectorAll('.employee_check:checked');
            const hours = assignHoursInput ? assignHoursInput.value : null;

            // Validation
            if (selected.length === 0) {
                showAlert('warning', messages.selectOneEmployee);
                return;
            }

            if (!hours || isNaN(hours) || hours < 1 || hours > 24) {
                showAlert('danger', messages.invalidHours);
                return;
            }

            const employeeIds = Array.from(selected).map(checkbox => checkbox.value);
            const button = this;

            // Update button state
            button.disabled = true;
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>${messages.updating}`;

            // Create form data
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('work_hours_per_day', parseFloat(hours));
            formData.append('bulk_update', 'true');

            employeeIds.forEach(id => {
                formData.append('employee_ids[]', id);
            });

            // Log the request for debugging
            console.log('Sending work hours request:', {
                route: workHoursRoute,
                employeeIds: employeeIds,
                hours: hours
            });

            fetch(workHoursRoute, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Work hours response status:', response.status);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('Work hours response data:', data);

                    if (typeof data !== 'object' || data === null) {
                        throw new Error('Invalid response format');
                    }

                    if (data.success === true) {
                        const message = typeof data.message === 'string' ? data.message : 'Work hours updated successfully';
                        showAlert('success', message);

                        // Update badges
                        selected.forEach(checkbox => {
                            const id = checkbox.value;
                            const badge = document.querySelector(`.employee-hours[data-id="${id}"]`);
                            if (badge) {
                                badge.textContent = hours + ' ' + messages.hrs;
                                checkbox.closest('tr').classList.add('table-success');
                            }
                        });

                        // Clear selections and input
                        clearSelections();
                        if (assignHoursInput) {
                            assignHoursInput.value = '';
                        }

                        // Remove success class after 3 seconds
                        setTimeout(() => {
                            document.querySelectorAll('.table-success').forEach(row => {
                                row.classList.remove('table-success');
                            });
                        }, 3000);
                    } else {
                        const errorMessage = data.message || messages.updateFailed;
                        console.error('Server error:', data);
                        showAlert('danger', errorMessage);
                    }
                })
                .catch(error => {
                    console.error('Work hours fetch error:', error);

                    let errorMessage = messages.updateFailed;
                    if (error.message.includes('HTTP error')) {
                        errorMessage = 'Server error occurred. Please try again.';
                    } else if (error.message.includes('Failed to fetch')) {
                        errorMessage = 'Network error. Please check your connection.';
                    }

                    showAlert('danger', errorMessage);
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = `<i class="fas fa-check mr-1"></i>${messages.assignHours}`;
                });
        });
    }

    // ==========================================================================
    // WORKING DAYS ASSIGNMENT
    // ==========================================================================

    if (assignWorkingDaysBtn) {
        assignWorkingDaysBtn.addEventListener('click', function() {
            const selectedEmployees = Array.from(employeeCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            const selectedWorkingDays = Array.from(workingDayCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            // Validation
            if (selectedEmployees.length === 0) {
                showAlert('warning', messages.selectOneEmployee);
                return;
            }

            if (selectedWorkingDays.length === 0) {
                showAlert('warning', messages.selectWorkingDays);
                return;
            }

            const button = this;

            // Show loading state
            const originalText = button.innerHTML;
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i> ${messages.updating}`;
            button.disabled = true;

            // Create FormData for working days
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('bulk_update', 'true');

            selectedEmployees.forEach(id => {
                formData.append('employee_ids[]', id);
            });

            selectedWorkingDays.forEach(day => {
                formData.append('working_days[]', day);
            });

            // Log the request for debugging
            console.log('Sending working days request:', {
                route: workingDaysRoute,
                employeeIds: selectedEmployees,
                workingDays: selectedWorkingDays
            });

            // Send AJAX request
            fetch(workingDaysRoute, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Working days response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return response.json();
            })
            .then(data => {
                console.log('Working days response data:', data);

                if (data.success === true) {
                    showAlert('success', data.message);

                    // Day abbreviations for display
                    const dayAbbreviations = {
                        'Sunday': 'Sun', 'Monday': 'Mon', 'Tuesday': 'Tue',
                        'Wednesday': 'Wed', 'Thursday': 'Thu', 'Friday': 'Fri', 'Saturday': 'Sat'
                    };

                    // Update the working days display for affected employees
                    selectedEmployees.forEach(employeeId => {
                        const container = document.querySelector(`.employee-working-days[data-id="${employeeId}"]`);
                        if (container) {
                            container.innerHTML = selectedWorkingDays.map(day =>
                                `<span class="badge badge-secondary mr-1">${dayAbbreviations[day] || day}</span>`
                            ).join('');
                        }
                    });

                    // Clear selections
                    clearSelections();
                    workingDayCheckboxes.forEach(checkbox => checkbox.checked = false);
                } else {
                    showAlert('danger', data.message || 'Failed to update working days');
                }
            })
            .catch(error => {
                console.error('Working days fetch error:', error);

                let errorMessage = 'Failed to update working days';
                if (error.message.includes('HTTP error')) {
                    errorMessage = 'Server error occurred. Please try again.';
                } else if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Network error. Please check your connection.';
                }

                showAlert('danger', errorMessage);
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    }

    // ==========================================================================
    // UTILITY FUNCTIONS
    // ==========================================================================

    // Clear employee selections
    function clearSelections() {
        employeeCheckboxes.forEach(checkbox => checkbox.checked = false);
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    // Show alert function
    function showAlert(type, message) {
        const alertArea = document.getElementById('alert_area');
        if (!alertArea) {
            console.warn('Alert area not found');
            return;
        }

        // Ensure message is a string
        if (typeof message !== 'string') {
            console.error('Invalid message format:', message);
            message = 'An error occurred';
        }

        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;

        alertArea.innerHTML = alertHtml;

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = alertArea.querySelector('.alert');
            if (alert) {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    // ==========================================================================
    // ADDITIONAL UTILITY FUNCTIONS (Optional)
    // ==========================================================================

    // Quick selection for working days
    function selectWeekdays() {
        const weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        workingDayCheckboxes.forEach(checkbox => {
            checkbox.checked = weekdays.includes(checkbox.value);
        });
    }

    function selectAllDays() {
        workingDayCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function clearDaysSelection() {
        workingDayCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    // Expose utility functions globally if needed
    window.WorkHoursDays = {
        selectWeekdays: selectWeekdays,
        selectAllDays: selectAllDays,
        clearDaysSelection: clearDaysSelection,
        clearSelections: clearSelections,
        showAlert: showAlert
    };

    console.log('WorkHours-Days script loaded successfully');
});
