<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends BaseController
{
    /**
     * Kiểm tra quyền Admin cho tất cả trang quản trị.
     */
    private function checkAdmin(): void
    {
        $this->requireAdmin();
    }

    /**
     * Dashboard quản trị
     */
    public function index(): void
    {
        $this->checkAdmin();

        $productModel = new Product();
        $orderModel = new Order();
        $userModel = new User();

        $totalProducts = $productModel->count();
        $totalOrders = $orderModel->count();
        $totalUsers = $userModel->count();
        $totalRevenue = $orderModel->totalRevenue();
        $latestOrders = $orderModel->latest(5);

        $this->view(
            'admin/index',
            compact(
                'totalProducts',
                'totalOrders',
                'totalUsers',
                'totalRevenue',
                'latestOrders'
            )
        );
    }

    /**
     * Danh sách và quản lý sản phẩm
     */
    public function products(): void
    {
        $this->checkAdmin();

        $productModel = new Product();

        $keyword = trim($_GET['keyword'] ?? '');
        $stockFilter = trim($_GET['stock'] ?? '');

        $minPriceInput = trim($_GET['min_price'] ?? '');
        $maxPriceInput = trim($_GET['max_price'] ?? '');

        $minPrice = $this->parsePrice($minPriceInput);
        $maxPrice = $this->parsePrice($maxPriceInput);

        if (
            $keyword !== ''
            || $stockFilter !== ''
            || $minPrice !== null
            || $maxPrice !== null
        ) {
            $products = $productModel->filter(
                $keyword,
                $stockFilter,
                $minPrice,
                $maxPrice
            );
        } else {
            $products = $productModel->allAdmin();
        }

        $this->view(
            'admin/products',
            compact(
                'products',
                'keyword',
                'stockFilter',
                'minPriceInput',
                'maxPriceInput'
            )
        );
    }

    /**
     * Thêm sản phẩm
     */
    public function createProduct(): void
    {
        $this->checkAdmin();

        $name = trim($_POST['name'] ?? '');
        $price = $this->parsePrice($_POST['price'] ?? '');
        $stock = (int) ($_POST['stock'] ?? 0);

        if (
            $name === ''
            || $price === null
            || $price <= 0
            || $stock < 0
        ) {
            $_SESSION['flash'] =
                'Vui lòng nhập tên, giá và số lượng tồn kho hợp lệ.';

            $this->redirect('/admin/products');
            return;
        }

        $salePrice = $this->parsePrice(
            $_POST['sale_price'] ?? ''
        );

        if (
            $salePrice !== null
            && $salePrice > 0
            && $salePrice >= $price
        ) {
            $_SESSION['flash'] =
                'Giá khuyến mãi phải nhỏ hơn giá gốc.';

            $this->redirect('/admin/products');
            return;
        }

        try {
            $created = (new Product())->create([
                'name' => $name,
                'price' => $price,
                'sale_price' => $salePrice,
                'image' => trim($_POST['image'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'stock' => $stock,
                'status' => (int) ($_POST['status'] ?? 1)
            ]);

            $_SESSION['flash'] = $created
                ? 'Thêm sản phẩm thành công.'
                : 'Không thể thêm sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Không thể thêm sản phẩm: ' . $e->getMessage();
        }

        $this->redirect('/admin/products');
    }

    /**
     * Hiển thị trang sửa sản phẩm
     */
    public function editProduct(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] = 'ID sản phẩm không hợp lệ.';
            $this->redirect('/admin/products');
            return;
        }

        $productModel = new Product();
        $product = $productModel->findAdmin($id);

        if (!$product) {
            $_SESSION['flash'] = 'Không tìm thấy sản phẩm.';
            $this->redirect('/admin/products');
            return;
        }

        $this->view(
            'admin/edit-product',
            compact('product')
        );
    }

    /**
     * Cập nhật sản phẩm
     */
    public function updateProduct(): void
    {
        $this->checkAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $price = $this->parsePrice($_POST['price'] ?? '');
        $stock = (int) ($_POST['stock'] ?? 0);

        if (
            $id <= 0
            || $name === ''
            || $price === null
            || $price <= 0
            || $stock < 0
        ) {
            $_SESSION['flash'] =
                'Thông tin sản phẩm không hợp lệ.';

            $this->redirect('/admin/products');
            return;
        }

        $salePrice = $this->parsePrice(
            $_POST['sale_price'] ?? ''
        );

        if (
            $salePrice !== null
            && $salePrice > 0
            && $salePrice >= $price
        ) {
            $_SESSION['flash'] =
                'Giá khuyến mãi phải nhỏ hơn giá gốc.';

            $this->redirect(
                '/admin/products/edit?id=' . $id
            );
            return;
        }

        try {
            $updated = (new Product())->update($id, [
                'name' => $name,
                'price' => $price,
                'sale_price' => $salePrice,
                'image' => trim($_POST['image'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'stock' => $stock,
                'status' => (int) ($_POST['status'] ?? 1)
            ]);

            $_SESSION['flash'] = $updated
                ? 'Cập nhật sản phẩm thành công.'
                : 'Không thể cập nhật sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Lỗi cập nhật sản phẩm: ' . $e->getMessage();
        }

        $this->redirect('/admin/products');
    }

    /**
     * Xóa sản phẩm
     */
    public function deleteProduct(): void
    {
        $this->checkAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] = 'ID sản phẩm không hợp lệ.';
            $this->redirect('/admin/products');
            return;
        }

        try {
            $productModel = new Product();
            $deleted = $productModel->delete($id);

            $_SESSION['flash'] = $deleted
                ? 'Đã xóa sản phẩm thành công.'
                : 'Không thể xóa sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Không thể xóa sản phẩm này vì có thể đã tồn tại trong đơn hàng.';
        }

        $this->redirect('/admin/products');
    }

    /**
     * Danh sách đơn hàng
     */
    public function orders(): void
    {
        $this->checkAdmin();

        $orders = (new Order())->all();

        $this->view(
            'admin/orders',
            compact('orders')
        );
    }

    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail(): void
    {
        $this->checkAdmin();

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
        $this->checkAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        $allowedStatus = [
            'pending',
            'confirmed',
            'shipping',
            'completed',
            'cancelled'
        ];

        if (
            $id <= 0
            || !in_array($status, $allowedStatus, true)
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
     * Trang quản lý doanh thu
     */
    public function revenue(): void
    {
        $this->checkAdmin();

        $orderModel = new Order();

        /*
         * Tên biến ở đây phải giống với revenue.php.
         */
        $revenueToday = $orderModel->todayRevenue();

        $revenueThisMonth = $orderModel->monthRevenue();

        $totalRevenue = $orderModel->totalRevenue();

        $completedOrders = $orderModel->completedCount();

        $completedOrderList = $orderModel->completedOrders();

        $this->view(
            'admin/revenue',
            compact(
                'revenueToday',
                'revenueThisMonth',
                'totalRevenue',
                'completedOrders',
                'completedOrderList'
            )
        );
    }

    /**
     * Danh sách người dùng
     */
    public function users(): void
    {
        $this->checkAdmin();

        $users = (new User())->all();

        $this->view(
            'admin/users',
            compact('users')
        );
    }

    /**
     * Cập nhật quyền người dùng
     */
    public function updateUserRole(): void
    {
        $this->checkAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $role = trim($_POST['role'] ?? '');

        $allowedRoles = [
            'admin',
            'customer'
        ];

        if (
            $id <= 0
            || !in_array($role, $allowedRoles, true)
        ) {
            $_SESSION['flash'] =
                'Dữ liệu người dùng không hợp lệ.';

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
     * Chuyển giá tiền từ form sang số.
     *
     * Ví dụ:
     * 10.000.000
     * 10,000,000
     * 10000000
     */
    private function parsePrice(string $value): ?float
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $value = str_replace(
            ['.', ',', ' '],
            '',
            $value
        );

        if (!is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }
}