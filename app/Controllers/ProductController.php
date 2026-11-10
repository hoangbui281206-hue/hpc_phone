<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Product;

class ProductController extends BaseController
{
    public function index(): void
    {
        $products = (new Product())->all();
        $this->view('products/index', compact('products'));
    }

    public function show(string $id): void
    {
        $product = (new Product())->find((int)$id);
        if (!$product) {
            http_response_code(404);
            exit('Không tìm thấy sản phẩm.');
        }
        $this->view('products/show', compact('product'));
    }
}
