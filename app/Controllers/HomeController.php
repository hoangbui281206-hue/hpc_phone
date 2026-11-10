<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Product;

class HomeController extends BaseController
{
    public function index(): void
    {
        $products = (new Product())->all();
        $this->view('home/index', compact('products'));
    }
}
