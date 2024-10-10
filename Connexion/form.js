function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password-input");
    var eyeIcon = document.getElementById("eye-icon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.className = "slashed-eye";
    } else {
        passwordInput.type = "password";
        eyeIcon.className = "eye";
    }
}

function togglePasswordVisibility2() {
    var passwordInput = document.getElementById("cpassword");
    var eyeIcon = document.getElementById("eye-icon2");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.className = "slashed-eye";
    } else {
        passwordInput.type = "password";
        eyeIcon.className = "eye";
    }
}