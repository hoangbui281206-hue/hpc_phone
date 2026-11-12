<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Order;

class OrderController extends BaseController
{
    /**
     * Đặt hàng
     */
    public function checkout(): void
    {
        $this->requireLogin();

        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $_SESSION['flash'] = 'Giỏ hàng của bạn đang trống.';
            $this->redirect('/cart');
        }

        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? 'cod';

        if ($name === '' || $phone === '' || $address === '') {
            $_SESSION['flash'] =
                'Vui lòng điền đầy đủ thông tin nhận hàng.';

            $this->redirect('/cart');
        }

        try {
            $orderModel = new Order();

            $orderId = $orderModel->create(
                (int) $_SESSION['user']['id'],
                $cart,
                $name,
                $phone,
                $address,
                $paymentMethod
            );

            /**
             * Xóa giỏ hàng sau khi đặt thành công
             */
            unset($_SESSION['cart']);

            /**
             * Thanh toán online
             */
            if ($paymentMethod === 'online') {
                $_SESSION['flash'] =
                    "Đơn hàng #{$orderId} đã được tạo. "
                    . "Vui lòng tiếp tục thanh toán online.";

                $this->redirect('/payment/' . $orderId);
            }

            /**
             * Thanh toán khi nhận hàng
             */
            $_SESSION['flash'] =
                "Đặt hàng thành công! Mã đơn hàng của bạn là #{$orderId}.";

            $this->redirect('/my-orders');

        } catch (\Throwable $e) {

            $_SESSION['flash'] =
                'Không thể đặt hàng: ' . $e->getMessage();

            $this->redirect('/cart');
        }
    }

    /**
     * Danh sách đơn hàng của khách hàng đang đăng nhập
     */
    public function myOrders(): void
    {
        $this->requireLogin();

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($userId <= 0) {
            $_SESSION['flash'] =
                'Không thể xác định tài khoản của bạn.';

            $this->redirect('/login');
        }

        $orderModel = new Order();

        $orders = $orderModel->getOrdersByUser($userId);

        $this->view(
            'orders/my-orders',
            compact('orders')
        );
    }

    /**
     * Chi tiết đơn hàng của khách hàng
     */
    public function myOrderDetail(): void
    {
        $this->requireLogin();

        $orderId = (int) ($_GET['id'] ?? 0);
        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($orderId <= 0) {
            $_SESSION['flash'] =
                'Mã đơn hàng không hợp lệ.';

            $this->redirect('/my-orders');
        }

        $orderModel = new Order();

        /**
         * Chỉ lấy đơn hàng thuộc về tài khoản hiện tại
         */
        $order = $orderModel->findByUser(
            $orderId,
            $userId
        );

        if (!$order) {
            $_SESSION['flash'] =
                'Không tìm thấy đơn hàng hoặc bạn không có quyền xem đơn hàng này.';

            $this->redirect('/my-orders');
        }

        $items = $orderModel->getItems($orderId);

        $this->view(
            'orders/my-order-detail',
            compact(
                'order',
                'items'
            )
        );
    }
}