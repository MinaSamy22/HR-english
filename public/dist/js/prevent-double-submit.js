/**
 * Global Double-Submit Prevention for Backend Forms
 * Prevents multiple form submissions / rapid clicking on create and add forms.
 */
(function () {
    function initDoubleSubmitProtection() {
        const forms = document.querySelectorAll('#addForm, #evaluationForm, #requestForm, form.prevent-double-submit');

        forms.forEach(function (form) {
            if (form.dataset.doubleSubmitAttached) return;
            form.dataset.doubleSubmitAttached = 'true';

            let isSubmitting = false;
            const submitBtn = form.querySelector('#submitBtn, button[type="submit"]');

            if (!submitBtn) return;

            // Store original button HTML if not already stored
            if (!submitBtn.dataset.originalHtml) {
                submitBtn.dataset.originalHtml = submitBtn.innerHTML;
            }

            form.addEventListener('submit', function (e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                // If HTML5 form validation fails, do not block or disable
                if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                    return;
                }

                isSubmitting = true;
                submitBtn.disabled = true;

                const originalHtml = submitBtn.dataset.originalHtml || submitBtn.innerHTML;
                submitBtn.dataset.originalHtml = originalHtml;

                // Extract plain text for loading label
                const tempEl = document.createElement('div');
                tempEl.innerHTML = originalHtml;
                const btnText = tempEl.textContent.trim();

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> ' + (btnText ? btnText + '...' : 'Processing...');
                submitBtn.style.opacity = '0.7';
                submitBtn.style.cursor = 'not-allowed';
            });

            // Re-enable submit button when page is restored from bfcache (browser back/forward)
            window.addEventListener('pageshow', function (event) {
                if (event.persisted || isSubmitting) {
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    if (submitBtn.dataset.originalHtml) {
                        submitBtn.innerHTML = submitBtn.dataset.originalHtml;
                    }
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDoubleSubmitProtection);
    } else {
        initDoubleSubmitProtection();
    }
})();
