<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'user') {
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Order Confirmed - RestaurantPro</title>
<style>
  body { font-family: Arial, sans-serif; background: #fafafa; margin: 0; padding: 2rem; max-width: 600px; margin: auto; text-align: center;}
  h1 { color: #28a745; }
  a { color: #667eea; text-decoration: none; }
</style>
</head>
<body>

<h1>Thank you for your order!</h1>
<p>Your order has been successfully placed and is being prepared.</p>
<p><a href="menu.php">Back to Menu</a></p>
<p><a href="home.php">Go to Dashboard</a></p>

</body>
</html>
