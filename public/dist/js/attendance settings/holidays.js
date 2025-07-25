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
        const saveIndicator = document.createElement('div');
        saveIndicator.id = 'save-indicator';
        saveIndicator.className = 'text-info mt-2';
        saveIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving changes...';

        // Remove existing indicator if any
        const existingIndicator = document.getElementById('save-indicator');
        if (existingIndicator) existingIndicator.remove();

        // Add indicator after the add button
        addBtn.insertAdjacentElement('afterend', saveIndicator);

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
            saveIndicator.className = 'text-success mt-2';
            saveIndicator.innerHTML = '<i class="fas fa-check"></i> ' + (data.message || 'Holidays saved successfully');

            // Remove success message after 3 seconds
            setTimeout(() => {
                if (document.getElementById('save-indicator')) {
                    document.getElementById('save-indicator').remove();
                }
            }, 3000);
        })
        .catch(error => {
            console.error('Failed to save holidays:', error);

            // Show error message
            saveIndicator.className = 'text-danger mt-2';
            saveIndicator.innerHTML = '<i class="fas fa-exclamation-circle"></i> Failed to save holidays';

            // Remove error message after 5 seconds
            setTimeout(() => {
                if (document.getElementById('save-indicator')) {
                    document.getElementById('save-indicator').remove();
                }
            }, 5000);
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
                `Are you sure you want to delete ` :
                `Are you sure you want to delete `;

            if (confirm(confirmMessage)) {
                holidayEntry.remove();
                debouncedSave();
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
                <input type="text" class="form-control holiday-title" name="holiday_titles[]" placeholder="Holiday title" required>
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
