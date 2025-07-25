function printPayslip(payrollId) {
    var printContent = document.getElementById('payslip-' + payrollId);
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent.outerHTML;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

function printAllPayslips() {
    window.print();
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
    form.action = window.appConfig.downloadUrl; // Use the config value
    form.style.display = 'none';

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = window.appConfig.csrfToken; // Use the config value
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
    form.action = window.appConfig.downloadAllUrl; // Use the config value
    form.style.display = 'none';

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = window.appConfig.csrfToken; // Use the config value
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
