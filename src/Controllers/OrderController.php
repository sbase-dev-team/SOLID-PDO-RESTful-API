<?php

namespace App\Controllers;

use App\Repositories\OrderRepository;
use App\Utils\Response;

class OrderController
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function getAllOrders()
    {
        try {
            $orders = $this->orderRepository->getAllOrders();
            Response::json($orders, 200);
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 500);
        }
    }

    public function createOrder()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['products']) || !is_array($data['products'])) {
            echo Response::json(['error' => 'Invalid input'], 400);
            return;
        }

        try {
            $orderId = $this->orderRepository->createOrder($data['products']);
            echo Response::json(['success' => true, 'order_id' => $orderId], 201);
        } catch (\Exception $e) {
            echo Response::json(['error' => 'Order creation failed: ' . $e->getMessage()], 500);
        }
    }

    public function getOrder($id)
    {
        try {
            $order = $this->orderRepository->getOrderById($id);
            if ($order) {
                Response::json($order, 200);
            } else {
                Response::json(['error' => 'Order not found'], 404);
            }
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 500);
        }
    }
}
