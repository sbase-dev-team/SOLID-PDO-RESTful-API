<?php

namespace App\Services;

use App\Repositories\ProductInOrderRepository;

class ProductInOrderService
{
    private ProductInOrderRepository $productInOrderRepository;

    public function __construct()
    {
        $this->productInOrderRepository = new ProductInOrderRepository();
    }

    public function addProductToOrder(int $orderId, int $productId, int $quantity)
    {
        return $this->productInOrderRepository->create($orderId, $productId, $quantity);
    }

    public function getProductsInOrder(int $orderId): array
    {
        return $this->productInOrderRepository->findByOrderId($orderId);
    }
}
