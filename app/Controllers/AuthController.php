<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;

class AuthController extends BaseController
{
    /**
     * Hiển thị trang đăng ký
     */
    public function registerForm(): void
    {
        $this->view('auth/register');
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function loginForm(): void
    {
        $this->view('auth/login');
    }

    /**
     * Xử lý đăng ký tài khoản
     */
    public function register(): void
    {
        $name = trim(
            (string) ($_POST['name'] ?? '')
        );

        $emailInput = trim(
            (string) ($_POST['email'] ?? '')
        );

        $email = filter_var(
            $emailInput,
            FILTER_VALIDATE_EMAIL
        );

        $password = (string) (
            $_POST['password'] ?? ''
        );

        /*
         * Kiểm tra dữ liệu
         */
        if (
            $name === ''
            || $email === false
            || strlen($password) < 8
        ) {
            $_SESSION['flash'] =
                'Vui lòng nhập đầy đủ thông tin. Mật khẩu tối thiểu 8 ký tự.';

            $this->redirect('/register');
            return;
        }

        try {
            $userModel = new User();

            /*
             * Kiểm tra email đã tồn tại
             */
            if ($userModel->findByEmail($email)) {
                $_SESSION['flash'] =
                    'Email này đã được sử dụng.';

                $this->redirect('/register');
                return;
            }

            /*
             * Tạo tài khoản mới
             */
            $created = $userModel->create(
                $name,
                $email,
                $password
            );

            if (!$created) {
                $_SESSION['flash'] =
                    'Không thể tạo tài khoản. Vui lòng thử lại.';

                $this->redirect('/register');
                return;
            }

            $_SESSION['flash'] =
                'Đăng ký thành công. Hãy đăng nhập.';

            $this->redirect('/login');
            return;

        } catch (\Throwable $e) {

            $_SESSION['flash'] =
                'Có lỗi khi đăng ký: ' . $e->getMessage();

            $this->redirect('/register');
            return;
        }
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(): void
    {
        $email = trim(
            (string) ($_POST['email'] ?? '')
        );

        $password = (string) (
            $_POST['password'] ?? ''
        );

        $userModel = new User();

        $user = $userModel->findByEmail($email);

        if (
            !$user
            || !password_verify(
                $password,
                $user['password']
            )
        ) {
            $_SESSION['flash'] =
                'Email hoặc mật khẩu không chính xác.';

            $this->redirect('/login');
            return;
        }

        session_regenerate_id(true);

        unset($user['password']);

        $_SESSION['user'] = $user;

        $this->redirect('/');
        return;
    }

    /**
     * Đăng xuất
     */
    public function logout(): void
    {
        unset($_SESSION['user']);

        session_regenerate_id(true);

        $_SESSION['flash'] =
            'Bạn đã đăng xuất thành công.';

        $this->redirect('/');
    }
}