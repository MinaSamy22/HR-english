document.addEventListener('DOMContentLoaded', function () {
    const bonusInput = document.getElementById('bonus_per_hour');
    const feedbackEl = document.getElementById('bonus_per_hour_feedback');

    let isFirstLoad = true;

    const route = document.querySelector('meta[name="bonus-per-hour-route"]')?.getAttribute('content');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!bonusInput || !route || !token) {
        console.error('Missing required elements for bonus per hour functionality');
        return;
    }

    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function updateBonus(value) {
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
                bonus_per_hour: value
            })
        })
            .then(res => {
                if (!res.ok) throw new Error('Server responded with an error');
                return res.json();
            })
            .then(data => {
                if (!isFirstLoad) {
                    feedbackEl.innerHTML = `<span class="text-success"><i class="fas fa-check"></i> ${data.message || 'Bonus per hour updated.'}</span>`;
                    setTimeout(() => feedbackEl.innerHTML = '', 3000);
                } else {
                    isFirstLoad = false;
                }
            })
            .catch((error) => {
                console.error('Error updating bonus per hour:', error);
                feedbackEl.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Failed to update bonus per hour.</span>`;
                setTimeout(() => feedbackEl.innerHTML = '', 5000);
                isFirstLoad = false;
            });
    }

    const debouncedUpdate = debounce(updateBonus, 500);

    bonusInput.addEventListener('input', function () {
        isFirstLoad = false;
        debouncedUpdate(this.value);
    });

    bonusInput.addEventListener('change', function () {
        isFirstLoad = false;
        debouncedUpdate(this.value);
    });

    bonusInput.removeAttribute('onchange');
});
