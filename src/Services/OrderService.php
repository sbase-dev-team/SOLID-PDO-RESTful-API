<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function createOrder()
    {
        return $this->orderRepository.create();
    }

    public function getAllOrders(): array
    {
        return $this->orderRepository->findAll();
    }

    public function getOrder(int $id)
    {
        return $this->orderRepository->find($id);
    }
}
