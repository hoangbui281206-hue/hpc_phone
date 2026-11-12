<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Product;

class ProductController extends BaseController
{
    /**
     * Danh sách sản phẩm
     */
    public function index(): void
    {
        $keyword = trim(
            (string) ($_GET['keyword'] ?? '')
        );

        $stock = trim(
            (string) ($_GET['stock'] ?? '')
        );

        $minPriceInput = str_replace(
            ['.', ',', ' '],
            '',
            trim((string) ($_GET['min_price'] ?? ''))
        );

        $maxPriceInput = str_replace(
            ['.', ',', ' '],
            '',
            trim((string) ($_GET['max_price'] ?? ''))
        );

        $minPrice = $minPriceInput !== ''
            ? (float) $minPriceInput
            : 0;

        $maxPrice = $maxPriceInput !== ''
            ? (float) $maxPriceInput
            : 0;

        $productModel = new Product();

        $products = $productModel->filter(
            $keyword,
            $stock,
            $minPrice,
            $maxPrice
        );

        $this->view(
            'products/index',
            compact(
                'products',
                'keyword',
                'stock',
                'minPrice',
                'maxPrice'
            )
        );
    }

    /**
     * Chi tiết sản phẩm
     *
     * ID được Router truyền từ URL:
     * /products/{id}
     */
    public function show(int $id = 0): void
    {
        /*
         * Dự phòng trường hợp Router chưa truyền
         * tham số trực tiếp vào hàm.
         */
        if ($id <= 0) {
            $id = (int) ($_GET['id'] ?? 0);
        }

        if ($id <= 0) {
            $_SESSION['flash'] =
                'Mã sản phẩm không hợp lệ.';

            $this->redirect('/products');
            return;
        }

        $productModel = new Product();

        $product = $productModel->find($id);

        if (!$product) {
            $_SESSION['flash'] =
                'Không tìm thấy sản phẩm.';

            $this->redirect('/products');
            return;
        }

        $this->view(
            'products/show',
            compact('product')
        );
    }
}