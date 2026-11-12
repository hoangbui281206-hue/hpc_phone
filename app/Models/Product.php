<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class Product extends BaseModel
{
    /**
     * Lấy sản phẩm đang hoạt động
     */
    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT *
             FROM products
             WHERE status = 1
             ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm và lọc sản phẩm cho khách hàng
     */
    public function filter(
        string $keyword = '',
        string $stock = '',
        float $minPrice = 0,
        float $maxPrice = 0
    ): array {
        $sql = "
            SELECT *
            FROM products
            WHERE status = 1
        ";

        $params = [];

        if ($keyword !== '') {
            $sql .= "
                AND name LIKE :keyword
            ";

            $params['keyword'] =
                '%' . $keyword . '%';
        }

        if ($stock === 'in_stock') {
            $sql .= "
                AND stock > 0
            ";
        }

        if ($minPrice > 0) {
            $sql .= "
                AND price >= :min_price
            ";

            $params['min_price'] = $minPrice;
        }

        if ($maxPrice > 0) {
            $sql .= "
                AND price <= :max_price
            ";

            $params['max_price'] = $maxPrice;
        }

        $sql .= "
            ORDER BY id DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Lấy tất cả sản phẩm cho Admin
     */
    public function allAdmin(): array
    {
        $stmt = $this->db->query(
            "SELECT *
             FROM products
             ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * Tìm sản phẩm cho khách hàng
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM products
             WHERE id = :id
             AND status = 1
             LIMIT 1"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $product = $stmt->fetch();

        return $product ?: null;
    }

    /**
     * Tìm sản phẩm cho Admin
     */
    public function findAdmin(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM products
             WHERE id = :id
             LIMIT 1"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $product = $stmt->fetch();

        return $product ?: null;
    }

    /**
     * Thêm sản phẩm
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO products
            (
                name,
                price,
                sale_price,
                image,
                description,
                stock,
                status
            )
            VALUES
            (
                :name,
                :price,
                :sale_price,
                :image,
                :description,
                :stock,
                :status
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name' => $data['name'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'image' => $data['image'] ?? '',
            'description' => $data['description'] ?? '',
            'stock' => $data['stock'],
            'status' => $data['status'] ?? 1
        ]);
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(
        int $id,
        array $data
    ): bool {
        $sql = "
            UPDATE products
            SET
                name = :name,
                price = :price,
                sale_price = :sale_price,
                image = :image,
                description = :description,
                stock = :stock,
                status = :status
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'image' => $data['image'] ?? '',
            'description' => $data['description'] ?? '',
            'stock' => $data['stock'],
            'status' => $data['status'] ?? 1
        ]);
    }

    /**
     * Xóa sản phẩm
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM products
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }

    /**
     * Tìm kiếm cho Admin
     */
    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM products
             WHERE name LIKE :keyword
             ORDER BY id DESC"
        );

        $stmt->execute([
            'keyword' => '%' . $keyword . '%'
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Đếm sản phẩm
     */
    public function count(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*)
             FROM products"
        );

        return (int) $stmt->fetchColumn();
    }
}