<?php

namespace App\Repositories;

use App\Database\DatabaseConnection;
use PDO;

class OrderRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance();
    }

    public function createOrder(array $products, string $createdAt): int
    {
        try {
            $this->db->beginTransaction();

            $data = $this->db->prepare("INSERT INTO orders (created_at) VALUES (:created_at)");
            $data->execute([':created_at' => $createdAt]);
            $orderId = $this->db->lastInsertId();

            $data = $this->db->prepare("INSERT INTO products_in_orders (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");

            foreach ($products as $product) {
                $data->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $product['product_id'],
                    ':quantity' => $product['quantity']
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getAllOrders(): array
    {
        $data = $this->db->query("SELECT id, created_at FROM orders");
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById(int $orderId): array
    {
        $data = $this->db->prepare("
            SELECT o.id, o.created_at, pio.product_id, pio.quantity, p.name AS product_name, p.price AS product_price
            FROM orders o
            LEFT JOIN products_in_orders pio ON o.id = pio.order_id
            LEFT JOIN products p ON pio.product_id = p.id
            WHERE o.id = :order_id
        ");
        $data->execute([':order_id' => $orderId]);
        $orderData = $data->fetchAll(PDO::FETCH_ASSOC);

        if (!$orderData) {
            return [];
        }

        $order = [
            'id' => $orderData[0]['id'],
            'created_at' => $orderData[0]['created_at'],
            'products' => []
        ];

        foreach ($orderData as $row) {
            $order['products'] []= [
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'product_name' => $row['product_name'],
                'product_price' => $row['product_price']
            ];
        }

        return $order;
    }
}
