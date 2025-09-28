// Translation object
const translations = {
    en: {
        saving_changes: 'Saving changes...',
        holidays_saved: 'Holidays saved successfully',
        failed_to_save: 'Failed to save holidays',
        deleting_holiday: 'Deleting holiday...',
        holiday_deleted: 'Holiday deleted successfully',
        failed_to_delete: 'Failed to delete holiday',
        confirm_delete_with_date: 'Are you sure you want to delete "{title}" on {date}?',
        confirm_delete_generic: 'Are you sure you want to delete this holiday entry?',
        holiday_title_placeholder: 'Holiday title'
    },
    ar: {
        saving_changes: 'جاري حفظ التغييرات...',
        holidays_saved: 'تم حفظ العطل بنجاح',
        failed_to_save: 'فشل في حفظ العطل',
        deleting_holiday: 'جاري حذف العطلة...',
        holiday_deleted: 'تم حذف العطلة بنجاح',
        failed_to_delete: 'فشل في حذف العطلة',
        confirm_delete_with_date: 'هل أنت متأكد من حذف "{title}" في {date}؟',
        confirm_delete_generic: 'هل أنت متأكد من حذف هذه العطلة؟',
        holiday_title_placeholder: 'عنوان العطلة'
    }
};

// Get current language (you can set this based on your app's language detection)
const currentLanguage = document.documentElement.lang || 'en'; // Default to English

// Translation helper function
function t(key, replacements = {}) {
    let text = translations[currentLanguage][key] || translations.en[key] || key;

    // Replace placeholders
    Object.keys(replacements).forEach(placeholder => {
        text = text.replace(`{${placeholder}}`, replacements[placeholder]);
    });

    return text;
}

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('holiday-container');
    const addBtn = document.getElementById('add-holiday-btn');

    // Add debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function collectHolidayData() {
        const dates = document.querySelectorAll('.holiday-date');
        const titles = document.querySelectorAll('.holiday-title');

        const holidays = Array.from(dates).map((dateInput, index) => {
            return {
                date: dateInput.value,
                title: titles[index].value
            };
        });

        return holidays.filter(h => h.date && h.title);
    }

    // Use debounce to prevent excessive API calls
    const debouncedSave = debounce(function() {
        saveHolidays();
    }, 500);

    function saveHolidays() {
        const data = collectHolidayData();

        // Show loading indicator
        showSaveIndicator(`<i class="fas fa-spinner fa-spin"></i> ${t('saving_changes')}`, 'text-info');

        fetch('/attendance-rules/update-holidays', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ official_holidays: data })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            // Show success message
            showSaveIndicator(`<i class="fas fa-check"></i> ${data.message || t('holidays_saved')}`, 'text-success', 3000);
        })
        .catch(error => {
            console.error('Failed to save holidays:', error);
            // Show error message
            showSaveIndicator(`<i class="fas fa-exclamation-circle"></i> ${t('failed_to_save')}`, 'text-danger', 5000);
        });
    }

    function showSaveIndicator(message, className, timeout = 0) {
        // Remove existing indicator if any
        const existingIndicator = document.getElementById('save-indicator');
        if (existingIndicator) existingIndicator.remove();

        // Create new indicator
        const saveIndicator = document.createElement('div');
        saveIndicator.id = 'save-indicator';
        saveIndicator.className = className + ' mt-2';
        saveIndicator.innerHTML = message;

        // Add indicator after the add button
        addBtn.insertAdjacentElement('afterend', saveIndicator);

        // Remove indicator after specified timeout
        if (timeout > 0) {
            setTimeout(() => {
                if (document.getElementById('save-indicator')) {
                    document.getElementById('save-indicator').remove();
                }
            }, timeout);
        }
    }

    function deleteHoliday(holidayEntry) {
        const dateInput = holidayEntry.querySelector('.holiday-date');
        const titleInput = holidayEntry.querySelector('.holiday-title');

        if (!dateInput.value || !titleInput.value) {
            // If the holiday doesn't have both date and title, just remove from DOM
            holidayEntry.remove();
            return;
        }

        // Show loading indicator
        showSaveIndicator(`<i class="fas fa-spinner fa-spin"></i> ${t('deleting_holiday')}`, 'text-info');

        fetch('/admin/attendance-rule/delete-holiday', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                date: dateInput.value,
                title: titleInput.value
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            // Remove the holiday entry from DOM
            holidayEntry.remove();

            // Show success message
            showSaveIndicator(`<i class="fas fa-check"></i> ${data.message || t('holiday_deleted')}`, 'text-success', 3000);
        })
        .catch(error => {
            console.error('Failed to delete holiday:', error);
            // Show error message
            showSaveIndicator(`<i class="fas fa-exclamation-circle"></i> ${t('failed_to_delete')}`, 'text-danger', 5000);
        });
    }

    // Attach input event listeners to the container for event delegation
    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('holiday-date') || e.target.classList.contains('holiday-title')) {
            debouncedSave();
        }
    });

    // Handle remove button clicks with confirmation
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-holiday-btn')) {
            const holidayEntry = e.target.closest('.holiday-entry');
            const holidayTitle = holidayEntry.querySelector('.holiday-title').value || 'this holiday';
            const holidayDate = holidayEntry.querySelector('.holiday-date').value || '';

            // Format the date for better readability if available
            const formattedDate = holidayDate ?
                new Date(holidayDate).toLocaleDateString() : '';

            const confirmMessage = formattedDate ?
                t('confirm_delete_with_date', { title: holidayTitle, date: formattedDate }) :
                t('confirm_delete_generic');

            if (confirm(confirmMessage)) {
                deleteHoliday(holidayEntry);
            }
        }
    });

    // Add new holiday entry
    addBtn.addEventListener('click', function() {
        const newEntry = document.createElement('div');
        newEntry.className = 'holiday-entry mb-2 row';
        newEntry.innerHTML = `
            <div class="col-md-4">
                <input type="date" class="form-control holiday-date" name="holiday_dates[]" required>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control holiday-title" name="holiday_titles[]" placeholder="${t('holiday_title_placeholder')}" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-holiday-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newEntry);

        // Focus the date input of the new entry
        const dateInput = newEntry.querySelector('.holiday-date');
        if (dateInput) dateInput.focus();
    });
});
