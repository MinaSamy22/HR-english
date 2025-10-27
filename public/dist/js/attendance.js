document.addEventListener('DOMContentLoaded', function () {
    const checkInInputs = document.querySelectorAll('.check-in-input');
    const checkOutInputs = document.querySelectorAll('.check-out-input');
    const attendanceDateInput = document.getElementById('getAttendanceDate');
    const attendanceRadios = document.querySelectorAll('.attendance-radio');

    // Bulk controls
    const selectAllCheckbox = document.getElementById('selectAll');
    const employeeCheckboxes = document.querySelectorAll('.employee-select');
    const applyGlobalBtn = document.getElementById('applyGlobalTime');
    const globalCheckIn = document.getElementById('globalCheckIn');
    const globalCheckOut = document.getElementById('globalCheckOut');

    // Individual input listeners
    checkInInputs.forEach(input => input.addEventListener('change', saveCheckTime));
    checkOutInputs.forEach(input => input.addEventListener('change', saveCheckTime));
    attendanceRadios.forEach(radio => radio.addEventListener('change', saveAttendance));

    // Select all
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            employeeCheckboxes.forEach(chk => chk.checked = selectAllCheckbox.checked);
        });
    }

    // Apply global times with spinner
    if (applyGlobalBtn) {
        applyGlobalBtn.addEventListener('click', async function (e) {
            e.preventDefault();

            const inTime = globalCheckIn.value.trim();
            const outTime = globalCheckOut.value.trim();
            const date = attendanceDateInput.value;

            const selected = Array.from(employeeCheckboxes).filter(chk => chk.checked);
            if (selected.length === 0) {
                alert('Please select at least one employee.');
                return;
            }

            // Show loading spinner on button
            const originalHtml = applyGlobalBtn.innerHTML;
            applyGlobalBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;
            applyGlobalBtn.disabled = true;

            // Save each selected employee
            for (const chk of selected) {
                const empId = chk.value;
                const inField = document.querySelector(`.check-in-input[data-employee='${empId}']`);
                const outField = document.querySelector(`.check-out-input[data-employee='${empId}']`);

                // Update UI
                inField.value = inTime || '';
                outField.value = outTime || '';

                await saveData(empId, {
                    attendance_date: date,
                    check_in: inTime ? `${inTime.length === 5 ? inTime + ':00' : inTime}` : null,
check_out: outTime ? `${outTime.length === 5 ? outTime + ':00' : outTime}` : null,

                });
            }

            // Restore button
            applyGlobalBtn.innerHTML = originalHtml;
            applyGlobalBtn.disabled = false;

            alert('All selected employees updated successfully!');
        });
    }

    // Save attendance type from radio button
    function saveAttendance(event) {
        const empId = event.target.dataset.employee;
        const attendanceType = event.target.value;
        const date = attendanceDateInput.value;

        // Get the parent radio group for visual feedback
        const radioGroup = event.target.closest('.attendance-radio-group');

        saveData(empId, {
            attendance_date: date,
            attendance_type: attendanceType
        }, radioGroup);
    }

    // Save check-in/out
    function saveCheckTime(event) {
        const empId = event.target.dataset.employee;
        const date = attendanceDateInput.value;
        const inTime = document.querySelector(`.check-in-input[data-employee='${empId}']`).value;
        const outTime = document.querySelector(`.check-out-input[data-employee='${empId}']`).value;

        saveData(empId, {
            attendance_date: date,
            check_in: inTime ? `${inTime}:00` : null,
            check_out: outTime ? `${outTime}:00` : null
        }, event.target);
    }

    // Save to server
    async function saveData(employeeId, data, element = null) {
        data.employee_id = employeeId;

        try {
            const res = await fetch('/admin/attendance/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await res.json();

            if (result.success && element) {
                // Different visual feedback for radio groups vs inputs
                if (element.classList.contains('attendance-radio-group')) {
                    element.classList.add('save-success-radio');
                    setTimeout(() => element.classList.remove('save-success-radio'), 1200);
                } else {
                    element.classList.add('save-success');
                    setTimeout(() => element.classList.remove('save-success'), 1200);
                }
            } else if (!result.success) {
                console.error(result.message || 'Save failed.');
            }
        } catch (error) {
            console.error('Error saving data:', error);
            if (element) {
                element.classList.add('save-error');
                setTimeout(() => element.classList.remove('save-error'), 2000);
            }
        }
    }
});
