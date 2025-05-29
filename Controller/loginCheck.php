<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../model/userModel.php');

if (isset($_POST['submit'])) { 
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "Null email/password";
    } else {
        $status = loginByEmail($email, $password); // Use function to login by email
        if ($status) {
            setcookie('status', 'true', time() + 3600, '/');
            $_SESSION['status'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['user'] = getUserByEmail($email);

            // Optionally, add welcome notification or other logic here

            echo "success";
        } else {
            echo "invalid";
        }
    }
} else {
    echo "Invalid request";
}
?>
