<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

require_once('../model/siteInfoModel.php');

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $about_us = $_POST['about_us'] ?? '';
    $services = $_POST['services'] ?? '';
    $why_choose_us = $_POST['why_choose_us'] ?? '';
    $email = $_POST['contact_email'] ?? '';
    $phone = $_POST['contact_phone'] ?? '';
    $address = $_POST['contact_address'] ?? '';

    if (!$about_us || !$services || !$why_choose_us || !$email || !$phone || !$address) {
        $errorMsg = "All fields are required.";
    } else {
        if (updateSiteInfo($about_us, $services, $why_choose_us, $email, $phone, $address)) {
            $successMsg = "Site info updated successfully.";
        } else {
            $errorMsg = "Failed to update site info.";
        }
    }
}

$info = getSiteInfo();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Site Information - Admin</title>
<link rel="stylesheet" href="../asset/css/info.css" />
<style>
    form { max-width: 700px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 0 15px rgba(0,0,0,0.1);}
    label { display: block; margin-top: 1rem; font-weight: 600; }
    textarea, input[type=text], input[type=email] {
        width: 100%; padding: 10px; border: 2px solid #764ba2; border-radius: 6px; font-size: 1rem; box-sizing: border-box;
    }
    button {
        margin-top: 1.5rem; background: linear-gradient(45deg, #ff6b6b, #764ba2); color: white; border: none; padding: 12px 20px; border-radius: 30px;
        font-size: 1.1rem; cursor: pointer; box-shadow: 0 5px 15px rgba(255,107,107,0.6); transition: all 0.3s ease;
    }
    button:hover {
        box-shadow: 0 8px 20px rgba(255,107,107,0.85); transform: translateY(-3px);
    }
    .error { color: #e74c3c; margin-top: 1rem; }
    .success { color: #27ae60; margin-top: 1rem; }
</style>
</head>
<body>

<header>
    <div class="header-content">
        <div class="logo">RestaurantPro Admin</div>
        <div class="nav-buttons">
            <a href="home.php" class="nav-btn">Dashboard</a>
            <!-- <a href="infoedit.php" class="nav-btn active">Edit Info</a> -->
            <a href="../controller/logout.php" class="nav-btn">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    <h1>Edit Site Information</h1>

    <?php if ($errorMsg): ?>
        <p class="error"><?= htmlspecialchars($errorMsg) ?></p>
    <?php elseif ($successMsg): ?>
        <p class="success"><?= htmlspecialchars($successMsg) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="about_us">About Us</label>
        <textarea id="about_us" name="about_us" rows="5" required><?= htmlspecialchars($info['about_us'] ?? '') ?></textarea>

        <label for="services">Our Services</label>
        <textarea id="services" name="services" rows="5" required><?= htmlspecialchars($info['services'] ?? '') ?></textarea>

        <label for="why_choose_us">Why Choose Us</label>
        <textarea id="why_choose_us" name="why_choose_us" rows="4" required><?= htmlspecialchars($info['why_choose_us'] ?? '') ?></textarea>

        <label for="contact_email">Contact Email</label>
        <input type="email" id="contact_email" name="contact_email" required value="<?= htmlspecialchars($info['contact_email'] ?? '') ?>" />

        <label for="contact_phone">Contact Phone</label>
        <input type="text" id="contact_phone" name="contact_phone" required value="<?= htmlspecialchars($info['contact_phone'] ?? '') ?>" />

        <label for="contact_address">Contact Address</label>
        <textarea id="contact_address" name="contact_address" rows="3" required><?= htmlspecialchars($info['contact_address'] ?? '') ?></textarea>

        <button type="submit">Update Information</button>
    </form>
</main>

</body>
</html>
