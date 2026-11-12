<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class Order extends BaseModel
{
    /**
     * Tạo đơn hàng mới
     */
    public function create(
        int $userId,
        array $cart,
        string $customerName,
        string $phone,
        string $address,
        string $paymentMethod = 'cod'
    ): int {
        if ($userId <= 0) {
            throw new \RuntimeException('Vui lòng đăng nhập để đặt hàng.');
        }

        if (empty($cart)) {
            throw new \RuntimeException('Giỏ hàng đang trống.');
        }

        $allowedMethods = ['cod', 'online'];

        if (!in_array($paymentMethod, $allowedMethods, true)) {
            $paymentMethod = 'cod';
        }

        $this->db->beginTransaction();

        try {
            /*
             * Tính tổng tiền
             */
            $total = 0;

            foreach ($cart as $item) {
                $price = (float) ($item['price'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);

                if ($price < 0 || $quantity <= 0) {
                    throw new \RuntimeException(
                        'Dữ liệu giỏ hàng không hợp lệ.'
                    );
                }

                $total += $price * $quantity;
            }

            /*
             * Tạo đơn hàng
             */
            $orderStmt = $this->db->prepare(
                "INSERT INTO orders (
                    user_id,
                    customer_name,
                    phone,
                    address,
                    total,
                    payment_method,
                    payment_status,
                    status
                ) VALUES (
                    :user_id,
                    :customer_name,
                    :phone,
                    :address,
                    :total,
                    :payment_method,
                    :payment_status,
                    :status
                )"
            );

            $orderStmt->execute([
                'user_id' => $userId,
                'customer_name' => $customerName,
                'phone' => $phone,
                'address' => $address,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => 'unpaid',
                'status' => 'pending'
            ]);

            $orderId = (int) $this->db->lastInsertId();

            /*
             * Lưu chi tiết sản phẩm
             */
            $itemStmt = $this->db->prepare(
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

            /*
             * Trừ số lượng tồn kho
             */
            $stockStmt = $this->db->prepare(
                "UPDATE products
                 SET stock = stock - :quantity_sub
                 WHERE id = :id
                 AND stock >= :quantity_check"
            );

            foreach ($cart as $id => $item) {
                $productId = (int) ($item['id'] ?? $id);
                $productName = trim(
                    (string) ($item['name'] ?? '')
                );
                $price = (float) ($item['price'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);

                if (
                    $productId <= 0
                    || $productName === ''
                    || $quantity <= 0
                ) {
                    throw new \RuntimeException(
                        'Thông tin sản phẩm không hợp lệ.'
                    );
                }

                /*
                 * Kiểm tra và trừ tồn kho
                 */
                $stockStmt->execute([
                    'quantity_sub' => $quantity,
                    'id' => $productId,
                    'quantity_check' => $quantity
                ]);

                if ($stockStmt->rowCount() !== 1) {
                    throw new \RuntimeException(
                        'Sản phẩm "' . $productName
                        . '" không đủ số lượng trong kho.'
                    );
                }

                /*
                 * Lưu sản phẩm vào đơn hàng
                 */
                $itemStmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'price' => $price,
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
             LEFT JOIN users
             ON orders.user_id = users.id
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
     * Tổng doanh thu từ đơn hoàn thành
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
     * Doanh thu hôm nay
     */
    public function todayRevenue(): float
    {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(total), 0)
             FROM orders
             WHERE status = 'completed'
             AND DATE(created_at) = CURDATE()"
        );

        return (float) $stmt->fetchColumn();
    }

    /**
     * Doanh thu tháng này
     */
    public function monthRevenue(): float
    {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(total), 0)
             FROM orders
             WHERE status = 'completed'
             AND MONTH(created_at) = MONTH(CURDATE())
             AND YEAR(created_at) = YEAR(CURDATE())"
        );

        return (float) $stmt->fetchColumn();
    }

    /**
     * Đếm đơn hàng đã hoàn thành
     */
    public function completedCount(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*)
             FROM orders
             WHERE status = 'completed'"
        );

        return (int) $stmt->fetchColumn();
    }

    /**
     * Danh sách đơn hàng hoàn thành
     */
    public function completedOrders(): array
    {
        $stmt = $this->db->query(
            "SELECT *
             FROM orders
             WHERE status = 'completed'
             ORDER BY id DESC"
        );

        return $stmt->fetchAll();
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
     * Tìm đơn hàng theo ID cho Admin
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT orders.*, users.email
             FROM orders
             LEFT JOIN users
             ON orders.user_id = users.id
             WHERE orders.id = :id
             LIMIT 1"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $order = $stmt->fetch();

        return $order ?: null;
    }

    /**
     * Lấy tất cả đơn hàng của khách hàng đang đăng nhập
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM orders
             WHERE user_id = :user_id
             ORDER BY id DESC"
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Lấy một đơn hàng và kiểm tra quyền sở hữu của khách hàng
     */
    public function findByUser(
        int $orderId,
        int $userId
    ): ?array {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM orders
             WHERE id = :id
             AND user_id = :user_id
             LIMIT 1"
        );

        $stmt->execute([
            'id' => $orderId,
            'user_id' => $userId
        ]);

        $order = $stmt->fetch();

        return $order ?: null;
    }

    /**
     * Lấy các sản phẩm trong đơn hàng
     */
    public function getItems(int $orderId): array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM order_items
             WHERE order_id = :order_id
             ORDER BY id ASC"
        );

        $stmt->execute([
            'order_id' => $orderId
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(
        int $id,
        string $status
    ): bool {
        $allowedStatus = [
            'pending',
            'confirmed',
            'shipping',
            'completed',
            'cancelled'
        ];

        if (!in_array($status, $allowedStatus, true)) {
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