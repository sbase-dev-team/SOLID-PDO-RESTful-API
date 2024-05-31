<?php

namespace App\Repositories;

use PDO;
use App\Models\ProductInOrder;

class ProductInOrderRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = \App\Database\DatabaseConnection::getInstance();
    }

    public function create(int $orderId, int $productId, int $quantity): ProductInOrder
    {
        $data = $this->db->prepare('INSERT INTO products_in_orders (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)');
        $data->execute([
            ':order_id' => $orderId,
            ':product_id' => $productId,
            ':quantity' => $quantity
        ]);
        return $this->find($this->db->lastInsertId());
    }

    public function find(int $id): ?ProductInOrder
    {
        $data = $this->db->prepare('SELECT * FROM products_in_orders WHERE id = :id');
        $data->execute([':id' => $id]);
        $data->setFetchMode(PDO::FETCH_CLASS, ProductInOrder::class);
        return $data->fetch() ?: null;
    }

    public function findAll(): array
    {
        $data = $this->db->query('SELECT * FROM products_in_orders');
        return $data->fetchAll(PDO::FETCH_CLASS, ProductInOrder::class);
    }

    public function findByOrderId(int $orderId): array
    {
        $data = $this->db->prepare('SELECT * FROM products_in_orders WHERE order_id = :order_id');
        $data->execute([':order_id' => $orderId]);
        return $data->fetchAll(PDO::FETCH_CLASS, ProductInOrder::class);
    }
}
