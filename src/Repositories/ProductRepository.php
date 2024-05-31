<?php

namespace App\Repositories;

use PDO;
use App\Models\Product;

class ProductRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = \App\Database\DatabaseConnection::getInstance();
    }

    public function create(string $name, float $price): Product
    {
        $data = $this->db->prepare('INSERT INTO products (name, price) VALUES (:name, :price)');
        $data->execute([':name' => $name, ':price' => $price]);
        return $this->find($this->db->lastInsertId());
    }

    public function find(int $id): ?Product
    {
        $data = $this->db->prepare('SELECT * FROM products WHERE id = :id');
        $data->execute([':id' => $id]);
        $data->setFetchMode(PDO::FETCH_CLASS, Product::class);
        return $data->fetch() ?: null;
    }

    public function findAll(): array
    {
        $data = $this->db->query('SELECT * FROM products');
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
}
