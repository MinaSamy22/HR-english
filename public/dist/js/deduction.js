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
        const checkboxes = document.querySelectorAll('.deductionCheckbox');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function deleteSelectedRecords() {
        const selectedIds = Array.from(document.querySelectorAll('.deductionCheckbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            // Get translated message from meta tag or use default
            const noRowMessage = document.querySelector('meta[name="no-row-selected"]')?.getAttribute('content') || 'No row selected.';
            alert(noRowMessage);
            return;
        }

        // Get translated confirmation message from meta tag or use default
        const confirmMessage = document.querySelector('meta[name="delete-selection-confirm"]')?.getAttribute('content') || 'Are you sure you want to delete the selection?';

        if (confirm(confirmMessage)) {
            fetch("/admin/deductions/delete-multiple", {
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
                    // Get translated error message from meta tag or use default
                    const errorMessage = document.querySelector('meta[name="error-occurred"]')?.getAttribute('content') || 'An error occurred. Please try again.';
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Get translated error message from meta tag or use default
                const errorMessage = document.querySelector('meta[name="error-occurred"]')?.getAttribute('content') || 'An error occurred. Please try again.';
                alert(errorMessage);
            });
        }
    }
});
