// File: public/dist/js/payroll.js
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
        const checkboxes = document.querySelectorAll('.payrollCheckbox');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function deleteSelectedRecords() {
        const selectedIds = Array.from(document.querySelectorAll('.payrollCheckbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            // Get translated message from a data attribute or use fallback
            const noRowMessage = document.body.getAttribute('data-no-row-message') || 'No row selected.';
            alert(noRowMessage);
            return;
        }

        // Get translated confirmation message from a data attribute or use fallback
        const confirmMessage = document.body.getAttribute('data-delete-confirm-message') || 'Are you sure you want to delete the selection?';

        if (confirm(confirmMessage)) {
            fetch("/admin/payrolls/delete-multiple", {
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
                    // Get translated error message from a data attribute or use fallback
                    const errorMessage = document.body.getAttribute('data-error-message') || 'An error occurred. Please try again.';
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Get translated error message from a data attribute or use fallback
                const errorMessage = document.body.getAttribute('data-error-message') || 'An error occurred. Please try again.';
                alert(errorMessage);
            });
        }
    }
});
