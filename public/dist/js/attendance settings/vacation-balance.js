document.addEventListener('DOMContentLoaded', function () {
    const vacationInput = document.getElementById('vacation_balance');
    const feedbackEl = document.getElementById('vacation_balance_feedback');

    // Track if this is the first load
    let isFirstLoad = true;

    // Get route and token from meta tags
    const route = document.querySelector('meta[name="vacation-balance-route"]')?.getAttribute('content');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Check if all elements exist
    if (!vacationInput || !route || !token) {
        console.error('Missing required elements for vacation balance functionality');
        return;
    }

    // Debounce function to prevent multiple rapid requests
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Function to update vacation balance
    function updateVacationBalance(value) {
        // Only show loading indicator if not first load
        if (!isFirstLoad) {
            feedbackEl.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Updating...</span>';
        }

        fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                vacation_balance: value
            })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Server responded with an error');
            }
            return res.json();
        })
        .then(data => {
            // Only show success message if not first load
            if (!isFirstLoad) {
                feedbackEl.innerHTML = `<span class="text-success"><i class="fas fa-check"></i> ${data.message || 'Vacation balance saved .'}</span>`;
                setTimeout(() => feedbackEl.innerHTML = '', 3000);
            } else {
                // Clear first load flag after initial save
                isFirstLoad = false;
            }
        })
        .catch((error) => {
            console.error('Error updating vacation balance:', error);
            // Always show error messages
            feedbackEl.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Failed to update vacation balance.</span>`;
            setTimeout(() => feedbackEl.innerHTML = '', 5000);
            // Clear first load flag after error
            isFirstLoad = false;
        });
    }

    // Debounced update function
    const debouncedUpdate = debounce(updateVacationBalance, 500);

    // Add event listener for input changes
    vacationInput.addEventListener('input', function() {
        isFirstLoad = false; // User is actively changing the value
        debouncedUpdate(this.value);
    });

    // Add event listener for change event to catch non-typing changes
    vacationInput.addEventListener('change', function() {
        isFirstLoad = false; // User is actively changing the value
        debouncedUpdate(this.value);
    });

    // Remove the inline onchange attribute if it exists
    vacationInput.removeAttribute('onchange');
});
