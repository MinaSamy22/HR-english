document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
       EMPLOYEE SELECT ALL
    ================================ */
    const selectAllCheckbox = document.getElementById('select-all');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');

    if (selectAllCheckbox && employeeCheckboxes.length > 0) {
        function updateSelectAllState() {
            const total = employeeCheckboxes.length;
            const checked = document.querySelectorAll('.employee-checkbox:checked').length;

            if (checked === total && total > 0) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (checked > 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        selectAllCheckbox.addEventListener('change', function () {
            employeeCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        employeeCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllState);
        });

        // Initialize state on page load (e.g. if old inputs exist)
        updateSelectAllState();
    }

    const insuranceSources = document.querySelectorAll('.insurance-source');
    const insuranceInputs  = document.querySelectorAll('.insurance-input');
    const totalInput       = document.querySelector('input[name="i_percent"]');
    const insuranceForm    = document.querySelector('form');
    const errorMsg         = document.getElementById('insurance-percent-error');
    const remainingEl      = document.getElementById('insurance-remaining-percent');

    if (!insuranceSources.length || !insuranceForm || !totalInput) return;

    /* ===============================
       TOGGLE INPUTS
    =============================== */
    insuranceSources.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const input = document.getElementById(this.dataset.target);
            if (!input) return;

            if (this.checked) {
                input.classList.remove('d-none');
                if (!input.value) input.focus();
            } else {
                input.classList.add('d-none');
                input.value = '';
            }

            updateRemaining();
        });
    });

    /* ===============================
       LIVE INPUT LISTENER
    =============================== */
    insuranceInputs.forEach(input => {
        input.addEventListener('input', updateRemaining);
    });

    /* ===============================
       SUBMIT VALIDATION
    =============================== */
    insuranceForm.addEventListener('submit', function (e) {
        if (!validateTotal()) {
            e.preventDefault();
        }
    });

    /* ===============================
       FUNCTIONS
    =============================== */
    function getCurrentSum() {
        let sum = 0;
        insuranceInputs.forEach(input => {
            if (!input.classList.contains('d-none')) {
                sum += Number(input.value || 0);
            }
        });
        return sum;
    }

    function updateRemaining() {
        const total = Number(totalInput.value || 0);
        const used  = getCurrentSum();
        const remaining = (total - used).toFixed(2);

        if (remainingEl) {
            remainingEl.textContent = remaining >= 0 ? remaining : 0;
            remainingEl.style.color = remaining < 0 ? 'red' : 'green';
        }

        validateTotal();
    }

    function validateTotal() {
        const total = Number(totalInput.value || 0);
        const used  = getCurrentSum();

        if (used !== total) {
            errorMsg?.classList.remove('d-none');
            return false;
        } else {
            errorMsg?.classList.add('d-none');
            return true;
        }
    }

    // ✅ Initial calculation for EDIT page
    updateRemaining();

});
