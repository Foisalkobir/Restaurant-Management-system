<?php
session_start();
require_once('../model/db.php');

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

$con = getConnection();

$categories = [];
$catRes = mysqli_query($con, "SELECT id, name FROM categories ORDER BY name ASC");
while ($cat = mysqli_fetch_assoc($catRes)) {
    $categories[] = $cat;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $photo = trim($_POST['photo'] ?? '');
    $is_seasonal = isset($_POST['is_seasonal']) ? 1 : 0;
    $availability_start = $_POST['availability_start'] ?? null;
    $availability_end = $_POST['availability_end'] ?? null;

    if ($name === '' || $category_id <= 0 || $price <= 0) {
        $error = "Please fill in all required fields with valid data.";
    } elseif ($is_seasonal && (empty($availability_start) || empty($availability_end))) {
        $error = "Please specify availability start and end dates for seasonal items.";
    } else {
        $stmt = mysqli_prepare($con, "INSERT INTO menu_items (category_id, name, description, price, photo, is_seasonal, availability_start, availability_end) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issdsiss", $category_id, $name, $description, $price, $photo, $is_seasonal, $availability_start, $availability_end);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Menu item added successfully.";
        } else {
            $error = "Failed to add menu item.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add Menu Item - Admin</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<link rel="stylesheet" href="../asset/css/admin-menu-item.css" />
<script>
function toggleSeasonalDates() {
  const isSeasonal = document.getElementById('is_seasonal').checked;
  document.getElementById('seasonal_dates').style.display = isSeasonal ? 'block' : 'none';
}
</script>
</head>
<body>

<main>
  <h1>Add New Menu Item</h1>

  <?php if ($error): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
  <?php elseif ($success): ?>
    <p class="message success"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="category_id">Category *</label>
    <select id="category_id" name="category_id" required>
      <option value="">-- Select Category --</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="name">Item Name *</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required />

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

    <label for="price">Price ($) *</label>
    <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required />

    <label for="photo">Photo URL</label>
    <input type="url" id="photo" name="photo" placeholder="Optional image URL" value="<?= htmlspecialchars($_POST['photo'] ?? '') ?>" />

    <label><input type="checkbox" id="is_seasonal" name="is_seasonal" onchange="toggleSeasonalDates()" <?= isset($_POST['is_seasonal']) ? 'checked' : '' ?> /> Seasonal Item</label>

    <div id="seasonal_dates" style="display: <?= isset($_POST['is_seasonal']) ? 'block' : 'none' ?>;">
      <label for="availability_start">Availability Start</label>
      <input type="date" id="availability_start" name="availability_start" value="<?= htmlspecialchars($_POST['availability_start'] ?? '') ?>" />

      <label for="availability_end">Availability End</label>
      <input type="date" id="availability_end" name="availability_end" value="<?= htmlspecialchars($_POST['availability_end'] ?? '') ?>" />
    </div>

    <button type="submit">Add Item</button>
  </form>

  <a href="menu_management.php" class="back">← Back to Menu Management</a>
</main>

</body>
</html>
