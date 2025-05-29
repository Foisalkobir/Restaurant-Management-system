<?php 
session_start();
require_once('../model/userModel.php');

$info = $_REQUEST['info'];
$data = json_decode($info, true); 

if (isset($data['submit'])) { 
    $username = trim($data['username']);
    $password = trim($data['password']);
    $confirm_pass = trim($data['confirm_password']);
    $email = trim($data['email']);
    $account_type = trim($data['account_type']);
    $question = trim($data['question']);
    $answer = trim($data['answer']);

    if (empty($username) || empty($password) || empty($confirm_pass) || empty($email) || 
        empty($account_type) || empty($question) || empty($answer)) {
        echo "Enter all fields.";
    } elseif (strlen($username) < 4 || !ctype_alnum($username[0]) || strpos($username, ' ') !== false) {
        echo "Username must be at least 4 characters long, start with an alphanumeric character, and contain no spaces.";
    } elseif (strlen($password) < 4) {
        echo "Password must be at least 4 characters long.";
    } elseif ($password !== $confirm_pass) {
        echo "Passwords do not match.";
    } elseif (!isValidEmail($email)) {
        echo "Enter a valid email address.";
    } elseif (strlen($question) < 5 || strpos($question, "'") !== false || strpos($question, '"') !== false) {
        echo "Security question must be at least 5 characters long and cannot contain quotes.";
    } elseif (strpos($answer, "'") !== false || strpos($answer, '"') !== false) {
        echo "Security answer cannot contain quotes.";
    } elseif (userExists($username) == true) {
        echo "Username is already taken. Please choose another.";
    } else {
        $status = addUser($username, $email, $password, $account_type, $question, $answer);
        if ($status) {
            addNotificationNewUser( $username); 
            echo "success";
        } else {
            echo "Failed to register user. Try again.";
        }
    }
    
} 

else {
    echo "Invalid request";
}

function isValidEmail($email) {
    if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
        return true;
    }
    return false;
}
