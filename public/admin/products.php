<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
require_once __DIR__ . '/../../src/helpers/functions.php';
require_once __DIR__ . '/../../src/controllers/AdminController.php';

if (!isAdmin()) {
    header('Location: /login.php');
    exit();
}

$adminController = new AdminController();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $adminController->deleteProduct($_POST['delete_id']);
    } else {
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'stock_quantity' => $_POST['stock_quantity'],
            'image_path' => $_FILES['image']['name'] ?? 'default.jpg'
        ];
        
        if (isset($_POST['product_id'])) {
            // Update existing product
            $adminController->updateProduct($_POST['product_id'], $data);
        } else {
            // Create new product
            $adminController->createProduct($data);
        }
        
        // Handle file upload
        if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
            $targetDir = __DIR__ . '/../../public/images/products/';
            $targetFile = $targetDir . basename($_FILES['image']['name']);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                error_log('Failed to move uploaded file.');
            }
        }
    }
}

$products = $adminController->getAllProducts();
$editingProduct = isset($_GET['edit']) ? $adminController->getProductById($_GET['edit']) : null;
?>

<?php require_once __DIR__ . '/../../src/views/partials/header.php'; ?>

<div class="admin-container">
    <div class="admin-header brass-panel">
        <h1 class="gears-title">🛠️ Product Management</h1>
    </div>

    <!-- Product Form -->
    <div class="brass-panel product-form">
        <h2><?= $editingProduct ? 'Edit Product' : 'Add New Product' ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($editingProduct): ?>
                <input type="hidden" name="product_id" value="<?= $editingProduct['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?= $editingProduct['name'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required><?= $editingProduct['description'] ?? '' ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Price ($):</label>
                    <input type="number" step="0.01" name="price" value="<?= $editingProduct['price'] ?? '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Stock Quantity:</label>
                    <input type="number" name="stock_quantity" value="<?= $editingProduct['stock_quantity'] ?? '' ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Product Image:</label>
                <input type="file" name="image" accept="image/*">
                <?php if ($editingProduct && $editingProduct['image_path']): ?>
                    <div class="current-image">
                        <img src="/images/products/<?= $editingProduct['image_path'] ?>" class="product-thumbnail">
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="piston-button">
                <?= $editingProduct ? 'Update Product' : 'Add Product' ?>
            </button>
            
            <?php if ($editingProduct): ?>
                <a href="/admin/products.php" class="steam-btn">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Products Table -->
    <div class="brass-panel">
        <h2>Current Products</h2>
        <table class="steam-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <img src="/images/products/<?= $product['image_path'] ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="product-thumbnail">
                    </td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= $product['stock_quantity'] ?></td>
                    <td class="actions">
                        <a href="/admin/products.php?edit=<?= $product['id'] ?>" 
                           class="steam-btn small">Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="steam-btn small danger" 
                                    onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../src/views/partials/footer.php'; ?>