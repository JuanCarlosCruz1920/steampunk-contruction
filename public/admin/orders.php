<?php
require_once __DIR__ . '/../../src/helpers/functions.php';
require_once __DIR__ . '/../../src/controllers/AdminController.php';
require_once __DIR__ . '/../../src/models/Orders.php';

if (!isAdmin()) {
    header('Location: /login.php');
    exit();
}

$adminController = new AdminController();
$orderModel = new Order();

// Show order details if 'view' param is set
$orderDetails = null;
$orderItems = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $orderDetails = $orderModel->getOrderById($_GET['view']);
    $orderItems = $orderModel->getOrderItems($_GET['view']);
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $adminController->updateOrderStatus($_POST['order_id'], $_POST['status']);
}

// Get filtered orders
$statusFilter = $_GET['status'] ?? null;
$orders = $adminController->getAllOrders($statusFilter);
?>

<?php require_once __DIR__ . '/../../src/views/partials/header.php'; ?>

<div class="admin-container">
    <div class="admin-header brass-panel">
        <h1 class="gears-title">📦 Order Management</h1>
        
    </div>

    <?php if ($orderDetails): ?>
    <div class="brass-panel" style="margin-bottom: 2rem;">
        <h2>Order #<?= $orderDetails['id'] ?> Details</h2>
        <p><strong>Customer:</strong> <?= htmlspecialchars($orderDetails['username']) ?></p>
        <p><strong>Date:</strong> <?= date('M j, Y', strtotime($orderDetails['created_at'])) ?></p>
        <p><strong>Amount:</strong> $<?= number_format($orderDetails['total_amount'], 2) ?></p>
        <p><strong>Status:</strong> <?= ucfirst($orderDetails['status']) ?></p>
        <h3>Items</h3>
        <ul>
            <?php foreach ($orderItems as $item): ?>
                <li><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>) - $<?= number_format($item['unit_price'], 2) ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="/admin/orders.php" class="steam-btn small">Close</a>
    </div>
    <?php endif; ?>

    <!-- Order Filters -->
    <div class="brass-panel">
        <h2>Order Filters</h2>
        <div class="filter-options">
            <a href="/admin/orders.php" class="piston-button small<?= !$statusFilter ? ' active' : '' ?>">All</a>
            <a href="/admin/orders.php?status=pending" class="piston-button small<?= $statusFilter === 'pending' ? ' active' : '' ?>">Pending</a>
            <a href="/admin/orders.php?status=completed" class="piston-button small<?= $statusFilter === 'completed' ? ' active' : '' ?>">Completed</a>
            <a href="/admin/orders.php?status=shipped" class="piston-button small<?= $statusFilter === 'shipped' ? ' active' : '' ?>">Shipped</a>
            <a href="/admin/orders.php?status=cancelled" class="piston-button small<?= $statusFilter === 'cancelled' ? ' active' : '' ?>">Cancelled</a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="brass-panel">
        <h2>Order List</h2>
        <table class="steam-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <?php $orderItems = $orderModel->getOrderItems($order['id']); ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                    <td>$<?= number_format($order['total_amount'], 2) ?></td>
                    <td>
                        <form method="POST" class="status-form">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Structure -->
<div id="receipt-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff8e1; color:#333; border-radius:8px; padding:2rem; min-width:320px; max-width:90vw; box-shadow:0 4px 32px #0008; position:relative;">
    <button id="close-receipt-modal" style="position:absolute; top:8px; right:12px; background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
    <h2>Order Receipt</h2>
    <div id="receipt-content"></div>
  </div>
</div>

<script>
document.querySelectorAll('.view-receipt-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var order = JSON.parse(this.getAttribute('data-order'));
        var html = '';
        html += '<p><strong>Order ID:</strong> #' + order.id + '</p>';
        html += '<p><strong>Customer:</strong> ' + order.username + '</p>';
        html += '<p><strong>Date:</strong> ' + order.created_at + '</p>';
        html += '<p><strong>Amount:</strong> $' + order.total_amount + '</p>';
        html += '<p><strong>Status:</strong> ' + order.status + '</p>';
        html += '<h3>Items</h3>';
        if(order.items && order.items.length > 0) {
            html += '<table style="width:100%;border-collapse:collapse;margin-bottom:1em;">';
            html += '<thead><tr><th style="text-align:left;">Name</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead><tbody>';
            order.items.forEach(function(item) {
                html += '<tr>' +
                    '<td>' + item.name + '</td>' +
                    '<td style="text-align:center;">' + item.quantity + '</td>' +
                    '<td style="text-align:right;">$' + item.unit_price + '</td>' +
                    '<td style="text-align:right;">$' + item.subtotal + '</td>' +
                '</tr>';
            });
            html += '</tbody></table>';
        } else {
            html += '<p>No items found for this order.</p>';
        }
        document.getElementById('receipt-content').innerHTML = html;
        document.getElementById('receipt-modal').style.display = 'flex';
    });
});
document.getElementById('close-receipt-modal').onclick = function() {
    document.getElementById('receipt-modal').style.display = 'none';
};
document.getElementById('receipt-modal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>

<?php require_once __DIR__ . '/../../src/views/partials/footer.php'; ?>