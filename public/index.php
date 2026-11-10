<?php
declare(strict_types=1);

/**
 * Khởi động Session
 */
session_start();

/**
 * Đường dẫn gốc của dự án
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
 * Đăng nhập / Đăng xuất
 */
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

/**
 * Thanh toán
 */
$router->post('/checkout', 'OrderController@checkout');

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
*/

/**
 * Dashboard Admin
 */
$router->get('/admin', 'AdminController@index');

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

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$requestUri = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
);

$router->dispatch(
    $requestMethod,
    $requestUri ?: '/'
);