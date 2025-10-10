// workhours-days.js - Enhanced Employee Management Script with Consistent UI Feedback
document.addEventListener('DOMContentLoaded', function() {
    // Get routes and tokens
    const workHoursRoute = document.querySelector('meta[name="employees-work-hours-route"]').getAttribute('content');
    const workingDaysRoute = '/attendance/update-employee-working-days';
    const vacationBalanceRoute = '/attendance/update-employee-vacation-balance';
    const bonusPerHourRoute = '/attendance/update-employee-bonus-per-hour';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Get translated messages from meta tags or data attributes
    const messages = {
        selectOneEmployee: document.querySelector('meta[name="msg-select-employee"]')?.getAttribute('content') || 'Please select at least one employee',
        invalidHours: document.querySelector('meta[name="msg-invalid-hours"]')?.getAttribute('content') || 'Work hours must be between 1 and 24',
        updating: document.querySelector('meta[name="msg-updating"]')?.getAttribute('content') || 'Updating...',
        assignHours: document.querySelector('meta[name="msg-assign-hours"]')?.getAttribute('content') || 'Assign Hours to Selected Employees',
        assignWorkingDays: document.querySelector('meta[name="msg-assign-working-days"]')?.getAttribute('content') || 'Assign Working Days to Selected Employees',
        updateFailed: document.querySelector('meta[name="msg-update-failed"]')?.getAttribute('content') || 'Failed to update',
        selectWorkingDays: document.querySelector('meta[name="msg-select-working-days"]')?.getAttribute('content') || 'Please select at least one working day',
        hrs: document.querySelector('meta[name="msg-hrs"]')?.getAttribute('content') || 'hrs',
        days: document.querySelector('meta[name="msg-days"]')?.getAttribute('content') || 'days',
        // New messages for vacation and bonus with translation support
        invalidVacation: document.querySelector('meta[name="msg-invalid-vacation"]')?.getAttribute('content') || 'Please enter a valid vacation balance',
        invalidBonus: document.querySelector('meta[name="msg-invalid-bonus"]')?.getAttribute('content') || 'Please enter a valid bonus amount',
        assignVacation: document.querySelector('meta[name="msg-assign-vacation"]')?.getAttribute('content') || 'Assign Vacation to Selected Employees',
        assignBonus: document.querySelector('meta[name="msg-assign-bonus"]')?.getAttribute('content') || 'Assign Bonus to Selected Employees',
        workingDaysUpdated: document.querySelector('meta[name="msg-working-days-updated"]')?.getAttribute('content') || 'Working days updated successfully',
        vacationUpdated: document.querySelector('meta[name="msg-vacation-updated"]')?.getAttribute('content') || 'Vacation balance updated successfully',
        bonusUpdated: document.querySelector('meta[name="msg-bonus-updated"]')?.getAttribute('content') || 'Bonus per hour updated successfully'
    };

    // Day abbreviations with translation support
    const dayAbbreviations = {
        'Sunday': document.querySelector('meta[name="msg-sun"]')?.getAttribute('content') || 'Sun',
        'Monday': document.querySelector('meta[name="msg-mon"]')?.getAttribute('content') || 'Mon',
        'Tuesday': document.querySelector('meta[name="msg-tue"]')?.getAttribute('content') || 'Tue',
        'Wednesday': document.querySelector('meta[name="msg-wed"]')?.getAttribute('content') || 'Wed',
        'Thursday': document.querySelector('meta[name="msg-thu"]')?.getAttribute('content') || 'Thu',
        'Friday': document.querySelector('meta[name="msg-fri"]')?.getAttribute('content') || 'Fri',
        'Saturday': document.querySelector('meta[name="msg-sat"]')?.getAttribute('content') || 'Sat'
    };

    // Get DOM elements
    const selectAllCheckbox = document.getElementById('select_all');
    const employeeCheckboxes = document.querySelectorAll('.employee_check');
    const assignHoursBtn = document.getElementById('assign_hours_btn');
    const assignWorkingDaysBtn = document.getElementById('assign_working_days_btn');
    const assignHoursInput = document.getElementById('assign_hours');
    const workingDayCheckboxes = document.querySelectorAll('.working-day-checkbox');

    // New elements for vacation and bonus
    const assignVacationBtn = document.getElementById('assign_vacation_btn');
    const assignBonusBtn = document.getElementById('assign_bonus_btn');
    const assignVacationInput = document.getElementById('assign_vacation_balance');
    const assignBonusInput = document.getElementById('assign_bonus_per_hour');

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
    // UTILITY FUNCTIONS
    // ==========================================================================

    // Enhanced function to highlight updated rows with success effect
    function highlightUpdatedRows(selectedCheckboxes) {
        selectedCheckboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            if (row) {
                row.classList.add('table-success');
                // Add a subtle animation effect
                row.style.transition = 'background-color 0.3s ease';
            }
        });

        // Remove success class after 3 seconds with fade effect
        setTimeout(() => {
            selectedCheckboxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                if (row) {
                    row.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
                    setTimeout(() => {
                        row.classList.remove('table-success');
                        row.style.backgroundColor = '';
                        row.style.transition = '';
                    }, 300);
                }
            });
        }, 3000);
    }

    // Clear employee selections
    function clearSelections() {
        employeeCheckboxes.forEach(checkbox => checkbox.checked = false);
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

// Get translations from meta tags
function getTranslation(key) {
    const meta = document.querySelector(`meta[name="${key}"]`);
    return meta ? meta.getAttribute('content') : key;
}

// Get current locale
function getCurrentLocale() {
    const meta = document.querySelector('meta[name="current-locale"]');
    return meta ? meta.getAttribute('content') : 'en';
}

// Check if current language is RTL
function isRTL() {
    const locale = getCurrentLocale();
    return ['ar', 'ur', 'fa', 'he'].includes(locale);
}

// Show toast notification function
function showAlert(type, message, title = null) {
    // Ensure message is a string
    if (typeof message !== 'string') {
        console.error('Invalid message format:', message);
        message = 'An error occurred';
    }

    // Map alert types to toast types and icons
    const typeConfig = {
        'success': {
            class: 'toast-success',
            icon: 'fas fa-check-circle',
            title: title || getTranslation('toast-success-title')
        },
        'danger': {
            class: 'toast-danger',
            icon: 'fas fa-exclamation-circle',
            title: title || getTranslation('toast-error-title')
        },
        'warning': {
            class: 'toast-warning',
            icon: 'fas fa-exclamation-triangle',
            title: title || getTranslation('toast-warning-title')
        },
        'info': {
            class: 'toast-info',
            icon: 'fas fa-info-circle',
            title: title || getTranslation('toast-info-title')
        }
    };

    const config = typeConfig[type] || typeConfig['info'];

    // Create toast element
    const toastId = 'toast_' + Date.now();
    const rtlAttr = isRTL() ? 'dir="rtl"' : 'dir="ltr"';

    const toastHtml = `
        <div class="toast-item ${config.class}" id="${toastId}" ${rtlAttr}>
            <div class="toast-icon">
                <i class="${config.icon}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${config.title}</div>
                <p class="toast-message">${message}</p>
            </div>
            <button class="toast-close" onclick="closeToast('${toastId}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    // Add toast to notification area
    const toastContainer = document.getElementById('toast_notification');
    if (toastContainer) {
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            closeToast(toastId);
        }, 5000);
    } else {
        console.warn('Toast notification area not found');
    }
}

// Close toast function
function closeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('fade-out');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }
}

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
                            }
                        });

                        // Highlight updated rows
                        highlightUpdatedRows(selected);

                        // Clear selections and input
                        if (assignHoursInput) {
                            assignHoursInput.value = '';
                        }
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
    // WORKING DAYS ASSIGNMENT (ENHANCED)
    // ==========================================================================

    if (assignWorkingDaysBtn) {
        assignWorkingDaysBtn.addEventListener('click', function() {
            const selected = document.querySelectorAll('.employee_check:checked');
            const selectedEmployees = Array.from(selected).map(checkbox => checkbox.value);

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
                    const message = data.message || messages.workingDaysUpdated;
                    showAlert('success', message);

                    // Update the working days display for affected employees with translated abbreviations
                    selectedEmployees.forEach(employeeId => {
                        const container = document.querySelector(`.employee-working-days[data-id="${employeeId}"]`);
                        if (container) {
                            container.innerHTML = selectedWorkingDays.map(day =>
                                `<span class="badge badge-secondary mr-1">${dayAbbreviations[day] || day}</span>`
                            ).join('');
                        }
                    });

                    // Highlight updated rows
                    highlightUpdatedRows(selected);

                    // Clear selections
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
    // VACATION BALANCE ASSIGNMENT (ENHANCED)
    // ==========================================================================

    if (assignVacationBtn) {
        assignVacationBtn.addEventListener('click', function() {
            const selected = document.querySelectorAll('.employee_check:checked');
            const selectedEmployees = Array.from(selected).map(checkbox => checkbox.value);
            const vacationBalance = assignVacationInput ? assignVacationInput.value : null;

            // Validation
            if (selectedEmployees.length === 0) {
                showAlert('warning', messages.selectOneEmployee);
                return;
            }

            if (!vacationBalance || isNaN(vacationBalance) || vacationBalance < 0) {
                showAlert('danger', messages.invalidVacation);
                return;
            }

            const button = this;

            // Show loading state
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i> ${messages.updating}`;
            button.disabled = true;

            // Create FormData for vacation balance
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('bulk_update', 'true');
            formData.append('vacation_balance', parseFloat(vacationBalance));

            selectedEmployees.forEach(id => {
                formData.append('employee_ids[]', id);
            });

            // Log the request for debugging
            console.log('Sending vacation balance request:', {
                route: vacationBalanceRoute,
                employeeIds: selectedEmployees,
                vacationBalance: vacationBalance
            });

            // Send AJAX request
            fetch(vacationBalanceRoute, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Vacation balance response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return response.json();
            })
            .then(data => {
                console.log('Vacation balance response data:', data);

                if (data.success === true) {
                    const message = data.message || messages.vacationUpdated;
                    showAlert('success', message);

                    // Update the vacation balance display for affected employees with translation
                    selectedEmployees.forEach(employeeId => {
                        const badge = document.querySelector(`.employee-vacation[data-id="${employeeId}"]`);
                        if (badge) {
                            badge.textContent = vacationBalance + ' ' + messages.days;
                        }
                    });

                    // Highlight updated rows
                    highlightUpdatedRows(selected);

                    // Clear selections and input
                    if (assignVacationInput) {
                        assignVacationInput.value = '';
                    }
                } else {
                    showAlert('danger', data.message || 'Failed to update vacation balance');
                }
            })
            .catch(error => {
                console.error('Vacation balance fetch error:', error);

                let errorMessage = 'Failed to update vacation balance';
                if (error.message.includes('HTTP error')) {
                    errorMessage = 'Server error occurred. Please try again.';
                } else if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Network error. Please check your connection.';
                }

                showAlert('danger', errorMessage);
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = `<i class="fas fa-check mr-1"></i>${messages.assignVacation}`;
                button.disabled = false;
            });
        });
    }

    // ==========================================================================
    // BONUS PER HOUR ASSIGNMENT (ENHANCED)
    // ==========================================================================

    if (assignBonusBtn) {
        assignBonusBtn.addEventListener('click', function() {
            const selected = document.querySelectorAll('.employee_check:checked');
            const selectedEmployees = Array.from(selected).map(checkbox => checkbox.value);
            const bonusPerHour = assignBonusInput ? assignBonusInput.value : null;

            // Validation
            if (selectedEmployees.length === 0) {
                showAlert('warning', messages.selectOneEmployee);
                return;
            }

            if (!bonusPerHour || isNaN(bonusPerHour) || bonusPerHour < 0) {
                showAlert('danger', messages.invalidBonus);
                return;
            }

            const button = this;

            // Show loading state
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i> ${messages.updating}`;
            button.disabled = true;

            // Create FormData for bonus per hour
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('bulk_update', 'true');
            formData.append('bonus_per_hour', parseFloat(bonusPerHour));

            selectedEmployees.forEach(id => {
                formData.append('employee_ids[]', id);
            });

            // Log the request for debugging
            console.log('Sending bonus per hour request:', {
                route: bonusPerHourRoute,
                employeeIds: selectedEmployees,
                bonusPerHour: bonusPerHour
            });

            // Send AJAX request
            fetch(bonusPerHourRoute, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Bonus per hour response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return response.json();
            })
            .then(data => {
                console.log('Bonus per hour response data:', data);

                if (data.success === true) {
                    const message = data.message || messages.bonusUpdated;
                    showAlert('success', message);

                    // Update the bonus per hour display for affected employees
                    selectedEmployees.forEach(employeeId => {
                        const badge = document.querySelector(`.employee-bonus[data-id="${employeeId}"]`);
                        if (badge) {
                            const formattedBonus = parseFloat(bonusPerHour).toFixed(2);
                            badge.textContent = '$' + formattedBonus;
                        }
                    });

                    // Highlight updated rows
                    highlightUpdatedRows(selected);

                    // Clear selections and input
                    if (assignBonusInput) {
                        assignBonusInput.value = '';
                    }
                } else {
                    showAlert('danger', data.message || 'Failed to update bonus per hour');
                }
            })
            .catch(error => {
                console.error('Bonus per hour fetch error:', error);

                let errorMessage = 'Failed to update bonus per hour';
                if (error.message.includes('HTTP error')) {
                    errorMessage = 'Server error occurred. Please try again.';
                } else if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Network error. Please check your connection.';
                }

                showAlert('danger', errorMessage);
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = `<i class="fas fa-check mr-1"></i>${messages.assignBonus}`;
                button.disabled = false;
            });
        });
    }

    // ==========================================================================
    // ADDITIONAL UTILITY FUNCTIONS
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
        showAlert: showAlert,
        highlightUpdatedRows: highlightUpdatedRows
    };

    console.log('Enhanced WorkHours-Days script loaded successfully with consistent UI feedback and translation support');
});


// ==========================================================================
// EMPLOYEE SEARCH FILTER
// ==========================================================================
const employeeSearch = document.getElementById('employee_search');
if (employeeSearch) {
    employeeSearch.addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#employees_table tr');

        rows.forEach(row => {
            const nameCell = row.querySelector('td:nth-child(2)'); // employee name is in 2nd column
            if (nameCell) {
                const name = nameCell.textContent.toLowerCase();
                row.style.display = name.includes(query) ? '' : 'none';
            }
        });
    });
}
