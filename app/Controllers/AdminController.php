<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin();

        $productModel = new Product();
        $orderModel = new Order();
        $userModel = new User();

        $totalProducts = $productModel->count();
        $totalOrders = $orderModel->count();
        $totalUsers = $userModel->count();
        $totalRevenue = $orderModel->totalRevenue();
        $latestOrders = $orderModel->latest();

        $this->view('admin/index', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'latestOrders'
        ));
    }

    public function products(): void
    {
        $this->requireAdmin();

        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');

        $products = $keyword !== ''
            ? $productModel->search($keyword)
            : $productModel->allAdmin();

        $this->view('admin/products', compact(
            'products',
            'keyword'
        ));
    }

    public function createProduct(): void
    {
        $this->requireAdmin();

        $name = trim($_POST['name'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);

        if ($name === '' || $price <= 0 || $stock < 0) {
            $_SESSION['flash'] = 'Dữ liệu không hợp lệ.';
            $this->redirect('/admin/products');
        }

        (new Product())->create([
            'name' => $name,
            'price' => $price,
            'sale_price' => !empty($_POST['sale_price'])
                ? (float) $_POST['sale_price']
                : null,
            'image' => trim($_POST['image'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'stock' => $stock,
            'status' => (int) ($_POST['status'] ?? 1)
        ]);

        $_SESSION['flash'] = 'Thêm sản phẩm thành công.';
        $this->redirect('/admin/products');
    }

    public function orders(): void
    {
        $this->requireAdmin();

        $orders = (new Order())->all();

        $this->view('admin/orders', compact('orders'));
    }

    public function orderDetail(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $orderModel = new Order();

        $order = $orderModel->find($id);
        $items = $orderModel->getItems($id);

        if (!$order) {
            $_SESSION['flash'] = 'Không tìm thấy đơn hàng.';
            $this->redirect('/admin/orders');
        }

        $this->view('admin/order-detail', compact(
            'order',
            'items'
        ));
    }

    public function updateOrderStatus(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        (new Order())->updateStatus($id, $status);

        $_SESSION['flash'] = 'Đã cập nhật trạng thái đơn hàng.';
        $this->redirect('/admin/orders');
    }

    public function users(): void
    {
        $this->requireAdmin();

        $users = (new User())->all();

        $this->view('admin/users', compact('users'));
    }

    public function updateUserRole(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $role = $_POST['role'] ?? '';

        (new User())->updateRole($id, $role);

        $_SESSION['flash'] = 'Đã cập nhật quyền người dùng.';
        $this->redirect('/admin/users');
    }
}