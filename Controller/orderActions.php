<?php
// Controller/orderActions.php – Handle order status updates
session_start();
// Only logged-in users
if (!isset($_SESSION['user_id'])) {
    header('Location: ../View/login.php');
    exit;
}

require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/orderModel.php';
$model = new OrderModel($con);

$action = $_POST['action'] ?? '';
switch ($action) {
    case 'updateStatus':
        $orderId   = intval($_POST['order_id'] ?? 0);
        $newStatus = $_POST['status'] ?? '';
        $ticketAge = intval($_POST['ticket_age'] ?? 0);
        // Increase age by 5 minutes when marking Ready
        if ($newStatus === 'Ready') {
            $ticketAge += 5;
        }
        if ($model->updateOrderStatus($orderId, $newStatus, $ticketAge)) {
            $_SESSION['order_success'] = "Order #$orderId marked $newStatus.";
        } else {
            $_SESSION['order_error'] = "Failed to update order #$orderId.";
        }
        break;

    case 'deleteOrder':
        $orderId = intval($_POST['order_id'] ?? 0);
        if ($model->deleteOrder($orderId)) {
            $_SESSION['order_success'] = "Order #$orderId deleted.";
        } else {
            $_SESSION['order_error'] = "Failed to delete order #$orderId.";
        }
        break;

    default:
        $_SESSION['order_error'] = 'Unknown action.';
        break;
}

header('Location: ../View/orders.php');
exit;
