
/**
 * Late Threshold Handler
 * Handles updating late threshold minutes via AJAX
 */
function updateLateThreshold(minutes) {
    const feedbackEl = document.getElementById('lateThresholdFeedback');

    fetch(updateLateThresholdUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            late_threshold_minutes: minutes
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        feedbackEl.innerHTML = '<div class="text-success">' + data.message + '</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    })
    .catch(error => {
        feedbackEl.innerHTML = '<div class="text-danger">Error updating late threshold</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    });
}

/**
 * Half Day Threshold Handler
 * Handles updating half day threshold minutes via AJAX
 */
function updateHalfDayThreshold(minutes) {
    const feedbackEl = document.getElementById('halfDayThresholdFeedback');

    fetch(updateHalfDayThresholdUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            half_day_threshold_minutes: minutes
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        feedbackEl.innerHTML = '<div class="text-success">' + data.message + '</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    })
    .catch(error => {
        feedbackEl.innerHTML = '<div class="text-danger">Error updating half day threshold</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    });
}

/**
 * Late Deduction Handler
 * Handles updating late deduction percentage via AJAX
 */
function updateLateDeduction(value) {
    const feedbackEl = document.getElementById('lateDeductionFeedback');

    fetch(updateLateDeductionUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            late_deduction_percentage: value
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        feedbackEl.innerHTML = '<div class="text-success">' + data.message + '</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    })
    .catch(error => {
        feedbackEl.innerHTML = '<div class="text-danger">Error updating late deduction</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    });
}

/**
 * Half Day Deduction Handler
 * Handles updating half day deduction percentage via AJAX
 */
function updateHalfDayDeduction(value) {
    const feedbackEl = document.getElementById('half_day_deduction_feedback');

    // Use the centralized CSRF token or fallback to meta tag
    const token = csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(halfDayUpdateRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            half_day_deduction_percentage: value
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        feedbackEl.innerHTML = '<div class="text-success">' + (data.message || 'Updated successfully!') + '</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    })
    .catch(error => {
        feedbackEl.innerHTML = '<div class="text-danger">Error updating half day deduction</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    });
}
