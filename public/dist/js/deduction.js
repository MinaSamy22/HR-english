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
            alert('No row selected.');
            return;
        }

        if (confirm('Are you sure you want to delete the selection?')) {
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
                    alert('An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    }
});
