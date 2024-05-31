<?php

namespace App\Controllers;

use App\Services\ProductService;
use App\Utils\Response;

class ProductController
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function getAllProducts()
    {
        $products = $this->productService->getAllProducts();
        echo Response::json($products);
    }

    public function createProduct()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $product = $this->productService->createProduct($data['name'], $data['price']);
        echo Response::json($product, 201);
    }
}
