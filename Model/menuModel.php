<?php
require_once __DIR__ . '/db.php';

class MenuModel {
    private $con;

    public function __construct($connection) {
        $this->con = $connection;
    }

    public function getAllCategories(): array {
        $sql = "SELECT id, name FROM categories ORDER BY name";
        $result = mysqli_query($this->con, $sql);
        $cats = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $cats[] = $row;
        }
        return $cats;
    }

    public function getAllItems(): array {
        $sql = "SELECT
                    i.id,
                    i.name,
                    i.price,
                    i.dietary_tags,
                    i.image_url,
                    i.available_from,
                    i.available_to,
                    i.category_id,
                    c.name AS category_name
                 FROM items i
                 JOIN categories c ON i.category_id = c.id
                 ORDER BY c.name, i.name";
        $result = mysqli_query($this->con, $sql);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function createCategory(string $name): bool {
        $stmt = mysqli_prepare($this->con, "INSERT INTO categories (name) VALUES (?)");
        mysqli_stmt_bind_param($stmt, 's', $name);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function createItem(array $data): bool {
        $stmt = mysqli_prepare(
            $this->con,
            "INSERT INTO items
               (name, category_id, price, dietary_tags, image_url, available_from, available_to)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'sidsiss',
            $data['name'],
            $data['category_id'],
            $data['price'],
            $data['dietary_tags'],
            $data['image_url'],
            $data['available_from'],
            $data['available_to']
        );
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
    public function updateCategory(int $id, string $name): bool {
        $stmt = mysqli_prepare($this->con, "UPDATE categories SET name = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $name, $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function deleteCategory(int $id): bool {
        $stmt = mysqli_prepare($this->con, "DELETE FROM categories WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function updateItem(int $id, array $d): bool {
        $stmt = mysqli_prepare(
            $this->con,
            "UPDATE items
                SET name = ?, category_id = ?, price = ?, dietary_tags = ?, image_url = ?, available_from = ?, available_to = ?
              WHERE id = ?"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'sidsissi',
            $d['name'],
            $d['category_id'],
            $d['price'],
            $d['dietary_tags'],
            $d['image_url'],
            $d['available_from'],
            $d['available_to'],
            $id
        );
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function deleteItem(int $id): bool {
        $stmt = mysqli_prepare($this->con, "DELETE FROM items WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}
?>
