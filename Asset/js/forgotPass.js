function validateEmail() {
  const emailInput = document.getElementById("email");
  const message = document.getElementById("emailMessage");
  const email = emailInput.value.trim();

  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!regex.test(email)) {
    message.textContent = "Please enter a valid email address.";
    message.style.color = "red";
    return false;
  }
  message.textContent = "Valid";
  message.style.color = "green";
  return true;
}

function validateForgotEmail() {
  return validateEmail();
}

function validatePassword() {
  const pass = document.getElementById("password").value.trim();
  const message = document.getElementById("passwordMessage");

  if (pass.length < 4) {
    message.textContent = "Password must be at least 4 characters long.";
    message.style.color = "red";
    return false;
  }
  message.textContent = "Valid";
  message.style.color = "green";
  return true;
}

function validateConfirmPassword() {
  const pass = document.getElementById("password").value.trim();
  const confirmPass = document.getElementById("confirm_password").value.trim();
  const message = document.getElementById("confirmPasswordMessage");

  if (pass !== confirmPass) {
    message.textContent = "Passwords do not match.";
    message.style.color = "red";
    return false;
  }
  message.textContent = "Valid";
  message.style.color = "green";
  return true;
}

function validateResetPassword() {
  return validatePassword() && validateConfirmPassword();
}

function validateResetPassword() {
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirm_password');
  const passwordMessage = document.getElementById('passwordMessage');
  const confirmPasswordMessage = document.getElementById('confirmPasswordMessage');

  let valid = true;

  if (password.value.trim().length < 4) {
    passwordMessage.textContent = "Password must be at least 4 characters long.";
    passwordMessage.style.color = "red";
    valid = false;
  } else {
    passwordMessage.textContent = "Valid";
    passwordMessage.style.color = "green";
  }

  if (confirmPassword.value !== password.value) {
    confirmPasswordMessage.textContent = "Passwords do not match.";
    confirmPasswordMessage.style.color = "red";
    valid = false;
  } else {
    confirmPasswordMessage.textContent = "Valid";
    confirmPasswordMessage.style.color = "green";
  }

  return valid;
}
