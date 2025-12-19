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

    // Event listeners
    checkInInputs.forEach(input => input.addEventListener('change', saveCheckTime));
    checkOutInputs.forEach(input => input.addEventListener('change', saveCheckTime));
    attendanceRadios.forEach(radio => radio.addEventListener('change', saveAttendance));

    // Select all
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            employeeCheckboxes.forEach(chk => chk.checked = selectAllCheckbox.checked);
        });
    }

    // Apply global times
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

            const originalHtml = applyGlobalBtn.innerHTML;
            applyGlobalBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;
            applyGlobalBtn.disabled = true;

            for (const chk of selected) {
                const empId = chk.value;
                const inField = document.querySelector(`.check-in-input[data-employee='${empId}']`);
                const outField = document.querySelector(`.check-out-input[data-employee='${empId}']`);

                // Update UI
                inField.value = inTime || '';
                outField.value = outTime || '';

                // 🔹 Automatic calculation - no manual_override flag
                await saveData(empId, {
                    attendance_date: date,
                    check_in: inTime ? `${inTime.length === 5 ? inTime + ':00' : inTime}` : null,
                    check_out: outTime ? `${outTime.length === 5 ? outTime + ':00' : outTime}` : null,
                    manual_override: false // Automatic
                });
            }

            applyGlobalBtn.innerHTML = originalHtml;
            applyGlobalBtn.disabled = false;
            alert('All selected employees updated successfully!');
        });
    }

    // Save attendance type (radio) - MANUAL OVERRIDE
    function saveAttendance(event) {
        const empId = event.target.dataset.employee;
        const date = attendanceDateInput.value;
        const attendanceType = event.target.value;

        // Read existing time values (don't change them)
        const inTime = document.querySelector(`.check-in-input[data-employee='${empId}']`)?.value || null;
        const outTime = document.querySelector(`.check-out-input[data-employee='${empId}']`)?.value || null;

        // 🔹 Send with manual_override flag
        saveData(empId, {
            attendance_date: date,
            attendance_type: attendanceType,
            check_in: inTime || null,
            check_out: outTime || null,
            manual_override: true // ✅ Manual selection
        }, event.target.closest('.attendance-radio-group'));
    }

    // Save check-in / check-out - AUTOMATIC CALCULATION
    function saveCheckTime(event) {
        const empId = event.target.dataset.employee;
        const date = attendanceDateInput.value;

        // Read both times
        const inTime = document.querySelector(`.check-in-input[data-employee='${empId}']`)?.value || null;
        const outTime = document.querySelector(`.check-out-input[data-employee='${empId}']`)?.value || null;

        // 🔹 Don't send attendance_type - let backend calculate it
        saveData(empId, {
            attendance_date: date,
            check_in: inTime ? `${inTime.length === 5 ? inTime + ':00' : inTime}` : null,
            check_out: outTime ? `${outTime.length === 5 ? outTime + ':00' : outTime}` : null,
            manual_override: false // ✅ Automatic calculation
        }, event.target);
    }

    // Shared save function
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

            if (result.success) {
                const radios = document.querySelectorAll(
                    `.attendance-radio[data-employee='${employeeId}']`
                );

                const type = result.record.attendance_type;

                if (type === null) {
                    // ❌ NULL → uncheck all radios
                    radios.forEach(r => r.checked = false);

                    // Optional UI hint
                    radios.forEach(r => r.closest('.attendance-radio-group')
                        ?.classList.add('needs-review'));

                    setTimeout(() => {
                        radios.forEach(r => r.closest('.attendance-radio-group')
                            ?.classList.remove('needs-review'));
                    }, 2000);

                } else {
                    // ✅ Update radio selection based on backend response
                    radios.forEach(r => {
                        r.checked = (r.value == type);
                    });
                }

                // ✅ Visual feedback
                if (element) {
                    if (element.classList.contains('attendance-radio-group')) {
                        element.classList.add('save-success-radio');
                        setTimeout(() => element.classList.remove('save-success-radio'), 1200);
                    } else {
                        element.classList.add('save-success');
                        setTimeout(() => element.classList.remove('save-success'), 1200);
                    }
                }
            } else {
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
