<?php
// filepath: c:\xampp\htdocs\webtech\Project\View\profile.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.html");
    exit();
}

// Retrieve session data
$loggedInUser = $_SESSION['loggedInUser'];
$userRole = $_SESSION['userRole'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Profile Dashboard</title>
  <link rel="stylesheet" href="../css/profile.css" />
</head>
<body>
  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($loggedInUser); ?>!</h2>
    <p>Your role: <?php echo htmlspecialchars($userRole); ?></p>
    <div class="menu">
      <a href="viewprofile.html">View Profile</a>
      <a href="editprofile.html">Edit Profile</a>
      <a href="changeavatar.html">Change Avatar</a>
      <a href="updatepassword.html">Update Password</a>
    </div>
    <a href="../php/logout.php">Logout</a>
  </div>
</body>
</html>