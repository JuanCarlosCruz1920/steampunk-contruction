<?php
require_once __DIR__ . '/../src/helpers/functions.php';
require_once __DIR__ . '/../src/controllers/CartController.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('/login.php');
}
// Redirect admin users to the admin dashboard
if (isAdmin()) {
    redirect('/admin/dashboard.php');
}

$cartController = new CartController();
$cartItems = $cartController->getCart();

// Handle remove/update actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove']) && isset($_POST['product_id'])) {
        $cartController->removeFromCart($_POST['product_id']);
        setFlashMessage('Item removed from cart.', 'success');
        header('Location: /cart.php');
        exit();
    }
    if (isset($_POST['update']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $qty = max(1, (int)$_POST['quantity']);
        $cartController->updateCartItem($_POST['product_id'], $qty);
        setFlashMessage('Cart updated.', 'success');
        header('Location: /cart.php');
        exit();
    }
    if (isset($_POST['checkout'])) {
        // For demo, use 'cash' as payment method
        $result = $cartController->checkout($_SESSION['user_id'], 'cash');
        if ($result['success']) {
            setFlashMessage('Order placed successfully! Order ID: ' . $result['order_id'], 'success');
            header('Location: /orders.php');
            exit();
        } else {
            setFlashMessage($result['error'] ?? 'Checkout failed.', 'error');
        }
    }
    // Refresh cart items after any action
    $cartItems = $cartController->getCart();
}

function getProductImageUrl($imagePath) {
    if (!$imagePath) {
        return '/images/products/default.jpg';
    }
    if (strpos($imagePath, 'products/') === 0) {
        return '/images/' . $imagePath;
    }
    return '/images/products/' . $imagePath;
}
?>

<?php require_once __DIR__ . '/../src/views/partials/header.php'; ?>

<div class="brass-panel">
    <h1 class="gears-title">🛒 Your Cart</h1>
    <div class="copper-divider"></div>
    <?php displayFlashMessage(); ?>
    <?php if (empty($cartItems)): ?>
        <div class="empty-cart-actions">
            <div class="steam-alert">Your cart is empty.</div>
            <a href="/dashboard.php" class="piston-button">Browse Products</a>
        </div>
    <?php else: ?>
        <form method="post">
        <table class="steam-table" style="width:100%; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($cartItems as $productId => $item): ?>
                    <?php $subtotal = $item['product']['price'] * $item['quantity']; $total += $subtotal; ?>
                    <tr>
                        <td style="display:flex; align-items:center; gap:10px;">
                            <img src="<?= htmlspecialchars(getProductImageUrl($item['product']['image_path'] ?? 'default.jpg')) ?>" alt="<?= htmlspecialchars($item['product']['name']) ?>" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                            <span><?= htmlspecialchars($item['product']['name']) ?></span>
                        </td>
                        <td><?= formatPrice($item['product']['price']) ?></td>
                        <td>
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width:60px;">
                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                            <button type="submit" name="update" class="piston-button small">Update</button>
                        </td>
                        <td><?= formatPrice($subtotal) ?></td>
                        <td>
                            <button type="submit" name="remove" value="1" class="piston-button small" onclick="return confirm('Remove this item?')">Remove</button>
                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right;">Total:</th>
                    <th><?= formatPrice($total) ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <button type="submit" name="checkout" class="piston-button">Checkout</button>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../src/views/partials/footer.php'; ?> 