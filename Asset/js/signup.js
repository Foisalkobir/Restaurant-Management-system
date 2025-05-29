function validateUsername() {
    let username = document.getElementById("username").value;
    let message = document.getElementById("usernameMessage");

    if (username.trim() === "" || username.length < 4 || !/^[a-zA-Z][a-zA-Z0-9_@]*$/.test(username)) {
        message.textContent = "Username must be at least 4 characters long, start with a letter, and contain no spaces.";
        message.style.color = "red";
        return false;
    } else {
        message.textContent = "Valid";
        message.style.color = "green";
        return true;
    }
}

function validatePassword() {
    let password = document.getElementById("password").value;
    let message = document.getElementById("passwordMessage");

    if (password.trim() === "" || password.length < 4) {
        message.textContent = "Password must be at least 4 characters long.";
        message.style.color = "red";
        return false;
    } else {
        message.textContent = "Valid";
        message.style.color = "green";
        return true;
    }
}

function validateConfirmPassword() {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let message = document.getElementById("confirmPasswordMessage");

    if (confirmPassword !== password) {
        message.textContent = "Passwords do not match.";
        message.style.color = "red";
        return false;
    } else {
        message.textContent = "Valid";
        message.style.color = "green";
        return true;
    }
}

function validateEmail() {
    let email = document.getElementById("email").value;
    let message = document.getElementById("emailMessage");

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        message.textContent = "Enter a valid email address.";
        message.style.color = "red";
        return false;
    } else {
        message.textContent = "Valid";
        message.style.color = "green";
        return true;
    }
}

function validateAccountType() {
    let accountType = document.getElementById("accountType").value;
    let message = document.getElementById("accountTypeMessage");

    if (accountType.trim() === "") {
        message.textContent = "Account type cannot be empty.";
        message.style.color = "red";
        return false;
    } else {
        message.textContent = "Valid";
        message.style.color = "green";
        return true;
    }
}

function validateQuestion() {
    let question = document.getElementById("securityQuestion").value;
    let message = document.getElementById("securityQuestionMessage");

    if (question.trim() === "" || question.length < 5 || /['"]/.test(question)) {
        message.textContent = "Security question must be at least 5 characters long and cannot contain quotes.";
        message.style.color = "red";
        return false;
    } else {
        message.textContent = "Valid";
        message.style.color = "green";
        return true;
    }
}

function validateAnswer() {
    let answer = document.getElementById("securityAnswer").value;
    let message = document.getElementById("securityAnswerMessage");

    if (/['"]/.test(answer)) {
        message.textContent = "Security answer cannot contain quotes.";
        message.style.color = "red";
        return false;
    } else {
        message.textContent = "Valid";
        message.style.color = "green";
        return true;
    }
}

function ajaxSignup() {
    if (!validateUsername() || !validatePassword() || !validateConfirmPassword() ||
        !validateEmail() || !validateAccountType() || !validateQuestion() || !validateAnswer()) {
        alert("Invalid data");
        return;
    }

    let data = {
        username: document.getElementById("username").value,
        password: document.getElementById("password").value,
        confirm_password: document.getElementById("confirmPassword").value,
        email: document.getElementById("email").value,
        account_type: document.getElementById("accountType").value,
        question: document.getElementById("securityQuestion").value,
        answer: document.getElementById("securityAnswer").value,
        submit: true
    };

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../controller/signupcheck.php", true);
    xhttp.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
    let info = JSON.stringify(data);
    xhttp.send("info=" + encodeURIComponent(info)); 

    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let response = this.responseText.trim();

            if (response === "success") {
                alert("Registration successful! Redirecting to login page.");
                window.location.href = "../view/login.html";
            } else {
                alert(response);
            }
        }
    };
}
