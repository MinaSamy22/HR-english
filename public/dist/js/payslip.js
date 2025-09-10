function printPayslip(payrollId) {
    // Add CSS to hide buttons and other payslips during print
    const style = document.createElement('style');
    style.innerHTML = `
        @media print {
            button, .btn, .no-print, .print-hide,
            .download-btn, .print-btn, .action-buttons,
            .card-header, .breadcrumb, .content-header, form, .form-group {
                display: none !important;
            }

            /* Hide all payslips except the one we want to print */
            .payslip-container {
                display: none !important;
            }

            /* Show only the target payslip */
            #payslip-${payrollId} {
                display: block !important;
            }

            /* Ensure proper styling for single payslip */
            .col-md-8 {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    `;
    document.head.appendChild(style);

    // Print without manipulating the DOM
    window.print();

    // Remove the style after printing
    document.head.removeChild(style);
}

function printAllPayslips() {
    // Add CSS to hide buttons and unwanted elements during print
    const style = document.createElement('style');
    style.innerHTML = `
        @media print {
            button, .btn, .no-print, .print-hide,
            .download-btn, .print-btn, .action-buttons,
            .card-header, .breadcrumb, .content-header, form, .form-group {
                display: none !important;
            }

            .col-md-8 {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    `;
    document.head.appendChild(style);

    window.print();

    // Remove the style after printing
    document.head.removeChild(style);
}

function downloadPayslip(payrollId) {
    // Show loading state
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
    button.disabled = true;

    // Create a form to submit the download request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.appConfig.downloadUrl;
    form.style.display = 'none';

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = window.appConfig.csrfToken;
    form.appendChild(csrfToken);

    // Add payroll ID
    const payrollIdInput = document.createElement('input');
    payrollIdInput.type = 'hidden';
    payrollIdInput.name = 'payroll_id';
    payrollIdInput.value = payrollId;
    form.appendChild(payrollIdInput);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);

    // Reset button after a delay
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 3000);
}

function downloadAllPayslips() {
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
    button.disabled = true;

    // Get all payroll IDs from the current page
    const payrollIds = [];
    document.querySelectorAll('[id^="payslip-"]').forEach(element => {
        const id = element.id.replace('payslip-', '');
        payrollIds.push(id);
    });

    // Create a form to submit the download request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.appConfig.downloadAllUrl;
    form.style.display = 'none';

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = window.appConfig.csrfToken;
    form.appendChild(csrfToken);

    // Add payroll IDs
    payrollIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'payroll_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    // Add search parameters to maintain context
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.forEach((value, key) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);

    // Reset button after a delay
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 5000);
}
