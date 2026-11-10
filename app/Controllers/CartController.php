<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Product;

class CartController extends BaseController
{
    public function index(): void
    {
        $cart = $_SESSION['cart'] ?? [];
        $this->view('cart/index', compact('cart'));
    }

    public function add(): void
    {
        $id = (int)($_POST['product_id'] ?? 0);
        $product = (new Product())->find($id);
        if (!$product || (int)$product['stock'] < 1) {
            $this->redirect('/products');
        }
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => (float)($product['sale_price'] ?: $product['price']),
                'image' => $product['image'],
                'quantity' => 0
            ];
        }
        $_SESSION['cart'][$id]['quantity']++;
        $this->redirect('/cart');
    }

    public function update(): void
    {
        $id = (int)($_POST['product_id'] ?? 0);
        $quantity = max(1, min(20, (int)($_POST['quantity'] ?? 1)));
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        }
        $this->redirect('/cart');
    }

    public function remove(): void
    {
        $id = (int)($_POST['product_id'] ?? 0);
        unset($_SESSION['cart'][$id]);
        $this->redirect('/cart');
    }
}
