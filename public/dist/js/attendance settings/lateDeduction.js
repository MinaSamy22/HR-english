function updateLateDeduction(value) {
    const feedbackEl = document.getElementById('lateDeductionFeedback');

    fetch(updateLateDeductionUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
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

}
