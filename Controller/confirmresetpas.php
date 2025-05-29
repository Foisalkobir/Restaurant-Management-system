<?php 
session_start();
require_once('../model/userModel.php');

function renderMessagePage($message, $isError = true, $retryUrl = '') {
    $color = $isError ? '#e74c3c' : '#27ae60';
    $retryLink = $retryUrl ? "<br><a href='$retryUrl' style='color:#764ba2; text-decoration:underline;'>Try again</a>" : '';
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Password Reset</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
  }
  .message-box {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    max-width: 400px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
    color: $color;
  }
  a {
    color: #764ba2;
    text-decoration: underline;
    font-weight: 600;
  }
</style>
</head>
<body>
  <div class="message-box">
    <p>$message</p>
    $retryLink
  </div>
</body>
</html>
HTML;
    exit();
}

if(isset($_POST['answer'])) {
    $answer = trim($_POST['answer']);

    if (!isset($_SESSION['reset_user_id'])) {
        renderMessagePage("Session expired. Please restart the password reset process.", true, '../view/forgot_password.html');
    }

    $userId = $_SESSION['reset_user_id'];
    $user = getUserById($userId);

    if (!$user) {
        renderMessagePage("User not found.", true, '../view/forgot_password.html');
    }

    if (strcasecmp($answer, $user['answer']) !== 0) {
        renderMessagePage("Incorrect answer.", true, '../view/securityQuestion.php');
    }
    
    $_SESSION['can_reset_password'] = true;
    header('Location: ../view/reset_password.php');
    exit();
} else {
    renderMessagePage("Invalid request.", true, '../view/forgot_password.html');
}
?>
