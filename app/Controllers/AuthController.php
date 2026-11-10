<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;

class AuthController extends BaseController
{
    public function registerForm(): void { $this->view('auth/register'); }
    public function loginForm(): void { $this->view('auth/login'); }

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        if (!$name || !$email || strlen($password) < 8) {
            $_SESSION['flash'] = 'Vui lòng nhập đúng thông tin; mật khẩu tối thiểu 8 ký tự.';
            $this->redirect('/register');
        }
        $model = new User();
        if ($model->findByEmail($email)) {
            $_SESSION['flash'] = 'Email đã được sử dụng.';
            $this->redirect('/register');
        }
        $model->create($name, $email, $password);
        $_SESSION['flash'] = 'Đăng ký thành công. Hãy đăng nhập.';
        $this->redirect('/login');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $user = (new User())->findByEmail($email);
        if (!$user || !password_verify($_POST['password'] ?? '', $user['password'])) {
            $_SESSION['flash'] = 'Email hoặc mật khẩu không chính xác.';
            $this->redirect('/login');
        }
        session_regenerate_id(true);
        unset($user['password']);
        $_SESSION['user'] = $user;
        $this->redirect('/');
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
        $this->redirect('/');
    }
}
