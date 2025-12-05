/**
 * Get translation from meta tags
 */
function getTranslation(key) {
    const meta = document.querySelector(`meta[name="${key}"]`);
    return meta ? meta.getAttribute('content') : key;
}

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
        const errorMsg = getTranslation('msg-error-updating-late-threshold');
        feedbackEl.innerHTML = '<div class="text-danger">' + errorMsg + '</div>';
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
        const errorMsg = getTranslation('msg-error-updating-half-day-threshold');
        feedbackEl.innerHTML = '<div class="text-danger">' + errorMsg + '</div>';
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
        const errorMsg = getTranslation('msg-error-updating-late-deduction');
        feedbackEl.innerHTML = '<div class="text-danger">' + errorMsg + '</div>';
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
        const successMsg = data.message || getTranslation('msg-updated-successfully');
        feedbackEl.innerHTML = '<div class="text-success">' + successMsg + '</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    })
    .catch(error => {
        const errorMsg = getTranslation('msg-error-updating-half-day-deduction');
        feedbackEl.innerHTML = '<div class="text-danger">' + errorMsg + '</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    });
}


/**
 * Absent Threshold Handler
 * Handles updating absent threshold minutes via AJAX
 */
function updateAbsentThreshold(minutes) {
    const feedbackEl = document.getElementById('absentThresholdFeedback');

    fetch(updateAbsentThresholdUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            absent_threshold_minutes: minutes
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
        const errorMsg = getTranslation('msg-error-updating-absent-threshold');
        feedbackEl.innerHTML = '<div class="text-danger">' + errorMsg + '</div>';
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    });
}
