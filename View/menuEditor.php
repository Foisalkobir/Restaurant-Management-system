<?php
// View/menuEditor.php – Manager interface to add/edit menu items and categories
session_start();
// Only managers or admins
if (!empty($_SESSION['user_id']) && in_array($_SESSION['user_role'] ?? '', ['manager','admin'], true)) {
    // OK
} else {
    header('Location: home.php');
    exit;
}

// Correct includes for Model files (relative to View/)
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/menuModel.php';

// Fetch data
$model      = new MenuModel($con);
$categories = $model->getAllCategories();
$items      = $model->getAllItems();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Menu Editor – Restaurant Management</title>
  <!-- Corrected CSS path -->
  <link rel="stylesheet" href="../Asset/css/style.css">

  <style>
    .editor-container { display: flex; gap: 20px; padding: 20px; }
    .categories, .items {
      flex: 1;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .categories h2, .items h2 { margin-top: 0; }
    .category-list, .item-list { list-style:none; padding:0; }
    .category-list li, .item-list li {
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 8px;
      background: #f9f9f9;
      border-radius: 4px;
    }
    .modal {
      display: none;
      position: fixed; top:0; left:0;
      width:100%; height:100%;
      background: rgba(0,0,0,0.5);
      align-items:center; justify-content:center;
      z-index:100;
    }
    .modal-content {
      background:#fff; padding:20px;
      border-radius:8px; width:400px;
    }
    .modal-content .form-group { margin-bottom:15px; }
    .modal-content input, .modal-content select {
      width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;
    }
  </style>
</head>
<body>
  <?php include 'nav.php'; ?>

  <div class="container">
    <?php if (!empty($_SESSION['menu_success'])): ?>
      <p class="success"><?= htmlspecialchars($_SESSION['menu_success']) ?></p>
      <?php unset($_SESSION['menu_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['menu_error'])): ?>
      <p class="error"><?= htmlspecialchars($_SESSION['menu_error']) ?></p>
      <?php unset($_SESSION['menu_error']); ?>
    <?php endif; ?>
  </div>

  <main class="container">
    <h1>Menu Editor</h1>
    <div class="editor-container">

      <!-- CATEGORIES COLUMN -->
      <div class="categories">
        <h2>Categories</h2>
        <ul class="category-list">
          <?php foreach ($categories as $cat): ?>
            <li>
              <span><?= htmlspecialchars($cat['name']) ?></span>
              <span>
                <button class="editCatBtn"
                        data-id="<?= $cat['id'] ?>"
                        data-name="<?= htmlspecialchars($cat['name']) ?>">
                  Edit
                </button>
                <form method="POST" action="../Controller/menuActions.php" style="display:inline">
                  <input type="hidden" name="action" value="deleteCategory">
                  <input type="hidden" name="id"     value="<?= $cat['id'] ?>">
                  <button type="submit" onclick="return confirm('Delete this category?')">
                    Delete
                  </button>
                </form>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
        <button id="addCategoryBtn" class="btn">Add Category</button>

        <!-- Create Category Modal -->
        <div id="categoryModal" class="modal">
          <div class="modal-content">
            <h3>New Category</h3>
            <form method="POST" action="../Controller/menuActions.php">
              <input type="hidden" name="action" value="createCategory">
              <div class="form-group">
                <label for="cat_name">Category Name</label>
                <input type="text" id="cat_name" name="name" required>
              </div>
              <button type="submit" class="btn">Save</button>
              <button type="button" class="btn btn-secondary" id="cancelCategory">Cancel</button>
            </form>
          </div>
        </div>

        <!-- Edit Category Modal -->
        <div id="editCategoryModal" class="modal">
          <div class="modal-content">
            <h3>Edit Category</h3>
            <form id="editCategoryForm" method="POST" action="../Controller/menuActions.php">
              <input type="hidden" name="action" value="updateCategory">
              <input type="hidden" name="id" id="edit_cat_id">
              <div class="form-group">
                <label for="edit_cat_name">Name</label>
                <input type="text" id="edit_cat_name" name="name" required>
              </div>
              <button type="submit" class="btn">Save</button>
              <button type="button" class="btn btn-secondary" id="cancelEditCat">Cancel</button>
            </form>
          </div>
        </div>
      </div>

      <!-- ITEMS COLUMN -->
      <div class="items">
        <h2>Items</h2>
        <ul class="item-list">
          <?php foreach ($items as $it): ?>
            <li>
              <span>
                <?= htmlspecialchars($it['name']) ?>
                (<?= htmlspecialchars($it['category_name']) ?>)
              </span>
              <span>
                <button class="editItemBtn"
                        data-id="<?= $it['id'] ?>"
                        data-name="<?= htmlspecialchars($it['name']) ?>"
                        data-category="<?= htmlspecialchars($it['category_name']) ?>"
                        data-price="<?= htmlspecialchars($it['price']) ?>"
                        data-tags="<?= htmlspecialchars($it['dietary_tags'] ?? '') ?>"
                        data-image="<?= htmlspecialchars($it['image_url'] ?? '') ?>"
                        data-from="<?= htmlspecialchars($it['available_from'] ?? '') ?>"
                        data-to="<?= htmlspecialchars($it['available_to'] ?? '') ?>">
                  Edit
                </button>
                <form method="POST" action="../Controller/menuActions.php" style="display:inline">
                  <input type="hidden" name="action" value="deleteItem">
                  <input type="hidden" name="id"     value="<?= $it['id'] ?>">
                  <button type="submit" onclick="return confirm('Delete this item?')">
                    Delete
                  </button>
                </form>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
        <button id="addItemBtn" class="btn">Add Item</button>

        <!-- Create Item Modal -->
        <div id="itemModal" class="modal">
          <div class="modal-content">
            <h3>New Item</h3>
            <form id="itemForm" method="POST" action="../Controller/menuActions.php">
              <input type="hidden" name="action" value="createItem">
              <div class="form-group">
                <label for="item_name">Name</label>
                <input type="text" id="item_name" name="name" required>
              </div>
              <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                  <option value="">Select…</option>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" id="price" name="price" required>
              </div>
              <div class="form-group">
                <label for="dietary_tags">Dietary Tags</label>
                <input type="text" id="dietary_tags" name="dietary_tags" placeholder="e.g. Vegan,Gluten-Free">
              </div>
              <div class="form-group">
                <label for="image_url">Image URL</label>
                <input type="text" id="image_url" name="image_url" placeholder="path/or/url">
              </div>
              <div class="form-group">
                <label for="available_from">Available From</label>
                <input type="date" id="available_from" name="available_from">
              </div>
              <div class="form-group">
                <label for="available_to">Available To</label>
                <input type="date" id="available_to" name="available_to">
              </div>
              <button type="submit" class="btn">Save</button>
              <button type="button" class="btn btn-secondary" id="cancelItem">Cancel</button>
            </form>
          </div>
        </div>

        <!-- Edit Item Modal -->
        <div id="editItemModal" class="modal">
          <div class="modal-content">
            <h3>Edit Item</h3>
            <form id="editItemForm" method="POST" action="../Controller/menuActions.php">
              <input type="hidden" name="action" value="updateItem">
              <input type="hidden" name="id" id="edit_item_id">
              <div class="form-group">
                <label for="edit_item_name">Name</label>
                <input type="text" id="edit_item_name" name="name" required>
              </div>
              <div class="form-group">
                <label for="edit_category_id">Category</label>
                <select id="edit_category_id" name="category_id" required>
                  <option value="">Select…</option>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="edit_price">Price</label>
                <input type="number" step="0.01" id="edit_price" name="price" required>
              </div>
              <div class="form-group">
                <label for="edit_dietary_tags">Dietary Tags</label>
                <input type="text" id="edit_dietary_tags" name="dietary_tags">
              </div>
              <div class="form-group">
                <label for="edit_image_url">Image URL</label>
                <input type="text" id="edit_image_url" name="image_url">
              </div>
              <div class="form-group">
                <label for="edit_available_from">Available From</label>
                <input type="date" id="edit_available_from" name="available_from">
              </div>
              <div class="form-group">
                <label for="edit_available_to">Available To</label>
                <input type="date" id="edit_available_to" name="available_to">
              </div>
              <button type="submit" class="btn">Save</button>
              <button type="button" class="btn btn-secondary" id="cancelEditItem">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Add / cancel category
    document.getElementById('addCategoryBtn').onclick = () =>
      document.getElementById('categoryModal').style.display = 'flex';
    document.getElementById('cancelCategory').onclick = () =>
      document.getElementById('categoryModal').style.display = 'none';

    // Edit Category
    document.querySelectorAll('.editCatBtn').forEach(btn => {
      btn.onclick = () => {
        document.getElementById('edit_cat_id').value   = btn.dataset.id;
        document.getElementById('edit_cat_name').value = btn.dataset.name;
        document.getElementById('editCategoryModal').style.display = 'flex';
      };
    });
    document.getElementById('cancelEditCat').onclick = () =>
      document.getElementById('editCategoryModal').style.display = 'none';

    // Add / cancel item
    document.getElementById('addItemBtn').onclick = () =>
      document.getElementById('itemModal').style.display = 'flex';
    document.getElementById('cancelItem').onclick = () =>
      document.getElementById('itemModal').style.display = 'none';

    // Edit Item
    document.querySelectorAll('.editItemBtn').forEach(btn => {
      btn.onclick = () => {
        document.getElementById('edit_item_id').value        = btn.dataset.id;
        document.getElementById('edit_item_name').value      = btn.dataset.name;
        document.getElementById('edit_price').value          = btn.dataset.price;
        document.getElementById('edit_dietary_tags').value   = btn.dataset.tags;
        document.getElementById('edit_image_url').value      = btn.dataset.image;
        document.getElementById('edit_available_from').value = btn.dataset.from;
        document.getElementById('edit_available_to').value   = btn.dataset.to;
        // category select by value
        document.getElementById('edit_category_id').value =
          Array.from(document.getElementById('edit_category_id').options)
               .find(o => o.text === btn.dataset.category).value;
        document.getElementById('editItemModal').style.display = 'flex';
      };
    });
    document.getElementById('cancelEditItem').onclick = () =>
      document.getElementById('editItemModal').style.display = 'none';
  </script>
</body>
</html>
