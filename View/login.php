<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Login – Restaurant Management System</title>
  <link rel="stylesheet" href="../Asset/css/style.css">
</head>
<body>
  <div class="auth-container">
    <?php if (!empty($_SESSION['login_errors'])): ?>
      <div class="error-messages">
        <?php foreach ($_SESSION['login_errors'] as $err): ?>
          <p class="error"><?= htmlspecialchars($err) ?></p>
        <?php endforeach; ?>
      </div>
      <?php unset($_SESSION['login_errors']); ?>
    <?php endif; ?>

    <form id="loginForm" action="../Controller/loginCheck.php" method="POST" novalidate>
      <h2>Login</h2>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="you@example.com">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required minlength="6" placeholder="••••••">
      </div>
      <button type="submit" class="btn">Login</button>
      <p class="small-text">Don't have an account? <a href="signup.html">Sign up</a></p>
    </form>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      var form = e.target;
      if (!form.checkValidity()) {
        e.preventDefault();
        alert('Please fill out the form correctly.');
      }
    });
  </script>
</body>
</html>
