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
 * Sản phẩm
 */
$router->get('/products', 'ProductController@index');
$router->get('/products/{id}', 'ProductController@show');

/**
 * Giỏ hàng
 */
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');

/**
 * Đăng ký
 */
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');

/**
 * Đăng nhập và đăng xuất
 */
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

/*
|--------------------------------------------------------------------------
| ĐẶT HÀNG VÀ THANH TOÁN
|--------------------------------------------------------------------------
*/

$router->post('/checkout', 'OrderController@checkout');

$router->get(
    '/payment/{id}',
    'PaymentController@index'
);

$router->post(
    '/payment/confirm',
    'PaymentController@confirm'
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

/*
|--------------------------------------------------------------------------
| QUẢN LÝ SẢN PHẨM
|--------------------------------------------------------------------------
*/

/**
 * Danh sách sản phẩm
 */
$router->get(
    '/admin/products',
    'AdminController@products'
);

/**
 * Thêm sản phẩm
 */
$router->post(
    '/admin/products/create',
    'AdminController@createProduct'
);

/**
 * Trang sửa sản phẩm
 */
$router->get(
    '/admin/products/edit',
    'AdminController@editProduct'
);

/**
 * Cập nhật sản phẩm
 */
$router->post(
    '/admin/products/update',
    'AdminController@updateProduct'
);

/**
 * Xóa sản phẩm
 */
$router->post(
    '/admin/products/delete',
    'AdminController@deleteProduct'
);

/*
|--------------------------------------------------------------------------
| QUẢN LÝ ĐƠN HÀNG
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

$requestMethod =
    $_SERVER['REQUEST_METHOD'] ?? 'GET';

$requestUri = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
);

$router->dispatch(
    $requestMethod,
    $requestUri ?: '/'
);