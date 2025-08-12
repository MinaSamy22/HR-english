// File: public/dist/js/bounas.js
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
        const checkboxes = document.querySelectorAll('.bounasCheckbox');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function deleteSelectedRecords() {
        const selectedIds = Array.from(document.querySelectorAll('.bounasCheckbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            // Get the current locale from Laravel
            const locale = document.documentElement.lang || 'en';
            const noRowMessage = locale === 'ar' ? 'لم يتم تحديد أي صف.' : 'No row selected.';
            alert(noRowMessage);
            return;
        }

        const locale = document.documentElement.lang || 'en';
        const confirmMessage = locale === 'ar' ? 'هل أنت متأكد من حذف المحدد؟' : 'Are you sure you want to delete the selection?';
        const errorMessage = locale === 'ar' ? 'حدث خطأ. يرجى المحاولة مرة أخرى.' : 'An error occurred. Please try again.';

        if (confirm(confirmMessage)) {
            fetch("/admin/bounas/delete-multiple", {
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
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(errorMessage);
            });
        }
    }
});
