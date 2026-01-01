
//radio buttoms and validation js
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    const typeError = document.getElementById('type-error');

    function getSelectedPayrollType() {
        const radios = document.querySelectorAll('input[name="payroll_type"]');
        for (let radio of radios) {
            if (radio.checked) {
                return radio.value;
            }
        }
        return null;
    }

form.addEventListener('submit', function (e) {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);
    const type = getSelectedPayrollType();
    const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
    let isValid = true;
    let errorMessage = '';

    const translations = window.payrollTranslations || {};

    if (!type) {
        errorMessage = translations.select_payroll_type || "Please select a payroll type.";
        isValid = false;
    } else if (type === 'daily' && diffDays > 32) {
        errorMessage = translations.daily_exceeds_limit || "Daily payroll cannot exceed 31 days.";
        isValid = false;
    } else if (type === 'weekly' && diffDays > 7) {
        errorMessage = translations.weekly_exceeds_limit || "Weekly payroll cannot exceed 7 days.";
        isValid = false;
    } else if (type === 'monthly') {
        if (diffDays <= 7) {
            errorMessage = translations.monthly_minimum_days || "Monthly payroll must exceed 7 days.";
            isValid = false;
        } else if (diffDays > 32) {
            errorMessage = translations.monthly_exceeds_limit || "Monthly payroll cannot exceed 31 days.";
            isValid = false;
        }
    }

    if (!isValid) {
        e.preventDefault();
        typeError.textContent = errorMessage;
        typeError.style.display = 'block';
    } else {
        typeError.style.display = 'none';
    }
});
});



//payroll js employee list
document.addEventListener('DOMContentLoaded', function () {
    // Handle payroll type selection to show/hide relevant employee categories
    const payrollTypes = document.querySelectorAll('input[name="payroll_type"]');
    const employeeSection = document.querySelector('.checkbox-box');

    payrollTypes.forEach(function (radio) {
        radio.addEventListener('change', function () {
            const selectedType = this.value;

            // Hide all categories first
            const categories = document.querySelectorAll('.employee-category');
            categories.forEach(function (category) {
                category.classList.remove('show');
                category.style.display = 'none';
            });

            // Show only the relevant category
            const relevantCategory = document.getElementById(selectedType + '-employees');
            if (relevantCategory) {
                relevantCategory.classList.add('show');
                relevantCategory.style.display = 'block';
            }

            // Clear all selections when switching types
            const allCheckboxes = document.querySelectorAll('.employee-checkbox');
            allCheckboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            // Clear category select-all checkboxes
            const selectAllCategories = document.querySelectorAll('.select-all-category');
            selectAllCategories.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            // Clear global select-all
            const globalSelectAll = document.getElementById('select-all-global');
            if (globalSelectAll) {
                globalSelectAll.checked = false;
            }
        });
    });

    // Handle category-specific "Select All" functionality
    const selectAllCategories = document.querySelectorAll('.select-all-category');
    selectAllCategories.forEach(function (selectAll) {
        selectAll.addEventListener('change', function () {
            const categoryId = this.id.replace('select-all-', '');
            const categoryCheckboxes = document.querySelectorAll('.' + categoryId + '-employee');

            categoryCheckboxes.forEach(function (checkbox) {
                checkbox.checked = selectAll.checked;
            });

            // Update count after select all
            updateEmployeeCount(categoryId);
        });
    });

    // Handle individual checkbox changes to update select-all states
    const allEmployeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    allEmployeeCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const categoryClass = this.className.split(' ').find(cls => cls.endsWith('-employee'));
            if (categoryClass) {
                const categoryType = categoryClass.replace('-employee', '');
                const categoryCheckboxes = document.querySelectorAll('.' + categoryClass);
                const categorySelectAll = document.getElementById('select-all-' + categoryType);

                if (categorySelectAll) {
                    const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(categoryCheckboxes).some(cb => cb.checked);
                    categorySelectAll.checked = allChecked;
                    categorySelectAll.indeterminate = someChecked && !allChecked;
                }

                // Update count after individual checkbox change
                updateEmployeeCount(categoryType);
            }
        });
    });

    // Function to update employee count
    function updateEmployeeCount(categoryType) {
        const categoryCheckboxes = document.querySelectorAll('.' + categoryType + '-employee');
        const selectedCount = Array.from(categoryCheckboxes).filter(cb => cb.checked).length;
        const totalCount = categoryCheckboxes.length;
        const countElement = document.getElementById(categoryType + '-count');

        if (countElement) {
            countElement.textContent = `(${selectedCount}/${totalCount})`;
        }
    }
});
