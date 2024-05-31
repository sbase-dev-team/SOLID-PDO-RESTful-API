<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function createProduct(string $name, float $price)
    {
        return $this->productRepository->create($name, $price);
    }
}
