<?php

require_once __DIR__ . '/src/Database/DatabaseConnection.php';

use App\Database\DatabaseConnection;

function loadEnv($file)
{
    if (!file_exists($file)) {
        throw new Exception("The .env file does not exist.");
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

function createDatabase(PDO $db, $dbname)
{
    try {
        $db->exec("CREATE DATABASE IF NOT EXISTS $dbname");
        echo "Database `$dbname` created successfully.\n";
    } catch (PDOException $e) {
        echo "Failed to create database: " . $e->getMessage();
    }
}

function createTables(PDO $db)
{
    try {
        $db->exec("CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(10, 2) NOT NULL
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS products_in_orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )");

        echo "Tables created successfully.\n";
    } catch (PDOException $e) {
        echo "Failed to create tables: " . $e->getMessage();
    }
}

function populateDatabase(PDO $db)
{
    try {
        // Start a transaction
        $db->beginTransaction();

        // Insert products
        $products = [
            ['name' => 'Product 1', 'price' => 10.00],
            ['name' => 'Product 2', 'price' => 20.00],
            ['name' => 'Product 3', 'price' => 30.00]
        ];

        $stmt = $db->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
        foreach ($products as $product) {
            $stmt->execute([
                ':name' => $product['name'],
                ':price' => $product['price']
            ]);
        }

        // Insert orders without specifying created_at, using NOW() for current timestamp
        $stmt = $db->prepare("INSERT INTO orders (created_at) VALUES (NOW())");
        $stmt->execute();
        $orderId1 = $db->lastInsertId();

        $stmt->execute();
        $orderId2 = $db->lastInsertId();

        $stmt->execute();
        $orderId3 = $db->lastInsertId();

        // Insert products_in_orders
        $productsInOrders = [
            ['order_id' => $orderId1, 'product_id' => 1, 'quantity' => 2],
            ['order_id' => $orderId1, 'product_id' => 2, 'quantity' => 1],
            ['order_id' => $orderId2, 'product_id' => 2, 'quantity' => 3],
            ['order_id' => $orderId3, 'product_id' => 3, 'quantity' => 1],
            ['order_id' => $orderId3, 'product_id' => 1, 'quantity' => 5]
        ];

        $stmt = $db->prepare("INSERT INTO products_in_orders (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
        foreach ($productsInOrders as $productInOrder) {
            $stmt->execute([
                ':order_id' => $productInOrder['order_id'],
                ':product_id' => $productInOrder['product_id'],
                ':quantity' => $productInOrder['quantity']
            ]);
        }

        $db->commit();

        echo "Database populated successfully.\n";
    } catch (PDOException $e) {
        $db->rollBack();
        echo "Failed to populate database: " . $e->getMessage();
    }
}

loadEnv(__DIR__ . '/.env');
$rootDb = new PDO("mysql:host=localhost", getenv('DB_ROOT_USER'), getenv('DB_ROOT_PASSWORD'));

createDatabase($rootDb, getenv('DB_NAME'));
$db = new PDO("mysql:host=localhost;dbname=" . getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));

createTables($db);
populateDatabase($db);