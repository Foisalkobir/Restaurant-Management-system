<?php
require_once('db.php');

function getMenuItems() {
    $con = getConnection();
    $today = date('Y-m-d');
    $sql = "SELECT mi.id, mi.name, mi.description, mi.price, c.name AS category
            FROM menu_items mi
            JOIN categories c ON mi.category_id = c.id
            WHERE mi.is_seasonal = 0 OR (mi.availability_start <= ? AND mi.availability_end >= ?)
            ORDER BY c.name, mi.name";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $today, $today);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $items = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
    return $items;
}
?>
