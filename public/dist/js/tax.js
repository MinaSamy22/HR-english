document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            employeeCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const deleteSelectedButton = document.getElementById('deleteSelected');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('click', toggleAllCheckboxes);
    }

    if (deleteSelectedButton) {
        deleteSelectedButton.addEventListener('click', deleteSelectedRecords);
    }

    function toggleAllCheckboxes() {
        const checkboxes = document.querySelectorAll('.taxCheckbox');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function deleteSelectedRecords() {
        const selectedIds = Array.from(document.querySelectorAll('.taxCheckbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            // Get the localized message from the blade template
            const noRowSelectedMsg = document.querySelector('meta[name="no-row-selected"]')?.getAttribute('content') || 'No row selected.';
            alert(noRowSelectedMsg);
            return;
        }

        // Get the localized message from the blade template
        const deleteConfirmationMsg = document.querySelector('meta[name="delete-selection-confirmation"]')?.getAttribute('content') || 'Are you sure you want to delete the selection?';

        if (confirm(deleteConfirmationMsg)) {
            fetch("/admin/taxes/delete-multiple", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    // Get the localized message from the blade template
                    const errorMsg = document.querySelector('meta[name="error-occurred"]')?.getAttribute('content') || 'An error occurred. Please try again.';
                    alert(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Get the localized message from the blade template
                const errorMsg = document.querySelector('meta[name="error-occurred"]')?.getAttribute('content') || 'An error occurred. Please try again.';
                alert(errorMsg);
            });
        }
    }
});
