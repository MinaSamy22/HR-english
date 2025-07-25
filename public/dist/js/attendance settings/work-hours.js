document.addEventListener('DOMContentLoaded', function () {
    const hoursInput = document.getElementById('work_hours_per_day');
    const feedbackEl = document.getElementById('work_hours_feedback');

    let isFirstLoad = true;

    const route = document.querySelector('meta[name="work-hours-route"]')?.getAttribute('content');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!hoursInput || !route || !token) {
        console.error('Missing required elements for work hours functionality');
        return;
    }

    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function updateWorkHours(value) {
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
                work_hours_per_day: value
            })
        })
            .then(res => {
                if (!res.ok) throw new Error('Server responded with an error');
                return res.json();
            })
            .then(data => {
                if (!isFirstLoad) {
                    feedbackEl.innerHTML = `<span class="text-success"><i class="fas fa-check"></i> ${data.message || 'Work hours updated.'}</span>`;
                    setTimeout(() => feedbackEl.innerHTML = '', 3000);
                } else {
                    isFirstLoad = false;
                }
            })
            .catch((error) => {
                console.error('Error updating work hours:', error);
                feedbackEl.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Failed to update work hours.</span>`;
                setTimeout(() => feedbackEl.innerHTML = '', 5000);
                isFirstLoad = false;
            });
    }

    const debouncedUpdate = debounce(updateWorkHours, 500);

    hoursInput.addEventListener('input', function () {
        isFirstLoad = false;
        debouncedUpdate(this.value);
    });

    hoursInput.addEventListener('change', function () {
        isFirstLoad = false;
        debouncedUpdate(this.value);
    });

    hoursInput.removeAttribute('onchange');
});
