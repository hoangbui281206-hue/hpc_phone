<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class User extends BaseModel
{
    /**
     * Tìm người dùng theo email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM users
             WHERE email = :email
             LIMIT 1"
        );

        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    /**
     * Tạo tài khoản khách hàng mới
     */
    public function create(
        string $name,
        string $email,
        string $password
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (
                name,
                email,
                password,
                role
            ) VALUES (
                :name,
                :email,
                :password,
                :role
            )"
        );

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            'role' => 'customer'
        ]);
    }

    /**
     * Lấy toàn bộ người dùng cho trang Admin
     */
    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT
                id,
                name,
                email,
                role
             FROM users
             ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số người dùng
     */
    public function count(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*)
             FROM users"
        );

        return (int) $stmt->fetchColumn();
    }

    /**
     * Tìm người dùng theo ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT
                id,
                name,
                email,
                role
             FROM users
             WHERE id = :id
             LIMIT 1"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    /**
     * Cập nhật quyền người dùng
     */
    public function updateRole(
        int $id,
        string $role
    ): bool {
        $allowedRoles = [
            'admin',
            'customer'
        ];

        if (
            !in_array(
                $role,
                $allowedRoles,
                true
            )
        ) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE users
             SET role = :role
             WHERE id = :id"
        );

        return $stmt->execute([
            'role' => $role,
            'id' => $id
        ]);
    }
}