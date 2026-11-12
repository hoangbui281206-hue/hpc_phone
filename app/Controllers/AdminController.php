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
     * Quản lý doanh thu
     */
    public function revenue(): void
    {
        $this->requireAdmin();

        $orderModel = new Order();

        $totalRevenue =
            $orderModel->totalCompletedRevenue();

        $completedOrders =
            $orderModel->completedOrderCount();

        $todayRevenue =
            $orderModel->todayRevenue();

        $monthlyRevenue =
            $orderModel->revenueByMonth();

        $orders =
            $orderModel->completedOrders();

        $this->view(
            'admin/revenue',
            compact(
                'totalRevenue',
                'completedOrders',
                'todayRevenue',
                'monthlyRevenue',
                'orders'
            )
        );
    }

    /**
     * Quản lý sản phẩm
     */
    public function products(): void
    {
        $this->requireAdmin();

        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');

        $products = $keyword !== ''
            ? $productModel->search($keyword)
            : $productModel->allAdmin();

        $this->view(
            'admin/products',
            compact(
                'products',
                'keyword'
            )
        );
    }

    /**
     * Thêm sản phẩm
     */
    public function createProduct(): void
    {
        $this->requireAdmin();

        $name = trim($_POST['name'] ?? '');

        $priceInput = trim($_POST['price'] ?? '');

        $priceInput = str_replace(
            ['.', ',', ' '],
            '',
            $priceInput
        );

        $price = (float) $priceInput;
        $stock = (int) ($_POST['stock'] ?? 0);

        if ($name === '' || $price <= 0 || $stock < 0) {
            $_SESSION['flash'] =
                'Vui lòng nhập thông tin sản phẩm hợp lệ.';

            $this->redirect('/admin/products');
        }

        $salePrice = null;

        if (!empty($_POST['sale_price'])) {
            $salePriceInput = str_replace(
                ['.', ',', ' '],
                '',
                trim($_POST['sale_price'])
            );

            $salePrice = (float) $salePriceInput;

            if ($salePrice <= 0) {
                $salePrice = null;
            }
        }

        try {
            $created = (new Product())->create([
                'name' => $name,
                'price' => $price,
                'sale_price' => $salePrice,
                'image' => trim($_POST['image'] ?? ''),
                'description' =>
                    trim($_POST['description'] ?? ''),
                'stock' => $stock,
                'status' =>
                    (int) ($_POST['status'] ?? 1)
            ]);

            $_SESSION['flash'] = $created
                ? 'Thêm sản phẩm thành công.'
                : 'Không thể thêm sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Không thể thêm sản phẩm: '
                . $e->getMessage();
        }

        $this->redirect('/admin/products');
    }

    /**
     * Trang sửa sản phẩm
     */
    public function editProduct(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        $product = (new Product())->findAdmin($id);

        if (!$product) {
            $_SESSION['flash'] =
                'Không tìm thấy sản phẩm.';

            $this->redirect('/admin/products');
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
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        $priceInput = str_replace(
            ['.', ',', ' '],
            '',
            trim($_POST['price'] ?? '')
        );

        $price = (float) $priceInput;
        $stock = (int) ($_POST['stock'] ?? 0);

        if ($id <= 0 || $name === '' || $price <= 0) {
            $_SESSION['flash'] =
                'Dữ liệu sản phẩm không hợp lệ.';

            $this->redirect('/admin/products');
        }

        $salePrice = null;

        if (!empty($_POST['sale_price'])) {
            $salePriceInput = str_replace(
                ['.', ',', ' '],
                '',
                trim($_POST['sale_price'])
            );

            $salePrice = (float) $salePriceInput;
        }

        try {
            $updated = (new Product())->update(
                $id,
                [
                    'name' => $name,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'image' =>
                        trim($_POST['image'] ?? ''),
                    'description' =>
                        trim($_POST['description'] ?? ''),
                    'stock' => $stock,
                    'status' =>
                        (int) ($_POST['status'] ?? 1)
                ]
            );

            $_SESSION['flash'] = $updated
                ? 'Cập nhật sản phẩm thành công.'
                : 'Không thể cập nhật sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Lỗi cập nhật sản phẩm: '
                . $e->getMessage();
        }

        $this->redirect('/admin/products');
    }

    /**
     * Xóa sản phẩm
     */
    public function deleteProduct(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] =
                'ID sản phẩm không hợp lệ.';

            $this->redirect('/admin/products');
        }

        try {
            $productModel = new Product();

            $product =
                $productModel->findAdmin($id);

            if (!$product) {
                $_SESSION['flash'] =
                    'Không tìm thấy sản phẩm.';

                $this->redirect('/admin/products');
            }

            $deleted = $productModel->delete($id);

            $_SESSION['flash'] = $deleted
                ? 'Đã xóa sản phẩm thành công.'
                : 'Không thể xóa sản phẩm.';

        } catch (\Throwable $e) {
            $_SESSION['flash'] =
                'Không thể xóa sản phẩm này vì sản phẩm '
                . 'có thể đã tồn tại trong đơn hàng.';
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
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order) {
            $_SESSION['flash'] =
                'Không tìm thấy đơn hàng.';

            $this->redirect('/admin/orders');
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
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $role = trim($_POST['role'] ?? '');

        $updated = (new User())->updateRole(
            $id,
            $role
        );

        $_SESSION['flash'] = $updated
            ? 'Đã cập nhật quyền người dùng.'
            : 'Không thể cập nhật quyền người dùng.';

        $this->redirect('/admin/users');
    }
}