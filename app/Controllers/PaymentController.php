<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Order;

class PaymentController extends BaseController
{
    /**
     * Hiển thị trang thanh toán MoMo
     */
    public function index(string $id): void
    {
        $this->requireLogin();

        $orderModel = new Order();
        $order = $orderModel->find((int) $id);

        if (!$order) {
            $_SESSION['flash'] = 'Không tìm thấy đơn hàng.';
            $this->redirect('/');
            return;
        }

        // Chỉ chủ đơn hàng được phép thanh toán
        if ((int) $order['user_id'] !== (int) $_SESSION['user']['id']) {
            $_SESSION['flash'] = 'Bạn không có quyền thanh toán đơn hàng này.';
            $this->redirect('/');
            return;
        }

        // Không thanh toán lại đơn đã thanh toán
        if (($order['payment_status'] ?? 'unpaid') === 'paid') {
            $_SESSION['flash'] = 'Đơn hàng này đã được thanh toán.';
            $this->redirect('/');
            return;
        }

        $this->view('payment/index', [
            'order' => $order
        ]);
    }

    /**
     * Xác nhận thanh toán demo
     */
    public function confirm(): void
    {
        $this->requireLogin();

        $orderId = (int) ($_POST['order_id'] ?? 0);

        if ($orderId <= 0) {
            $_SESSION['flash'] = 'Mã đơn hàng không hợp lệ.';
            $this->redirect('/');
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->find($orderId);

        if (!$order) {
            $_SESSION['flash'] = 'Không tìm thấy đơn hàng.';
            $this->redirect('/');
            return;
        }

        // Kiểm tra chủ sở hữu đơn hàng
        if ((int) $order['user_id'] !== (int) $_SESSION['user']['id']) {
            $_SESSION['flash'] = 'Bạn không có quyền thực hiện thao tác này.';
            $this->redirect('/');
            return;
        }

        // Không cập nhật lại đơn đã thanh toán
        if (($order['payment_status'] ?? 'unpaid') === 'paid') {
            $_SESSION['flash'] = 'Đơn hàng này đã được thanh toán.';
            $this->redirect('/');
            return;
        }

        try {
            $transactionId = 'MOMO-DEMO-' . date('YmdHis') . '-' . $orderId;

            $stmt = $orderModel->getConnection()->prepare(
                "UPDATE orders
                 SET payment_status = 'paid',
                     transaction_id = :transaction_id,
                     status = 'confirmed'
                 WHERE id = :id"
            );

            $stmt->execute([
                'transaction_id' => $transactionId,
                'id' => $orderId
            ]);

            $_SESSION['flash'] =
                'Thanh toán MoMo thành công! Đơn hàng đã được xác nhận.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Không thể cập nhật thanh toán: ' . $e->getMessage();
        }

        $this->redirect('/');
    }
}