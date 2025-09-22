document.addEventListener("DOMContentLoaded", function () {

    // Toggle password visibility
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("togglePassword");

    if (passwordInput && eyeIcon) {
        eyeIcon.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        });
    }
});

        document.addEventListener("DOMContentLoaded", function() {
            const shiftSelect = document.getElementById("shift_count");
            const secondShiftFields = document.getElementById("secondShiftFields");

            function toggleSecondShift() {
                if (shiftSelect.value === "2") {
                    secondShiftFields.style.display = "block";
                } else {
                    secondShiftFields.style.display = "none";
                }
            }

            shiftSelect.addEventListener("change", toggleSecondShift);
            toggleSecondShift(); // للتشغيل وقت التحميل
        });

        $(document).on('click', '.delete-btn', function () {
    let deleteId = $(this).data('id');
    let deleteUrl = $(this).data('url');

    Swal.fire({
        title: deleteEmployeeTranslations.title,
        text: deleteEmployeeTranslations.text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: deleteEmployeeTranslations.confirm,
        cancelButtonText: deleteEmployeeTranslations.cancel
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: 'GET', // ✅ your route uses GET
                success: function () {
                    $('button.delete-btn[data-id="' + deleteId + '"]').closest('tr').fadeOut();

                    Swal.fire({
                        title: deleteEmployeeTranslations.deleted,
                        text: deleteEmployeeTranslations.success,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function () {
                    Swal.fire({
                        title: deleteEmployeeTranslations.error,
                        text: deleteEmployeeTranslations.failed,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    });
});
