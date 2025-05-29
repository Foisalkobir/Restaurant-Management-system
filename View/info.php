<?php
require_once('../model/siteInfoModel.php');
$info = getSiteInfo(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About Us | RestaurantPro</title>
    <link rel="stylesheet" href="../asset/css/info.css" />
</head>
<body>
  <header>
    <div class="header-content">
      <div class="logo">RestaurantPro</div>
      <nav class="nav-buttons">
        <a href="landingPage.html" class="nav-btn">Home</a>
        <a href="login.html" class="nav-btn">Login</a>
        <a href="signup.html" class="nav-btn">Signup</a>
        <a href="info.php" class="nav-btn">Info</a>
      </nav>
    </div>
  </header>

    <main class="main-content">
        <h1>About RestaurantPro</h1>
        <p><?= nl2br(htmlspecialchars($info['about_us'] ?? '')) ?></p>

        <section class="section">
            <h2>Our Services</h2>
            <?= nl2br(htmlspecialchars($info['services'] ?? '')) ?>
        </section>

        <section class="section">
            <h2>Why Choose RestaurantPro?</h2>
            <p><?= nl2br(htmlspecialchars($info['why_choose_us'] ?? '')) ?></p>
        </section>

        <section class="section">
            <h2>Contact Us</h2>
            <ul>
                <li>Email: <a href="mailto:<?= htmlspecialchars($info['contact_email'] ?? '') ?>"><?= htmlspecialchars($info['contact_email'] ?? '') ?></a></li>
                <li>Phone: <?= htmlspecialchars($info['contact_phone'] ?? '') ?></li>
                <li>Address: <?= nl2br(htmlspecialchars($info['contact_address'] ?? '')) ?></li>
            </ul>
        </section>
    </main>
</body>
</html>
