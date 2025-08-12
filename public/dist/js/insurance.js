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
        const checkboxes = document.querySelectorAll('.insuranceCheckbox');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function deleteSelectedRecords() {
        const selectedIds = Array.from(document.querySelectorAll('.insuranceCheckbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            // Get the current language to show appropriate message
            const currentLang = document.documentElement.lang || 'en';
            const noRowMessage = currentLang === 'ar' ? 'لم يتم تحديد أي صف.' : 'No row selected.';
            alert(noRowMessage);
            return;
        }

        // Get the current language for confirmation message
        const currentLang = document.documentElement.lang || 'en';
        const confirmMessage = currentLang === 'ar' ?
            'هل أنت متأكد أنك تريد حذف المحدد؟' :
            'Are you sure you want to delete the selection?';

        const errorMessage = currentLang === 'ar' ?
            'حدث خطأ. يرجى المحاولة مرة أخرى.' :
            'An error occurred. Please try again.';

        if (confirm(confirmMessage)) {
            fetch("/admin/insurance/delete-multiple", {
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

// Additional function to handle language-specific behaviors
function initializeLanguageFeatures() {
    // Check if the current language is Arabic and apply RTL styles if needed
    const currentLang = document.documentElement.lang || 'en';

    if (currentLang === 'ar') {
        // Add RTL class to body if it doesn't exist
        if (!document.body.classList.contains('rtl')) {
            document.body.classList.add('rtl');
        }

        // Adjust table alignment for Arabic
        const tables = document.querySelectorAll('table');
        tables.forEach(table => {
            table.style.direction = 'rtl';
        });

        // Adjust form alignment for Arabic
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.style.direction = 'rtl';
        });
    } else {
        // Ensure LTR for English
        document.body.classList.remove('rtl');

        const tables = document.querySelectorAll('table');
        tables.forEach(table => {
            table.style.direction = 'ltr';
        });

        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.style.direction = 'ltr';
        });
    }
}

// Initialize language features when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeLanguageFeatures);
