<?php

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($name == "") {
        echo "Full Name is required.";
    } else if ($age < 13 || $age > 120) {
        echo "Please enter a valid age between 13 and 120.";
    } else if (!preg_match('/^\d{10}$/', $contact)) {
        echo "Contact Number must be a valid 10-digit number.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else if (strlen($password) < 6) {
        echo "Password must be at least 6 characters long.";
    } else if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        echo "Form submitted successfully!";
    }
} else {
    echo "Invalid request! Please submit the form!";
}

?>