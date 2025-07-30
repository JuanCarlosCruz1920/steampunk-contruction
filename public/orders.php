<?php
require_once __DIR__ . '/../src/helpers/functions.php';
require_once __DIR__ . '/../src/models/Orders.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('/login.php');
}
// Redirect admin users to the admin dashboard
if (isAdmin()) {
    redirect('/admin/dashboard.php');
}

$orderModel = new Order();
$userId = $_SESSION['user_id'];

// Handle order cancellation
$cancelMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $orderId = intval($_POST['cancel_order_id']);
    // Fetch the order and check ownership and status
    $order = $orderModel->getOrderById($orderId);
    if ($order && $order['user_id'] == $userId && $order['status'] === 'pending') {
        $orderModel->updateOrderStatus($orderId, 'cancelled');
        $cancelMessage = 'Order #' . htmlspecialchars($orderId) . ' has been cancelled.';
        // Refresh orders list
        $userOrders = $orderModel->getUserOrders($userId);
    } else {
        $cancelMessage = 'Unable to cancel this order.';
    }
}
$userOrders = $orderModel->getUserOrders($userId);

function formatOrderStatus($status) {
    switch ($status) {
        case 'completed': return '<span class="order-status completed">Completed</span>';
        case 'pending': return '<span class="order-status pending">Pending</span>';
        case 'cancelled': return '<span class="order-status cancelled">Cancelled</span>';
        default: return htmlspecialchars($status);
    }
}
?>

<?php require_once __DIR__ . '/../src/views/partials/header.php'; ?>

<div class="brass-panel">
    <h1 class="gears-title">📦 Your Orders</h1>
    <div class="copper-divider"></div>
    <?php if (!empty($cancelMessage)): ?>
        <div class="steam-alert"><?= $cancelMessage ?></div>
    <?php endif; ?>
    <?php if (empty($userOrders)): ?>
        <div class="steam-alert">You have not placed any orders yet.</div>
        <a href="/products.php" class="piston-button">Browse Products</a>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($userOrders as $order): ?>
                <div class="order-summary">
                    <div><strong>Order #<?= htmlspecialchars($order['id']) ?></strong> | <?= htmlspecialchars($order['created_at']) ?></div>
                    <div>Status: <?= formatOrderStatus($order['status']) ?></div>
                    <div>Total: <?= formatPrice($order['total_amount']) ?></div>
                    <button class="piston-button small details-btn" data-order-id="<?= $order['id'] ?>">Details</button>
                    <?php if ($order['status'] === 'pending'): ?>
                        <form method="POST" action="" class="cancel-order-form" style="display:inline;">
                            <input type="hidden" name="cancel_order_id" value="<?= $order['id'] ?>">
                            <button type="button" class="piston-button small cancel-btn" data-order-id="<?= $order['id'] ?>">Cancel</button>
                        </form>
                    <?php endif; ?>
                </div>
                <!-- Modal for order receipt -->
                <div id="order-modal-<?= $order['id'] ?>" class="order-modal">
                  <div class="order-modal-content">
                    <span class="close-order-modal" data-order-id="<?= $order['id'] ?>">&times;</span>
                    <h2>Order Receipt #<?= htmlspecialchars($order['id']) ?></h2>
                    <div><strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?></div>
                    <div><strong>Status:</strong> <?= formatOrderStatus($order['status']) ?></div>
                    <div><strong>Total:</strong> <?= formatPrice($order['total_amount']) ?></div>
                    <hr>
                    <h3>Items</h3>
                    <?php 
                    $items = $orderModel->getOrderItems($order['id']);
                    if (empty($items)) {
                        echo '<div class="steam-alert">No items found for this order.</div>';
                    } else {
                        echo '<table class="steam-table" style="width:100%; margin-top:10px;">';
                        echo '<thead><tr><th>Product</th><th>Description</th><th>Unit Price</th><th>Quantity</th><th>Subtotal</th></tr></thead><tbody>';
                        foreach ($items as $item) {
                            $subtotal = $item['unit_price'] * $item['quantity'];
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                            echo '<td>' . htmlspecialchars(substr($item['description'], 0, 50)) . (strlen($item['description']) > 50 ? '...' : '') . '</td>';
                            echo '<td>' . formatPrice($item['unit_price']) . '</td>';
                            echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                            echo '<td>' . formatPrice($subtotal) . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    }
                    ?>
                  </div>
                </div>
            <?php endforeach; ?>
        </div>
        <script>
        // Simple toggle for order items
        document.querySelectorAll('.order-summary button').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemsDiv = this.parentElement.nextElementSibling;
                itemsDiv.classList.toggle('hidden');
            });
        });
        // Modal logic for order details
        document.querySelectorAll('.details-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                const modal = document.getElementById('order-modal-' + orderId);
                if (modal) modal.style.display = 'flex';
            });
        });
        document.querySelectorAll('.close-order-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                const modal = document.getElementById('order-modal-' + orderId);
                if (modal) modal.style.display = 'none';
            });
        });
        document.querySelectorAll('.order-modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
        // Cancel order confirmation
        document.querySelectorAll('.cancel-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const orderId = this.dataset.orderId;
                if (confirm('Are you sure you want to cancel order #' + orderId + '?')) {
                    this.closest('form').submit();
                }
            });
        });
        </script>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../src/views/partials/footer.php'; ?>
<style>
.order-summary { background: var(--parchment); border: 1.5px solid var(--copper); padding: 12px; margin-bottom: 5px; border-radius: 6px; color: var(--dark-wood); }
.order-items { background: #fffbe6; border: 1px solid #e0c97f; padding: 10px; border-radius: 6px; }
.order-items.hidden { display: none; }
.order-status.completed { color: green; font-weight: bold; }
.order-status.pending { color: orange; font-weight: bold; }
.order-status.cancelled { color: red; font-weight: bold; }
.order-modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(43, 31, 29, 0.7); /* dark-wood overlay */
  justify-content: center;
  align-items: center;
}
.order-modal[style*="display: flex"] {
  display: flex !important;
}
.order-modal-content {
  background: linear-gradient(135deg, var(--parchment) 80%, #f7e7c1 100%);
  border: 3px solid var(--brass);
  border-radius: 10px;
  padding: 30px 24px 24px 24px;
  min-width: 320px;
  max-width: 95vw;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 8px 32px rgba(43,31,29,0.25);
  position: relative;
  color: var(--dark-wood);
}
.close-order-modal {
  position: absolute;
  top: 10px;
  right: 18px;
  font-size: 2rem;
  color: var(--brass);
  cursor: pointer;
  font-weight: bold;
  transition: color 0.2s;
}
.close-order-modal:hover {
  color: var(--copper);
}
.order-modal-content h2, .order-modal-content h3 {
  color: var(--dark-wood);
  font-weight: bold;
  text-shadow: 1px 1px 0 var(--brass), 0 0 2px #fffbe6;
}
.order-modal-content div, .order-modal-content strong {
  color: var(--dark-wood);
}
.order-modal-content table.steam-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
  color: var(--dark-wood);
}
.order-modal-content table.steam-table thead tr {
  background: linear-gradient(90deg, var(--brass) 0%, #f7e7c1 100%);
}
.order-modal-content table.steam-table th {
  color: var(--dark-wood);
  font-weight: bold;
  padding: 8px 6px;
  border-bottom: 2px solid var(--copper);
  background: transparent;
  text-shadow: 1px 1px 0 var(--parchment);
}
.order-modal-content table.steam-table td {
  color: var(--dark-wood);
  padding: 8px 6px;
  border-bottom: 1px solid var(--brass);
  background: var(--parchment);
}
.order-modal-content table.steam-table tr:last-child td {
  border-bottom: none;
}
.order-modal-content hr {
  border: none;
  border-top: 1.5px solid var(--brass);
  margin: 16px 0;
}
</style> 