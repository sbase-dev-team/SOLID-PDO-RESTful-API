<?php

namespace App\Models;

class ProductInOrder
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $quantity;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
