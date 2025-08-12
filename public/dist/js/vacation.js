// File: public/dist/js/vacation.js
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
        const checkboxes = document.querySelectorAll('.vacationCheckbox');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function deleteSelectedRecords() {
        const selectedIds = Array.from(document.querySelectorAll('.vacationCheckbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            // Get the current language from the HTML lang attribute or use a default
            const currentLang = document.documentElement.lang || 'en';

            // Messages based on language
            const messages = {
                'en': {
                    'no_selection': 'No row selected.',
                    'confirm_delete': 'Are you sure you want to delete the selection?',
                    'error_occurred': 'An error occurred. Please try again.'
                },
                'ar': {
                    'no_selection': 'لم يتم تحديد أي صف.',
                    'confirm_delete': 'هل أنت متأكد أنك تريد حذف المحدد؟',
                    'error_occurred': 'حدث خطأ. حاول مرة أخرى.'
                }
            };

            const msg = messages[currentLang] || messages['en'];
            alert(msg.no_selection);
            return;
        }

        const currentLang = document.documentElement.lang || 'en';
        const messages = {
            'en': {
                'no_selection': 'No row selected.',
                'confirm_delete': 'Are you sure you want to delete the selection?',
                'error_occurred': 'An error occurred. Please try again.'
            },
            'ar': {
                'no_selection': 'لم يتم تحديد أي صف.',
                'confirm_delete': 'هل أنت متأكد أنك تريد حذف المحدد؟',
                'error_occurred': 'حدث خطأ. حاول مرة أخرى.'
            }
        };

        const msg = messages[currentLang] || messages['en'];

        if (confirm(msg.confirm_delete)) {
            fetch("/admin/vacations/delete-multiple", {
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
                    alert(msg.error_occurred);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(msg.error_occurred);
            });
        }
    }
});

// Additional function to handle language-specific date formatting if needed
function formatDate(dateString, locale = 'en') {
    const date = new Date(dateString);

    if (locale === 'ar') {
        return date.toLocaleDateString('ar-EG', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    } else {
        return date.toLocaleDateString('en-GB', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    }
}
