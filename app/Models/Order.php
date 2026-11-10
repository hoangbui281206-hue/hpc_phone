<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class Order extends BaseModel
{
    public function create(
        int $userId,
        array $cart,
        string $customerName,
        string $phone,
        string $address
    ): int {
        $this->db->beginTransaction();

        try {
            $total = 0;

            foreach ($cart as $item) {
                $total += (float) $item['price'] * (int) $item['quantity'];
            }

            $stmt = $this->db->prepare(
                "INSERT INTO orders (
                    user_id,
                    customer_name,
                    phone,
                    address,
                    total,
                    status
                ) VALUES (
                    :user_id,
                    :customer_name,
                    :phone,
                    :address,
                    :total,
                    'pending'
                )"
            );

            $stmt->execute([
                'user_id' => $userId,
                'customer_name' => $customerName,
                'phone' => $phone,
                'address' => $address,
                'total' => $total
            ]);

            $orderId = (int) $this->db->lastInsertId();

            $insertItem = $this->db->prepare(
                "INSERT INTO order_items (
                    order_id,
                    product_id,
                    product_name,
                    price,
                    quantity
                ) VALUES (
                    :order_id,
                    :product_id,
                    :product_name,
                    :price,
                    :quantity
                )"
            );

            $stock = $this->db->prepare(
                "UPDATE products
                 SET stock = stock - :quantity_sub
                 WHERE id = :id
                 AND stock >= :quantity_check"
            );

            foreach ($cart as $id => $item) {
                $quantity = (int) $item['quantity'];

                $stock->execute([
                    'quantity_sub' => $quantity,
                    'id' => (int) $id,
                    'quantity_check' => $quantity
                ]);

                if ($stock->rowCount() !== 1) {
                    throw new \RuntimeException(
                        'Sản phẩm không đủ tồn kho: ' . $item['name']
                    );
                }

                $insertItem->execute([
                    'order_id' => $orderId,
                    'product_id' => (int) $id,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $quantity
                ]);
            }

            $this->db->commit();

            return $orderId;

        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Lấy tất cả đơn hàng cho Admin
     */
    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT orders.*, users.email
             FROM orders
             LEFT JOIN users ON orders.user_id = users.id
             ORDER BY orders.id DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng đơn hàng
     */
    public function count(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM orders"
        );

        return (int) $stmt->fetchColumn();
    }

    /**
     * Tính tổng doanh thu từ đơn hoàn thành
     */
    public function totalRevenue(): float
    {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(total), 0)
             FROM orders
             WHERE status = 'completed'"
        );

        return (float) $stmt->fetchColumn();
    }

    /**
     * Lấy đơn hàng mới nhất
     */
    public function latest(int $limit = 5): array
    {
        $limit = max(1, min($limit, 20));

        $stmt = $this->db->query(
            "SELECT *
             FROM orders
             ORDER BY id DESC
             LIMIT {$limit}"
        );

        return $stmt->fetchAll();
    }

    /**
     * Lấy thông tin một đơn hàng
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT orders.*, users.email
             FROM orders
             LEFT JOIN users ON orders.user_id = users.id
             WHERE orders.id = :id
             LIMIT 1"
        );

        $stmt->execute(['id' => $id]);

        $order = $stmt->fetch();

        return $order ?: null;
    }

    /**
     * Lấy sản phẩm trong đơn hàng
     */
    public function getItems(int $orderId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM order_items
             WHERE order_id = :order_id"
        );

        $stmt->execute(['order_id' => $orderId]);

        return $stmt->fetchAll();
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(int $id, string $status): bool
    {
        $allowed = [
            'pending',
            'confirmed',
            'shipping',
            'completed',
            'cancelled'
        ];

        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE orders
             SET status = :status
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }
}