<?php
require_once __DIR__ . '/../src/helpers/functions.php';
require_once __DIR__ . '/../src/controllers/ProductController.php';
require_once __DIR__ . '/../src/controllers/CartController.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('/login.php');
}
// Redirect admin users to the admin dashboard
if (isAdmin()) {
    redirect('/admin/dashboard.php');
}

$productController = new ProductController();
$cartController = new CartController();

// Get featured products
$featuredProducts = $productController->index();

// Get cart items
$cartItems = $cartController->getCart();
?>

<?php require_once __DIR__ . '/../src/views/partials/header.php'; ?>

<div class="brass-panel dashboard-container">
    <h1 class="gears-title">
        <?php if (isAdmin()): ?>
            <span class="admin-badge">⚙️ ADMIN</span>
        <?php endif; ?>
        Welcome Back, <?= htmlspecialchars($_SESSION['username'] ?? 'Engineer') ?>
    </h1>
    <div class="copper-divider"></div>

    <div class="dashboard-sections">
        <!-- Featured Products Section -->
        <section class="dashboard-section">
            <h2><span class="gear-icon">⚙️</span> Featured Construction Materials</h2>
            
            <div class="product-grid">
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card">
                        <img src="/images/products/<?= htmlspecialchars($product['image_path'] ?? 'default.jpg') ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="product-image sepia-filter">
                        
                        <div class="product-info">
                            <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                            
                            <div class="quality-rating">
                                <?php for (
                                    $i = 1; $i <= 5; $i++): ?>
                                    <span class="gear <?= $i <= ($product['quality_rating'] ?? 3) ? 'active' : '' ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <p class="product-description">
                                <?= htmlspecialchars(substr($product['description'], 0, 100)) ?><?= strlen($product['description']) > 100 ? '...' : '' ?>
                            </p>
                            <div class="product-meta">
                                <span class="product-price"><?= formatPrice($product['price']) ?></span>
                                <span class="product-stock <?= $product['stock_quantity'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                                    <?= $product['stock_quantity'] > 0 ? "In Stock ({$product['stock_quantity']})" : 'Out of Stock' ?>
                                </span>
                            </div>
                            <div class="product-actions">
                                <?php if ($product['stock_quantity'] > 0): ?>
                                    <button type="button" class="piston-button small add-to-cart" data-product-id="<?= $product['id'] ?>">
                                        Add to Cart
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Quantity Modal -->
            <div id="quantity-modal" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
                <div style="background:#fff; padding:2rem; border-radius:8px; min-width:300px; text-align:center; position:relative;">
                    <h2>Select Quantity</h2>
                    <input type="number" id="modal-quantity-input" min="1" value="1" style="width:80px; font-size:1.2rem; margin:1rem 0;">
                    <div style="margin-top:1rem;">
                        <button id="modal-add-btn" class="piston-button">Add</button>
                        <button id="modal-cancel-btn" class="piston-button" style="background:#ccc; color:#333;">Cancel</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Actions Section -->
        <section class="dashboard-section quick-actions">
            <h2><span class="gear-icon">🔧</span> Quick Actions</h2>
            
            <div class="action-grid">
                <a href="/products.php" class="action-card">
                    <div class="action-icon">🛠️</div>
                    <h3>Browse All Materials</h3>
                </a>
                
                <a href="/cart.php" class="action-card">
                    <div class="action-icon">🛒</div>
                    <h3>View Your Cart</h3>
                    <?php if (!empty($cartItems)): ?>
                        <span class="cart-badge"><?= count($cartItems) ?></span>
                    <?php endif; ?>
                </a>
                
                <a href="/orders.php" class="action-card">
                    <div class="action-icon">📦</div>
                    <h3>Your Orders</h3>
                </a>
                
                <?php if (isAdmin()): ?>
                    <a href="/admin/dashboard.php" class="action-card admin-action">
                        <div class="action-icon">⚙️</div>
                        <h3>Admin Panel</h3>
                    </a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../src/views/partials/footer.php'; ?>