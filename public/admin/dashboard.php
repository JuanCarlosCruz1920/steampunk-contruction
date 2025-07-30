<?php
require_once __DIR__ . '/../../src/helpers/functions.php';
require_once __DIR__ . '/../../src/controllers/AdminController.php';

// Strict admin check
if (!isAdmin()) {
    redirect('/dashboard.php');
}

$adminController = new AdminController();
$metrics = $adminController->getDashboardMetrics();
?>

<?php require_once __DIR__ . '/../../src/views/partials/header.php'; ?>

<div class="brass-panel admin-dashboard">
    <h1 class="gears-title">⚙️ Admin Command Center</h1>
    <div class="copper-divider"></div>

    <!-- Metrics Overview -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-title">Total Sales</div>
            <div class="metric-value">$<?= number_format($metrics['total_sales'], 2) ?></div>
            <div class="metric-icon">💰</div>
        </div>
        
        <div class="metric-card">
            <div class="metric-title">Customers</div>
            <div class="metric-value"><?= $metrics['total_customers'] ?></div>
            <div class="metric-icon">👥</div>
        </div>
        
        <div class="metric-card">
            <div class="metric-title">Products</div>
            <div class="metric-value"><?= $metrics['total_products'] ?></div>
            <div class="metric-icon">🛠️</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-actions-grid">
        <a href="/admin/products.php" class="action-card">
            <div class="action-icon">📦</div>
            <h3>Manage Products</h3>
        </a>
        
        <a href="/admin/orders.php" class="action-card">
            <div class="action-icon">📋</div>
            <h3>View Orders</h3>
        </a>
        
        <a href="/admin/customers.php" class="action-card">
            <div class="action-icon">👤</div>
            <h3>Manage Users</h3>
        </a>
        
        <a href="/admin/reports.php" class="action-card">
            <div class="action-icon">📊</div>
            <h3>Sales Reports</h3>
        </a>
    </div>

    <!-- Recent Orders Table -->
    <div class="recent-orders">
        <h2><span class="gear-icon">⏱</span> Recent Orders</h2>
        <table class="steam-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($metrics['recent_orders'] as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td>$<?= number_format($order['total_amount'], 2) ?></td>
                    <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                    <td>
                        <a href="/admin/orders.php?view=<?= $order['id'] ?>" class="table-action">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../src/views/partials/footer.php'; ?>