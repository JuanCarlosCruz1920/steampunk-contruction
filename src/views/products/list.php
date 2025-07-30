<?php
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$productController = new ProductController();
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
if ($searchQuery !== '') {
    $result = $productController->search($searchQuery);
    $products = $result['products'] ?? [];
} else {
    $products = $productController->index();
}

// Determine correct image path for each product
function getProductImageUrl($imagePath) {
    if (!$imagePath) {
        return '/images/products/default.jpg';
    }
    // If path already contains 'products/', just prepend '/images/'
    if (strpos($imagePath, 'products/') === 0) {
        return '/images/' . $imagePath;
    }
    // Otherwise, assume it's just a filename
    return '/images/products/' . $imagePath;
}
?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="brass-panel">
    <h1 class="gears-title">Our Steampunk Construction Materials</h1>
    <div class="copper-divider"></div>

    <?php if (isAdmin()): ?>
        <div class="admin-actions">
            <a href="/admin/products/create.php" class="piston-button">Add New Product</a>
        </div>
    <?php endif; ?>

    <div class="product-search">
        <form method="GET" action="/products.php" class="steam-form">
            <div class="form-group">
                <input type="text" name="query" placeholder="Search products..." class="gear-input" value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit" class="piston-button small">Search</button>
            </div>
        </form>
    </div>

    <?php displayFlashMessage(); ?>

    <?php if (empty($products)): ?>
        <div class="steam-alert">No products found.</div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars(getProductImageUrl($product['image_path'] ?? 'default.jpg')) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" 
                         class="product-image">
                    
                    <div class="product-info">
                        <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                        
                        <div class="quality-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="gear <?= $i <= $product['quality_rating'] ? 'active' : '' ?>"></span>
                            <?php endfor; ?>
                        </div>
                        
                        <p class="product-description">
                            <?= htmlspecialchars(substr($product['description'], 0, 100)) ?><?= strlen($product['description']) > 100 ? '...' : '' ?>
                        </p>
                        
                        <div class="product-footer">
                            <span class="product-price"><?= formatPrice($product['price']) ?></span>
                            
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <span class="in-stock">In Stock (<?= $product['stock_quantity'] ?>)</span>
                            <?php else: ?>
                                <span class="out-of-stock">Out of Stock</span>
                            <?php endif; ?>
                            
                            <div class="product-actions">
                                <button type="button" class="piston-button small view-details-btn" data-product-id="<?= $product['id'] ?>">Details</button>
                                
                                <?php if (isLoggedIn() && $product['stock_quantity'] > 0): ?>
                                    <button type="button" class="piston-button small add-to-cart" 
                                            data-product-id="<?= $product['id'] ?>">
                                        Add to Cart
                                    </button>
                                <?php endif; ?>
                                
                                <?php if (isAdmin()): ?>
                                    <a href="/admin/products/edit.php?id=<?= $product['id'] ?>" 
                                       class="piston-button small">Edit</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Details Modal -->
                <div id="product-modal-<?= $product['id'] ?>" class="product-modal" style="display:none; position:fixed; z-index:3000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
                    <div class="modal-card">
                        <button class="close-modal-btn" data-product-id="<?= $product['id'] ?>" style="position:absolute; top:1rem; right:1rem; background:none; border:none; font-size:2rem; cursor:pointer;">&times;</button>
                        <img src="<?= htmlspecialchars(getProductImageUrl($product['image_path'] ?? 'default.jpg')) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="divider"></div>
                        <div class="modal-content-area">
                            <h2 class="product-title" style="text-align:center; margin-bottom:0.5rem;"><?= htmlspecialchars($product['name']) ?></h2>
                            <div class="quality-rating" style="text-align:center; margin-bottom:1rem;">
                                <?php for (
                                    $i = 1; $i <= 5; $i++): ?>
                                    <span class="gear <?= $i <= $product['quality_rating'] ? 'active' : '' ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <p class="product-description" style="margin-bottom:1rem; text-align:center; color:#555;">
                                <?= htmlspecialchars($product['description']) ?>
                            </p>
                            <div style="text-align:center; margin-bottom:1rem;">
                                <span class="product-price" style="font-size:1.3rem; font-weight:bold; color:#b8860b;">
                                    <?= formatPrice($product['price']) ?>
                                </span>
                            </div>
                            <div style="text-align:center;">
                                <?php if ($product['stock_quantity'] > 0): ?>
                                    <span class="in-stock">In Stock (<?= $product['stock_quantity'] ?>)</span>
                                <?php else: ?>
                                    <span class="out-of-stock">Out of Stock</span>
                                <?php endif; ?>
                            </div>
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
        
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="pagination">
                <?php foreach (getPaginationLinks($totalItems, $itemsPerPage, $currentPage, '/products') as $link): ?>
                    <a href="<?= $link['url'] ?>" 
                       class="<?= $link['active'] ? 'active' : '' ?>">
                        <?= $link['label'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>