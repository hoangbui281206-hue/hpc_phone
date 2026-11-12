<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminController extends BaseController
{
    /**
     * Dashboard Admin
     */
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

    /**
     * Danh sách sản phẩm
     */
    public function products(): void
    {
        $this->requireAdmin();

        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword !== '') {
            $products = $productModel->search($keyword);
        } else {
            $products = $productModel->allAdmin();
        }

        $this->view('admin/products', compact(
            'products',
            'keyword'
        ));
    }

    /**
     * Thêm sản phẩm
     */
    public function createProduct(): void
    {
        $this->requireAdmin();

        $name = trim($_POST['name'] ?? '');

        $priceInput = trim($_POST['price'] ?? '');
        $priceInput = str_replace(['.', ',', ' '], '', $priceInput);
        $price = (float) $priceInput;

        $stock = (int) ($_POST['stock'] ?? 0);
        $image = trim($_POST['image'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = (int) ($_POST['status'] ?? 1);

        if ($name === '' || $price <= 0 || $stock < 0) {
            $_SESSION['flash'] =
                'Vui lòng nhập đầy đủ thông tin sản phẩm.';

            $this->redirect('/admin/products');
            return;
        }

        $salePrice = $this->formatPrice(
            $_POST['sale_price'] ?? ''
        );

        try {
            $created = (new Product())->create([
                'name' => $name,
                'price' => $price,
                'sale_price' => $salePrice,
                'image' => $image,
                'description' => $description,
                'stock' => $stock,
                'status' => $status
            ]);

            $_SESSION['flash'] = $created
                ? 'Thêm sản phẩm thành công.'
                : 'Không thể thêm sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Lỗi khi thêm sản phẩm: ' . $e->getMessage();
        }

        $this->redirect('/admin/products');
    }

    /**
     * Hiển thị trang sửa sản phẩm
     */
    public function editProduct(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] = 'ID sản phẩm không hợp lệ.';
            $this->redirect('/admin/products');
            return;
        }

        $product = (new Product())->findAdmin($id);

        if (!$product) {
            $_SESSION['flash'] = 'Không tìm thấy sản phẩm.';
            $this->redirect('/admin/products');
            return;
        }

        $this->view('admin/product-edit', compact('product'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function updateProduct(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        $priceInput = trim($_POST['price'] ?? '');
        $priceInput = str_replace(['.', ',', ' '], '', $priceInput);
        $price = (float) $priceInput;

        $stock = (int) ($_POST['stock'] ?? 0);
        $image = trim($_POST['image'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = (int) ($_POST['status'] ?? 1);

        if (
            $id <= 0 ||
            $name === '' ||
            $price <= 0 ||
            $stock < 0
        ) {
            $_SESSION['flash'] =
                'Dữ liệu sản phẩm không hợp lệ.';

            $this->redirect('/admin/products');
            return;
        }

        $salePrice = $this->formatPrice(
            $_POST['sale_price'] ?? ''
        );

        try {
            $updated = (new Product())->update($id, [
                'name' => $name,
                'price' => $price,
                'sale_price' => $salePrice,
                'image' => $image,
                'description' => $description,
                'stock' => $stock,
                'status' => $status
            ]);

            $_SESSION['flash'] = $updated
                ? 'Cập nhật sản phẩm thành công.'
                : 'Không thể cập nhật sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage();
        }

        $this->redirect('/admin/products');
    }

    /**
     * Xóa sản phẩm vĩnh viễn
     */
    public function deleteProduct(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] =
                'ID sản phẩm không hợp lệ.';

            $this->redirect('/admin/products');
            return;
        }

        try {
            $productModel = new Product();

            $product = $productModel->findAdmin($id);

            if (!$product) {
                $_SESSION['flash'] =
                    'Không tìm thấy sản phẩm.';

                $this->redirect('/admin/products');
                return;
            }

            $deleted = $productModel->delete($id);

            $_SESSION['flash'] = $deleted
                ? 'Đã xóa sản phẩm vĩnh viễn.'
                : 'Không thể xóa sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Lỗi khi xóa sản phẩm: ' . $e->getMessage();
        }

        $this->redirect('/admin/products');
    }

    /**
     * Danh sách đơn hàng
     */
    public function orders(): void
    {
        $this->requireAdmin();

        $orders = (new Order())->all();

        $this->view('admin/orders', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] =
                'ID đơn hàng không hợp lệ.';

            $this->redirect('/admin/orders');
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order) {
            $_SESSION['flash'] =
                'Không tìm thấy đơn hàng.';

            $this->redirect('/admin/orders');
            return;
        }

        $items = $orderModel->getItems($id);

        $this->view(
            'admin/order-detail',
            compact('order', 'items')
        );
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        $allowedStatuses = [
            'pending',
            'confirmed',
            'shipping',
            'completed',
            'cancelled'
        ];

        if (
            $id <= 0 ||
            !in_array($status, $allowedStatuses, true)
        ) {
            $_SESSION['flash'] =
                'Dữ liệu cập nhật không hợp lệ.';

            $this->redirect('/admin/orders');
            return;
        }

        $updated = (new Order())->updateStatus(
            $id,
            $status
        );

        $_SESSION['flash'] = $updated
            ? 'Đã cập nhật trạng thái đơn hàng.'
            : 'Không thể cập nhật trạng thái đơn hàng.';

        $this->redirect('/admin/orders');
    }

    /**
     * Danh sách người dùng
     */
    public function users(): void
    {
        $this->requireAdmin();

        $users = (new User())->all();

        $this->view('admin/users', compact('users'));
    }

    /**
     * Cập nhật quyền người dùng
     */
    public function updateUserRole(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $role = trim($_POST['role'] ?? '');

        $allowedRoles = [
            'admin',
            'customer'
        ];

        if (
            $id <= 0 ||
            !in_array($role, $allowedRoles, true)
        ) {
            $_SESSION['flash'] =
                'Quyền người dùng không hợp lệ.';

            $this->redirect('/admin/users');
            return;
        }

        $updated = (new User())->updateRole(
            $id,
            $role
        );

        $_SESSION['flash'] = $updated
            ? 'Đã cập nhật quyền người dùng.'
            : 'Không thể cập nhật quyền người dùng.';

        $this->redirect('/admin/users');
    }

    /**
     * Chuyển giá tiền từ chuỗi sang số.
     *
     * Ví dụ:
     * 18.990.000 -> 18990000
     * 18,990,000 -> 18990000
     */
    private function formatPrice(string $priceInput): ?float
    {
        $priceInput = trim($priceInput);

        if ($priceInput === '') {
            return null;
        }

        $priceInput = str_replace(
            ['.', ',', ' '],
            '',
            $priceInput
        );

        $price = (float) $priceInput;

        return $price > 0 ? $price : null;
    }
}