<?php
require_once __DIR__ . '/db.php';

class OrderModel {
    private $con;

    public function __construct($connection) {
        $this->con = $connection;
    }

    public function getAllOrders(string $status = null): array {
        $sql = "SELECT o.id, o.table_number, o.order_datetime, o.status, o.ticket_age, \
                       GROUP_CONCAT(CONCAT(i.name, ' (x', od.quantity, ')') SEPARATOR ', ') AS items\n                FROM orders o\n                JOIN order_details od ON o.id = od.order_id\n                JOIN items i ON od.item_id = i.id";
        if ($status) {
            $sql .= " WHERE o.status = '" . mysqli_real_escape_string($this->con, $status) . "'";
        }
        $sql .= " GROUP BY o.id ORDER BY o.order_datetime";
        $result = mysqli_query($this->con, $sql);
        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        return $orders;
    }

    public function createOrder(array $data): bool {
        mysqli_begin_transaction($this->con);
        try {
            $stmt = mysqli_prepare($this->con,
                "INSERT INTO orders (table_number, order_datetime, status, ticket_age)\n                 VALUES (?, NOW(), 'In Progress', 0)"
            );
            mysqli_stmt_bind_param($stmt, 'i', $data['table_number']);
            mysqli_stmt_execute($stmt);
            $orderId = mysqli_insert_id($this->con);
            mysqli_stmt_close($stmt);

            $stmtDetail = mysqli_prepare($this->con,
                "INSERT INTO order_details (order_id, item_id, quantity)\n                 VALUES (?, ?, ?)"
            );
            foreach ($data['items'] as $item) {
                mysqli_stmt_bind_param($stmtDetail, 'iii', $orderId, $item['id'], $item['quantity']);
                mysqli_stmt_execute($stmtDetail);
            }
            mysqli_stmt_close($stmtDetail);

            mysqli_commit($this->con);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }

    public function updateOrderStatus(int $id, string $status, int $ticket_age): bool {
        $stmt = mysqli_prepare(
            $this->con,
            "UPDATE orders SET status = ?, ticket_age = ? WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, 'sii', $status, $ticket_age, $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function deleteOrder(int $id): bool {
        $stmt = mysqli_prepare($this->con, "DELETE FROM orders WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}
?>
