<?php
session_start();
require_once('../model/userModel.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!$email) {
        echo "Please provide your email.";
        exit;
    }

    $user = getUserByEmail($email);

    if (!$user) {
        echo "No user found with this email. <a href='forgot_password.html'>Try again</a>";
        exit;
    }

    $_SESSION['reset_user_id'] = $user['id'];
    $_SESSION['reset_question'] = $user['question'];
} else {
    header('Location: forgot_password.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Security Question</title>
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
  fieldset {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    width: 400px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }
  legend {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ff6b6b;
  }
  h2 {
    margin-bottom: 1rem;
    color: #333;
  }
  input[type="text"] {
    width: 100%;
    padding: 10px;
    margin: 1rem 0 1.5rem 0;
    border: 2px solid #764ba2;
    border-radius: 8px;
    font-size: 1rem;
    box-sizing: border-box;
  }
  input[type="submit"] {
    width: 100%;
    background: linear-gradient(45deg, #ff6b6b, #764ba2);
    border: none;
    padding: 12px 0;
    border-radius: 30px;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  input[type="submit"]:hover {
    background: linear-gradient(45deg, #764ba2, #ff6b6b);
  }
  a {
    display: block;
    text-align: center;
    margin-top: 1rem;
    color: #fff;
    text-decoration: underline;
  }
</style>
</head>
<body>

<form method="POST" action="../controller/confirmresetpas.php">
  <fieldset>
    <legend>Security Question</legend>
    <h2>Answer your security question</h2>
    <p><strong><?= htmlspecialchars($_SESSION['reset_question']) ?></strong></p>
    <label for="answer">Your Answer:</label>
    <input type="text" id="answer" name="answer" required />
    <input type="submit" value="Submit" />
    <a href="forgot_password.html">Cancel</a>
  </fieldset>
</form>

</body>
</html>
