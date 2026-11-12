<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;
use PDO;
use Throwable;

class Product extends BaseModel
{
    /**
     * Lấy sản phẩm đang hoạt động cho khách hàng
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
     * Lấy tất cả sản phẩm cho trang Admin
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
     * Có thể lấy cả sản phẩm đang tạm ẩn
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
     * Thêm sản phẩm mới
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
    public function update(int $id, array $data): bool
    {
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
     * Xóa sản phẩm vĩnh viễn
     *
     * Nếu sản phẩm đã xuất hiện trong đơn hàng,
     * các order_items liên quan cũng được xóa trước.
     */
    public function delete(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            /*
             * Xóa các chi tiết đơn hàng liên quan đến sản phẩm.
             * Cần làm bước này trước vì bảng order_items
             * có khóa ngoại product_id.
             */
            $deleteItems = $this->db->prepare(
                "DELETE FROM order_items
                 WHERE product_id = :id"
            );

            $deleteItems->execute([
                'id' => $id
            ]);

            /*
             * Xóa sản phẩm
             */
            $deleteProduct = $this->db->prepare(
                "DELETE FROM products
                 WHERE id = :id"
            );

            $deleteProduct->execute([
                'id' => $id
            ]);

            /*
             * Kiểm tra sản phẩm có thực sự được xóa
             */
            if ($deleteProduct->rowCount() === 0) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();

            return true;

        } catch (Throwable $e) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Tìm kiếm sản phẩm trong Admin
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
     * Đếm tổng số sản phẩm
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