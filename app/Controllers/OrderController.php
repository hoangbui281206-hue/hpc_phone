<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Order;

class OrderController extends BaseController
{
    public function checkout(): void
    {
        $this->requireLogin();

        $cart = $_SESSION['cart'] ?? [];

        if (!$cart) {
            $this->redirect('/cart');
        }

        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? 'cod';

        if (!$name || !$phone || !$address) {
            $_SESSION['flash'] = 'Vui lòng điền đầy đủ thông tin nhận hàng.';
            $this->redirect('/cart');
        }

        try {
            $orderId = (new Order())->create(
                (int) $_SESSION['user']['id'],
                $cart,
                $name,
                $phone,
                $address,
                $paymentMethod
            );

            unset($_SESSION['cart']);

            if ($paymentMethod === 'online') {
                $_SESSION['flash'] =
                    "Đơn hàng #{$orderId} đã được tạo. "
                    . "Vui lòng tiếp tục thanh toán online.";

                $this->redirect('/payment/' . $orderId);
            }

            $_SESSION['flash'] =
                "Đặt hàng thành công! Mã đơn hàng #{$orderId}. "
                . "Bạn thanh toán khi nhận hàng.";

            $this->redirect('/');

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Không thể đặt hàng: ' . $e->getMessage();

            $this->redirect('/cart');
        }
    }
}