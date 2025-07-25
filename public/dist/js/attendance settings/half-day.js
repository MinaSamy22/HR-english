/**
 * Half Day Deduction Handler
 * Handles updating half day deduction percentage via AJAX
 */
function updateHalfDayDeduction(value) {
    // Show loading feedback
    const feedbackEl = document.getElementById('half_day_deduction_feedback');

    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Send AJAX request
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
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        feedbackEl.innerHTML = '<span class="text-success">Updated successfully!</span>';
        // Hide success message after 3 seconds
        setTimeout(() => {
            feedbackEl.innerHTML = '';
        }, 3000);
    })

}
