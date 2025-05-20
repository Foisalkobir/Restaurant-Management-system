<?php
// View/customerMenu.php - Public customer view of the digital menu

session_start();
// Only logged-in users can view menu; otherwise redirect to login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Use correct path to the Model files
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/menuModel.php';

$model = new MenuModel($con);
$categories = $model->getAllCategories();
$allItems = $model->getAllItems();

// Build a list of distinct dietary tags
$tagSet = [];
foreach ($allItems as $item) {
    if (!empty($item['dietary_tags'])) {
        foreach (explode(',', $item['dietary_tags']) as $tag) {
            $t = trim($tag);
            if ($t && !in_array($t, $tagSet)) {
                $tagSet[] = $t;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu - Restaurant Management System</title>
  <link rel="stylesheet" href="../Asset/css/style.css">
  <style>
    .filter-bar { margin: 20px 0; display: flex; gap: 20px; align-items: center; }
    .tabs { display: flex; gap: 10px; margin-bottom: 20px; }
    .tabs button { padding: 8px 12px; border: none; border-radius: 4px; background: #ddd; cursor: pointer; }
    .tabs button.active { background: #28a745; color: #fff; }
    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 20px; }
    .menu-item { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .menu-item img { max-width: 100%; border-radius: 4px; margin-bottom: 10px; }
    .hidden { display: none !important; }
  </style>
</head>
<body>
  <?php include 'nav.php'; ?>
  <main class="container">
    <h1>Our Menu</h1>
    <div class="filter-bar">
      <label for="tagFilter">Filter by dietary:</label>
      <select id="tagFilter">
        <option value="">All</option>
        <?php foreach ($tagSet as $tag): ?>
          <option value="<?= htmlspecialchars($tag) ?>"><?= htmlspecialchars($tag) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="tabs" id="categoryTabs">
      <button class="active" data-cat="all">All</button>
      <?php foreach ($categories as $cat): ?>
        <button data-cat="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['name']) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="menu-grid" id="menuGrid">
      <?php foreach ($allItems as $item): ?>
        <?php
          $tags = explode(',', $item['dietary_tags'] ?? '');
          $tagClasses = '';
          foreach ($tags as $t) {
              $t = trim($t);
              if ($t) {
                  $tagClasses .= ' tag-' . preg_replace('/[^a-z0-9]+/i','', strtolower($t));
              }
          }
          // Fix image URL to point to Asset/images if it doesn't already have full path
          $imgUrl = '';
          if (!empty($item['image_url'])) {
              $imgUrl = (strpos($item['image_url'], 'http') === 0 || strpos($item['image_url'], '/') === 0)
                ? $item['image_url']
                : '../Asset/images/' . $item['image_url'];
          }
        ?>
        <div class="menu-item<?= $tagClasses ?>" data-cat="<?= htmlspecialchars($item['category_id']) ?>" data-tags="<?= htmlspecialchars($item['dietary_tags']) ?>">
          <?php if (!empty($imgUrl)): ?>
            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          <?php endif; ?>
          <h3><?= htmlspecialchars($item['name']) ?></h3>
          <p>Price: $<?= number_format($item['price'],2) ?></p>
          <?php if (!empty($item['dietary_tags'])): ?>
            <p>Tags: <?= htmlspecialchars($item['dietary_tags']) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
  <script>
    const grid = document.getElementById('menuGrid');
    const tabs = document.querySelectorAll('#categoryTabs button');
    const tagFilter = document.getElementById('tagFilter');

    // Category tab click
    tabs.forEach(btn => btn.addEventListener('click', () => {
      tabs.forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      filterItems();
    }));

    // Dietary filter change
    tagFilter.addEventListener('change', filterItems);

    function filterItems() {
      const selectedCat = document.querySelector('#categoryTabs button.active').getAttribute('data-cat');
      const selectedTag = tagFilter.value;
      document.querySelectorAll('.menu-item').forEach(item => {
        const cat = item.getAttribute('data-cat');
        const tags = item.getAttribute('data-tags').split(',').map(t => t.trim());
        let visible = (selectedCat==='all' || cat===selectedCat);
        if (selectedTag && !tags.includes(selectedTag)) visible = false;
        item.classList.toggle('hidden', !visible);
      });
    }
  </script>
</body>
</html>
