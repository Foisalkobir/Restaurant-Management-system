function validateEmail() {
    let email = document.getElementById("email").value;
    let message = document.getElementById("emailMessage");

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email.trim() === "") {
        message.textContent = "Email cannot be empty.";
        message.style.color = "red";
        return false;
    } else if (!emailPattern.test(email)) {
        message.textContent = "Enter a valid email address.";
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

    if (password.trim() === "") {
        message.textContent = "Password cannot be empty.";
        message.style.color = "red";
        return false;
    } else {
        message.textContent = "Valid";
        message.style.color = "green";
        return true;
    }
}

function ajaxLogin() {
    if (!validateEmail() || !validatePassword()) {
        alert("Invalid data. Please correct the errors and try again.");
        return;
    }

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../controller/logincheck.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(
        `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&submit=true`
    );

    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let response = this.responseText.trim();

            if (response === "success") {
                window.location.href = "../view/home.php";
            } else if (response === "invalid") {
                alert("Invalid email or password!");
            } else {
                alert(response);
            }
        }
    };
}
