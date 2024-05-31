<?php

namespace App\Routes;

use App\Controllers\ProductController;
use App\Controllers\OrderController;

class Router
{
    public static function route($method, $url)
    {
        header('Content-Type: application/json');
        $productController = new ProductController();
        $orderController = new OrderController();

        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'];

        switch ($path) {
            case '/products':
                if ($method === 'GET') {
                    $productController->getAllProducts();
                } elseif ($method === 'POST') {
                    $productController->createProduct();
                }
                break;

            case '/orders':
                if ($method === 'GET') {
                    $orderController->getAllOrders();
                } elseif ($method === 'POST') {
                    $orderController->createOrder();
                }
                break;

            default:
                if (preg_match('/\/orders\/(\d+)/', $path, $matches)) {
                    if ($method === 'GET') {
                        $orderController->getOrder($matches[1]);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Not Found']);
                }
                break;
        }
    }
}
