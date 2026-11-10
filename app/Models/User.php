<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class User extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users
             WHERE email = :email
             LIMIT 1"
        );

        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function create(
        string $name,
        string $email,
        string $password
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, role)
             VALUES (:name, :email, :password, 'customer')"
        );

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            )
        ]);
    }

    /**
     * Lấy toàn bộ người dùng
     */
    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT id, name, email, role
             FROM users
             ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng người dùng
     */
    public function count(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM users"
        );

        return (int) $stmt->fetchColumn();
    }

    /**
     * Cập nhật quyền người dùng
     */
    public function updateRole(
        int $id,
        string $role
    ): bool {
        if (!in_array($role, ['admin', 'customer'], true)) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE users
             SET role = :role
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'role' => $role
        ]);
    }
}