document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll('input[name="working_days[]"]');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            const selectedDays = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            fetch("/attendance-rules/update-working-days", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify({
                    working_days: selectedDays
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Working days updated:", data.message);
                // Optionally show a success toast/alert here
            })
            .catch(error => {
                console.error("Error updating working days:", error);
            });
        });
    });
});
