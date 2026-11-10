<?php
namespace App\Core;

class BaseController
{
    protected function view(string $name, array $data = []): void
    {
        extract($data);
        require BASE_PATH . '/app/Views/layouts/header.php';
        require BASE_PATH . '/app/Views/' . $name . '.php';
        require BASE_PATH . '/app/Views/layouts/footer.php';
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }

    protected function requireLogin(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireLogin();
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('Bạn không có quyền truy cập.');
        }
    }
}
