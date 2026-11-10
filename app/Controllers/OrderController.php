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
        if (!$name || !$phone || !$address) {
            $_SESSION['flash'] = 'Vui lòng điền đầy đủ thông tin nhận hàng.';
            $this->redirect('/cart');
        }
        try {
            $id = (new Order())->create((int)$_SESSION['user']['id'], $cart, $name, $phone, $address);
            unset($_SESSION['cart']);
            $_SESSION['flash'] = "Đặt hàng thành công! Mã đơn hàng #{$id}. Thanh toán COD.";
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Không thể đặt hàng: ' . $e->getMessage();
        }
        $this->redirect('/');
    }
}
