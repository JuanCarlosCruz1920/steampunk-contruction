<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Orders.php';

class CartController {
    private $productModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }

    public function addToCart($productId, $quantity = 1) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $product = $this->productModel->getProductById($productId);
        
        if (!$product) {
            return ['success' => false, 'error' => 'Product not found'];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }

        return ['success' => true, 'cart' => $_SESSION['cart']];
    }

    public function removeFromCart($productId) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            return ['success' => true, 'cart' => $_SESSION['cart']];
        }

        return ['success' => false, 'error' => 'Product not in cart'];
    }

    public function updateCartItem($productId, $quantity) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        if (isset($_SESSION['cart'][$productId])) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
            }
            return ['success' => true, 'cart' => $_SESSION['cart']];
        }

        return ['success' => false, 'error' => 'Product not in cart'];
    }

    public function getCart() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        return $_SESSION['cart'] ?? [];
    }

    public function checkout($userId, $paymentMethod) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        if (empty($_SESSION['cart'])) {
            return ['success' => false, 'error' => 'Cart is empty'];
        }

        // Debug: Check userId
        if (empty($userId)) {
            return ['success' => false, 'error' => 'User ID is missing or invalid'];
        }

        try {
            // Calculate total
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['product']['price'] * $item['quantity'];
            }

            // Create order
            $orderId = $this->orderModel->createOrder([
                'user_id' => $userId,
                'total_amount' => $total,
                'payment_method' => $paymentMethod,
                'status' => 'pending' // Changed from 'completed' to 'pending'
            ]);

            // Debug: Check orderId
            if (empty($orderId) || !is_numeric($orderId) || $orderId <= 0) {
                return [
                    'success' => false,
                    'error' => 'Order creation failed. Invalid order ID.',
                    'debug' => [
                        'user_id' => $userId,
                        'total_amount' => $total,
                        'payment_method' => $paymentMethod,
                        'orderId' => $orderId
                    ]
                ];
            }

            // Add order items
            foreach ($_SESSION['cart'] as $productId => $item) {
                $addItemResult = $this->orderModel->addOrderItem([
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['product']['price']
                ]);
                // Debug: Check addOrderItem result
                if (!$addItemResult) {
                    return [
                        'success' => false,
                        'error' => 'Failed to add order item.',
                        'debug' => [
                            'order_id' => $orderId,
                            'product_id' => $productId,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['product']['price']
                        ]
                    ];
                }

                // Update stock
                $this->productModel->updateProduct($productId, [
                    'stock_quantity' => $item['product']['stock_quantity'] - $item['quantity']
                ]);
            }

            // Clear cart
            unset($_SESSION['cart']);

            return ['success' => true, 'order_id' => $orderId];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>