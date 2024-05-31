API Documentation:

To create database, tables and populate them, we can use script, from the root of the project.
php populateDatabase.php
This script will create a database with tables based on the .env file and fills them with test data.

Get All Products
URL: /products
example: http://localhost:8080/products
Method: GET
Description: Retrieves a list of all products.
Response:
Status: 200 OK

[
  {
    "id": 1,
    "name": "Some test product",
    "price": 100.00
  }
]

Create a New Product
URL: /products
example: http://localhost:8080/products
Method: POST
Description: Creates a new product.
Headers: Content-Type: application/json
Was tested via Postman

{
    "name": "Some test product",
    "price": 10.00
}

Orders
Get All Orders
URL: /orders
Method: GET
Description: Retrieves a list of all orders.
Response:
Status: 200 OK

[
  {
    "id": 1
  }
]

Create a New Order
URL: /orders
example: http://localhost:8080/orders
Method: POST
Description: Creates a new order with products.
Headers: Content-Type: application/json

{
    "products": [
        {
            "product_id": 1,
            "quantity": 2
        },
        {
            "product_id": 2,
            "quantity": 3
        }
    ]
}

Get a Specific Order
URL: /orders/{id}
example: http://localhost:8080/orders/1
Method: GET
Description: Retrieves details of a specific order.
URL Params: {id} is the ID of the order.
Response:
Status: 200 OK

{
    "id": 1,
    "created_at": "2024-05-30 12:34:56",
    "products": [
        {
            "product_id": 1,
            "quantity": 2,
            "product_name": "Example Product 1",
            "product_price": 19.99
        },
        {
            "product_id": 2,
            "quantity": 3,
            "product_name": "Example Product 2",
            "product_price": 29.99
        }
    ]
}