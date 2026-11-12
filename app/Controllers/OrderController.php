<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Order;

class OrderController extends BaseController
{
    public function checkout(): void
    {
        $this->requireLogin();

        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $_SESSION['flash'] = 'Giỏ hàng đang trống.';
            $this->redirect('/cart');
            return;
        }

        $customerName = trim(
            (string) ($_POST['customer_name'] ?? '')
        );

        $phone = trim(
            (string) ($_POST['phone'] ?? '')
        );

        $address = trim(
            (string) ($_POST['address'] ?? '')
        );

        $paymentMethod = trim(
            (string) ($_POST['payment_method'] ?? 'cod')
        );

        if (
            $customerName === ''
            || $phone === ''
            || $address === ''
        ) {
            $_SESSION['flash'] =
                'Vui lòng điền đầy đủ thông tin nhận hàng.';

            $this->redirect('/cart');
            return;
        }

        if (!in_array(
            $paymentMethod,
            ['cod', 'online'],
            true
        )) {
            $paymentMethod = 'cod';
        }

        try {
            $orderModel = new Order();

            $orderId = $orderModel->create(
                (int) ($_SESSION['user']['id'] ?? 0),
                $cart,
                $customerName,
                $phone,
                $address,
                $paymentMethod
            );

            unset($_SESSION['cart']);

            if ($paymentMethod === 'online') {
                $_SESSION['flash'] =
                    'Đơn hàng #' . $orderId
                    . ' đã được tạo. Vui lòng tiếp tục thanh toán.';

                $this->redirect('/payment/' . $orderId);
                return;
            }

            $_SESSION['flash'] =
                'Đặt hàng thành công! Mã đơn hàng của bạn là #'
                . $orderId;

            $this->redirect('/my-orders');
            return;

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Không thể đặt hàng: ' . $e->getMessage();

            $this->redirect('/cart');
            return;
        }
    }

    public function myOrders(): void
    {
        $this->requireLogin();

        $orderModel = new Order();

        $orders = $orderModel->getByUserId(
            (int) $_SESSION['user']['id']
        );

        $this->view(
            'orders/my-orders',
            compact('orders')
        );
    }

    public function myOrderDetail(): void
    {
        $this->requireLogin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] = 'Mã đơn hàng không hợp lệ.';
            $this->redirect('/my-orders');
            return;
        }

        $orderModel = new Order();

        $order = $orderModel->findByUser(
            $id,
            (int) $_SESSION['user']['id']
        );

        if (!$order) {
            $_SESSION['flash'] =
                'Không tìm thấy đơn hàng hoặc bạn không có quyền xem.';

            $this->redirect('/my-orders');
            return;
        }

        $items = $orderModel->getItems($id);

        $this->view(
            'orders/my-order-detail',
            compact('order', 'items')
        );
    }
}