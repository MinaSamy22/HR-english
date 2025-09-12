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
