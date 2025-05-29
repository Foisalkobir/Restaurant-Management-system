<?php

require_once('db.php');

function deleteOrder($orderId) {
    $con = getConnection();
    $orderId = (int)$orderId;

    $sqlItems = "DELETE FROM order_items WHERE order_id = ?";
    $stmtItems = mysqli_prepare($con, $sqlItems);
    if (!$stmtItems) return false;
    mysqli_stmt_bind_param($stmtItems, 'i', $orderId);
    mysqli_stmt_execute($stmtItems);
    mysqli_stmt_close($stmtItems);

    $sqlOrder = "DELETE FROM orders WHERE order_id = ?";
    $stmtOrder = mysqli_prepare($con, $sqlOrder);
    if (!$stmtOrder) return false;
    mysqli_stmt_bind_param($stmtOrder, 'i', $orderId);
    $result = mysqli_stmt_execute($stmtOrder);
    mysqli_stmt_close($stmtOrder);

    mysqli_close($con);
    return $result;
}

function getOrderById($orderId) {
    $con = getConnection();
    $orderId = (int)$orderId;
    $sql = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    return $order;
}


function getUserOrders($userId) {
    $con = getConnection();

    $sql = "SELECT order_id, order_date, status, total_amount FROM orders WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    return $orders;
}

function getAllOrders() {
    $con = getConnection();
    $sql = "SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC";
    $result = mysqli_query($con, $sql);
    $orders = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }
    mysqli_close($con);
    return $orders;
}

function updateOrder($orderId, $payment_method, $address, $phone, $status) {
    $con = getConnection();
    $sql = "UPDATE orders SET payment_method = ?, address = ?, phone = ?, status = ? WHERE order_id = ?";
    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($con));
        mysqli_close($con);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ssssi", $payment_method, $address, $phone, $status, $orderId);

    $success = mysqli_stmt_execute($stmt);

    if (!$success) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
    return $success;
}

function addOrder($user_id, $payment_method, $address, $phone, $total_amount, $status) {
    $con = getConnection();
    $sql = "INSERT INTO orders (user_id, order_date, payment_method, address, phone, total_amount, status)
            VALUES (?, NOW(), ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt === false) {
        error_log("Prepare failed: " . mysqli_error($con));
        return false;
    }
    mysqli_stmt_bind_param($stmt, "isssds", $user_id, $payment_method, $address, $phone, $total_amount, $status);
    $exec = mysqli_stmt_execute($stmt);
    if (!$exec) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    $order_id = mysqli_insert_id($con);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    return $order_id;
}

?>
