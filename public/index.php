<?php
declare(strict_types=1);

/**
 * ==========================================
 * NEXO TECH - ĐIỂM KHỞI ĐỘNG ỨNG DỤNG
 * ==========================================
 */

session_start();

/**
 * Đường dẫn gốc dự án
 */
define('BASE_PATH', dirname(__DIR__));

/**
 * Composer Autoload
 */
require BASE_PATH . '/vendor/autoload.php';

/**
 * Load file .env
 */
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

/**
 * Router
 */
use App\Core\Router;

$router = new Router();

/*
|--------------------------------------------------------------------------
| ROUTE KHÁCH HÀNG
|--------------------------------------------------------------------------
*/

/**
 * Trang chủ
 */
$router->get('/', 'HomeController@index');

/**
 * Danh sách sản phẩm
 */
$router->get('/products', 'ProductController@index');

/**
 * Chi tiết sản phẩm
 */
$router->get('/products/{id}', 'ProductController@show');

/*
|--------------------------------------------------------------------------
| GIỎ HÀNG
|--------------------------------------------------------------------------
*/

$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');

/*
|--------------------------------------------------------------------------
| TÀI KHOẢN
|--------------------------------------------------------------------------
*/

$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');

$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');

$router->post('/logout', 'AuthController@logout');

/*
|--------------------------------------------------------------------------
| ĐẶT HÀNG VÀ THANH TOÁN
|--------------------------------------------------------------------------
*/

/**
 * Tạo đơn hàng
 */
$router->post('/checkout', 'OrderController@checkout');

/**
 * Thanh toán online
 */
$router->get('/payment/{id}', 'PaymentController@index');
$router->post('/payment/confirm', 'PaymentController@confirm');

/*
|--------------------------------------------------------------------------
| ĐƠN HÀNG CỦA KHÁCH HÀNG
|--------------------------------------------------------------------------
*/

/**
 * Danh sách đơn hàng của tôi
 */
$router->get('/my-orders', 'OrderController@myOrders');

/**
 * Chi tiết đơn hàng của tôi
 */
$router->get(
    '/my-orders/detail',
    'OrderController@myOrderDetail'
);

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
*/

/**
 * Dashboard
 */
$router->get('/admin', 'AdminController@index');

/**
 * Quản lý doanh thu
 * PHẢI đặt trước router->dispatch()
 */
$router->get(
    '/admin/revenue',
    'AdminController@revenue'
);

/*
|--------------------------------------------------------------------------
| QUẢN LÝ SẢN PHẨM
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/products',
    'AdminController@products'
);

$router->post(
    '/admin/products/create',
    'AdminController@createProduct'
);

$router->get(
    '/admin/products/edit',
    'AdminController@editProduct'
);

$router->post(
    '/admin/products/update',
    'AdminController@updateProduct'
);

$router->post(
    '/admin/products/delete',
    'AdminController@deleteProduct'
);

/*
|--------------------------------------------------------------------------
| QUẢN LÝ ĐƠN HÀNG ADMIN
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/orders',
    'AdminController@orders'
);

$router->get(
    '/admin/orders/detail',
    'AdminController@orderDetail'
);

$router->post(
    '/admin/orders/status',
    'AdminController@updateOrderStatus'
);

/*
|--------------------------------------------------------------------------
| QUẢN LÝ NGƯỜI DÙNG
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/users',
    'AdminController@users'
);

$router->post(
    '/admin/users/role',
    'AdminController@updateUserRole'
);

/*
|--------------------------------------------------------------------------
| CHẠY ROUTER
|--------------------------------------------------------------------------
*/

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$requestUri = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
);

$router->dispatch(
    $requestMethod,
    $requestUri ?: '/'
);