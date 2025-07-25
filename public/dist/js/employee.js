document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("roleSelect");
    const passwordFieldWrapper = document.getElementById("passwordField");

    // Show/hide password input based on role
    roleSelect.addEventListener("change", function () {
        if (this.value === "1") {
            passwordFieldWrapper.style.display = "flex";
        } else {
            passwordFieldWrapper.style.display = "none";
        }
    });

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
