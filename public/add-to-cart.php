<?php
require_once __DIR__ . '/../src/controllers/CartController.php';
require_once __DIR__ . '/../src/helpers/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = $input['productId'] ?? null;
$quantity = $input['quantity'] ?? 1;

if (!$productId) {
    echo json_encode(['success' => false, 'error' => 'No product ID provided']);
    exit;
}

$cartController = new CartController();
$result = $cartController->addToCart($productId, $quantity);

if ($result['success']) {
    echo json_encode(['success' => true, 'cart' => $result['cart']]);
} else {
    echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Unknown error']);
} 