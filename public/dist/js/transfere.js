// Update selected count and enable/disable submit button
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.employee-checkbox:checked');
    const allCheckboxes = document.querySelectorAll('.employee-checkbox');
    const count = checkboxes.length;
    const total = allCheckboxes.length;

    document.getElementById('selected-count').textContent = count;

    // Update select all button
    const selectAllBtn = document.getElementById('select-all-btn');
    if (count === total && total > 0) {
        selectAllBtn.innerHTML = '<i class="fas fa-times"></i> ' + window.translations.deselectAll;
        selectAllBtn.className = 'btn btn-sm btn-warning';
    } else {
        selectAllBtn.innerHTML = '<i class="fas fa-check-double"></i> ' + window.translations.selectAll;
        selectAllBtn.className = 'btn btn-sm btn-success';
    }

    const transferBtn = document.getElementById('transfer-btn');
    if (count > 0) {
        transferBtn.disabled = false;
        if (count === 1) {
            transferBtn.innerHTML = '<i class="fas fa-random"></i> ' + window.translations.transferSelectedEmployee;
        } else {
            const message = window.translations.transferXSelectedEmployees.replace(':count', count);
            transferBtn.innerHTML = '<i class="fas fa-random"></i> ' + message;
        }
    } else {
        transferBtn.disabled = true;
        transferBtn.innerHTML = '<i class="fas fa-random"></i> ' + window.translations.transferSelectedEmployees;
    }
}

// Toggle between select all and deselect all
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.employee-checkbox');
    const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');

    if (checkedBoxes.length === checkboxes.length) {
        // All are selected, so deselect all
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    } else {
        // Not all are selected, so select all
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    }
    updateSelectedCount();
}

// Add event listeners to all checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.employee-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Initial count update
    updateSelectedCount();
});

// Add form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const selectedEmployees = document.querySelectorAll('.employee-checkbox:checked');
    const branchSelect = document.querySelector('select[name="branch_id"]');

    if (selectedEmployees.length === 0) {
        e.preventDefault();
        alert(window.translations.pleaseSelectEmployee);
        return false;
    }

    if (!branchSelect.value) {
        e.preventDefault();
        alert(window.translations.pleaseSelectBranch);
        return false;
    }
});
