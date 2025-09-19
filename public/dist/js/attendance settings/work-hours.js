// Wait for document to be ready
document.addEventListener('DOMContentLoaded', function() {
    const route = document.querySelector('meta[name="employees-work-hours-route"]').getAttribute('content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Get translated messages from meta tags or data attributes
    const messages = {
        selectOneEmployee: document.querySelector('meta[name="msg-select-employee"]')?.getAttribute('content') || 'Please select at least one employee',
        invalidHours: document.querySelector('meta[name="msg-invalid-hours"]')?.getAttribute('content') || 'Work hours must be between 1 and 24',
        updating: document.querySelector('meta[name="msg-updating"]')?.getAttribute('content') || 'Updating...',
        assignHours: document.querySelector('meta[name="msg-assign-hours"]')?.getAttribute('content') || 'Assign Hours to Selected Employees',
        updateFailed: document.querySelector('meta[name="msg-update-failed"]')?.getAttribute('content') || 'Failed to update work hours',
        hrs: document.querySelector('meta[name="msg-hrs"]')?.getAttribute('content') || 'hrs'
    };

    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select_all');
    const employeeCheckboxes = document.querySelectorAll('.employee_check');

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
            }
        });
    });

    // Assign hours button functionality
    const assignHoursBtn = document.getElementById('assign_hours_btn');
    if (assignHoursBtn) {
        assignHoursBtn.addEventListener('click', function() {
            const selected = document.querySelectorAll('.employee_check:checked');
            const hoursInput = document.getElementById('assign_hours');
            const hours = hoursInput ? hoursInput.value : null;

            if (selected.length === 0) {
                showAlert('warning', messages.selectOneEmployee);
                return;
            }

            if (!hours || hours < 1 || hours > 24) {
                showAlert('danger', messages.invalidHours);
                return;
            }

            const employeeIds = Array.from(selected).map(checkbox => checkbox.value);
            const button = this;

            button.disabled = true;
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>${messages.updating}`;

            // Create form data
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('work_hours_per_day', hours);
            formData.append('bulk_update', 'true');

            employeeIds.forEach(id => {
                formData.append('employee_ids[]', id);
            });

            fetch(route, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);

                        // Update badges
                        selected.forEach(checkbox => {
                            const id = checkbox.value;
                            const badge = document.querySelector(`.employee-hours[data-id="${id}"]`);
                            if (badge) {
                                badge.textContent = hours + ' ' + messages.hrs;
                                checkbox.closest('tr').classList.add('table-success');
                            }
                        });

                        // Clear selections
                        employeeCheckboxes.forEach(checkbox => checkbox.checked = false);
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = false;
                        }

                        // Remove success class after 3 seconds
                        setTimeout(() => {
                            document.querySelectorAll('.table-success').forEach(row => {
                                row.classList.remove('table-success');
                            });
                        }, 3000);
                    } else {
                        showAlert('danger', data.message || messages.updateFailed);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', messages.updateFailed);
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = `<i class="fas fa-check mr-1"></i>${messages.assignHours}`;
                });
        });
    }

    function showAlert(type, message) {
        const alertArea = document.getElementById('alert_area');
        if (!alertArea) return;

        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;

        alertArea.innerHTML = alertHtml;

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 5000);
    }
});
