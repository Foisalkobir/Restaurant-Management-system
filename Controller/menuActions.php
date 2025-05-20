<?php
// Controller/menuActions.php – Handle create/update/delete for menu

session_start();
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/menuModel.php';

// Only managers/admins
if (!isset($_SESSION['user_id']) ||
    !in_array($_SESSION['user_role'] ?? '', ['manager','admin'], true)) {
    header('Location: ../View/home.php');
    exit;
}

$model  = new MenuModel($con);
$action = $_POST['action'] ?? '';

switch ($action) {

    // Create
    case 'createCategory':
        $ok = $model->createCategory(trim($_POST['name'] ?? ''));
        $_SESSION['menu_success'] = $ok ? 'Category created.' : 'Failed to create category.';
        break;

    case 'createItem':
        $ok = $model->createItem([
            'name'           => trim($_POST['name'] ?? ''),
            'category_id'    => (int)$_POST['category_id'],
            'price'          => (float)$_POST['price'],
            'dietary_tags'   => trim($_POST['dietary_tags'] ?? ''),
            'image_url'      => trim($_POST['image_url'] ?? ''),
            'available_from' => $_POST['available_from'] ?: null,
            'available_to'   => $_POST['available_to']   ?: null,
        ]);
        $_SESSION['menu_success'] = $ok ? 'Item created.' : 'Failed to create item.';
        break;

    // Update
    case 'updateCategory':
        $ok = $model->updateCategory((int)$_POST['id'], trim($_POST['name'] ?? ''));
        $_SESSION['menu_success'] = $ok ? 'Category updated.' : 'Failed to update category.';
        break;

    case 'updateItem':
        $ok = $model->updateItem((int)$_POST['id'], [
            'name'           => trim($_POST['name'] ?? ''),
            'category_id'    => (int)$_POST['category_id'],
            'price'          => (float)$_POST['price'],
            'dietary_tags'   => trim($_POST['dietary_tags'] ?? ''),
            'image_url'      => trim($_POST['image_url'] ?? ''),
            'available_from' => $_POST['available_from'] ?: null,
            'available_to'   => $_POST['available_to']   ?: null,
        ]);
        $_SESSION['menu_success'] = $ok ? 'Item updated.' : 'Failed to update item.';
        break;

    // Delete
    case 'deleteCategory':
        $ok = $model->deleteCategory((int)$_POST['id']);
        $_SESSION['menu_success'] = $ok ? 'Category deleted.' : 'Failed to delete category.';
        break;

    case 'deleteItem':
        $ok = $model->deleteItem((int)$_POST['id']);
        $_SESSION['menu_success'] = $ok ? 'Item deleted.' : 'Failed to delete item.';
        break;

    default:
        $_SESSION['menu_error'] = 'Unknown action.';
}

header('Location: ../View/menuEditor.php');
exit;
