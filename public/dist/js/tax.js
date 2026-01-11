document.addEventListener('DOMContentLoaded', function () {

    const taxSources = document.querySelectorAll('.tax-source');
    const taxInputs  = document.querySelectorAll('.tax-input');
    const totalInput = document.querySelector('input[name="percent"]');
    const taxForm    = document.querySelector('form');
    const errorMsg   = document.getElementById('percent-error');
    const remainingEl = document.getElementById('remaining-percent');

    if (!taxSources.length || !taxForm || !totalInput) return;

    /* ===============================
       TOGGLE INPUTS
    ================================ */
    taxSources.forEach(checkbox => {
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
    ================================ */
    taxInputs.forEach(input => {
        input.addEventListener('input', updateRemaining);
    });

    /* ===============================
       SUBMIT VALIDATION
    ================================ */
    taxForm.addEventListener('submit', function (e) {
        if (!validateTotal()) {
            e.preventDefault();
        }
    });

    /* ===============================
       FUNCTIONS
    ================================ */
    function getCurrentSum() {
        let sum = 0;
        taxInputs.forEach(input => {
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
