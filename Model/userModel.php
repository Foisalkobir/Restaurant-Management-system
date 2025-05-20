<?php
// Model/userModel.php - User model for CRUD operations using mysqli dependencies: db.php

require_once __DIR__ . '/db.php';

class UserModel {
    private $con;

    public function __construct() {
        // Use the global mysqli connection from db.php
        global $con;
        $this->con = $con;
    }

    /**
     * Check if an email is already registered
     * @param string $email
     * @return bool
     */
    public static function existsByEmail(string $email): bool {
        global $con;
        $stmt = mysqli_prepare($con, 'SELECT COUNT(*) FROM users WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $count > 0;
    }

    /**
     * Create a new user (default role is 'user')
     * @param array $data ['name'=>string,'email'=>string,'password'=>string]
     * @return bool
     */
    public function create(array $data): bool {
        $stmt = mysqli_prepare(
            $this->con,
            'INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())'
        );
        // default role
        $role = $data['role'] ?? 'user';
        mysqli_stmt_bind_param(
            $stmt,
            'ssss',
            $data['name'],
            $data['email'],
            $data['password'],
            $role
        );
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    /**
     * Find a user by email (including password hash and role)
     * @param string $email
     * @return array|null ['id'=>int,'name'=>string,'email'=>string,'password'=>string,'role'=>string] or null
     */
    public function findByEmail(string $email): ?array {
        $stmt = mysqli_prepare(
            $this->con,
            'SELECT id, name, email, password, role
               FROM users
              WHERE email = ?
              LIMIT 1'
        );
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $em, $hash, $role);

        if (mysqli_stmt_fetch($stmt)) {
            $user = [
                'id'       => $id,
                'name'     => $name,
                'email'    => $em,
                'password' => $hash,
                'role'     => $role
            ];
        } else {
            $user = null;
        }

        mysqli_stmt_close($stmt);
        return $user;
    }

    /**
     * Update user password
     * @param int $id
     * @param string $newHash
     * @return bool
     */
    public function updatePassword(int $id, string $newHash): bool {
        $stmt = mysqli_prepare(
            $this->con,
            'UPDATE users SET password = ? WHERE id = ?'
        );
        mysqli_stmt_bind_param($stmt, 'si', $newHash, $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    /**
     * Fetch all users (for admin list)
     * @return array
     */
    public function getAllUsers(): array {
        $sql = 'SELECT id, name, email, role, created_at FROM users ORDER BY name';
        $res = mysqli_query($this->con, $sql);
        $out = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = $row;
        }
        return $out;
    }
}
