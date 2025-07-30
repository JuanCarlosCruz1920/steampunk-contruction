<?php
require_once(__DIR__ . '/../../src/helpers/functions.php');
require_once __DIR__ . '/../../src/controllers/AdminController.php';

if (!isAdmin()) {
    header('Location: /login.php');
    exit();
}

$adminController = new AdminController();
$customers = $adminController->getAllCustomers();
?>

<?php require_once __DIR__ . '/../../src/views/partials/header.php'; ?>

<div class="admin-container">
    <div class="admin-header brass-panel">
        <div class="admin-header-flex">
            <h1 class="gears-title">
                <span style="font-size:2.2rem;">👥</span> Customer Management
            </h1>
        </div>
    </div>

    <div class="search-bar brass-panel">
        <input type="text" id="customerSearch" placeholder="Search customers..." class="gear-input" style="width:100%; max-width:400px; margin:0 auto; display:block;">
    </div>

    <div class="admin-table-container brass-panel">
        <table class="steam-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="customerTableBody">
                <?php foreach ($customers as $customer): ?>
                <tr data-username="<?= strtolower(htmlspecialchars($customer['username'])) ?>" data-email="<?= strtolower(htmlspecialchars($customer['email'])) ?>">
                    <td><?= $customer['id'] ?></td>
                    <td><?= htmlspecialchars($customer['username']) ?></td>
                    <td><?= htmlspecialchars($customer['email']) ?></td>
                    <td><?= date('M j, Y', strtotime($customer['created_at'])) ?></td>
                    <td>
                        <a href="/admin/customers/edit.php?id=<?= $customer['id'] ?>" class="piston-button small">Edit</a>
                        <a href="#" class="piston-button small danger" onclick="confirmDelete(<?= $customer['id'] ?>); return false;">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(customerId) {
    if (confirm('Are you sure you want to delete this customer?')) {
        window.location.href = `/admin/customers/delete.php?id=${customerId}`;
    }
}

document.getElementById('customerSearch').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('#customerTableBody tr').forEach(row => {
        const username = row.getAttribute('data-username');
        const email = row.getAttribute('data-email');
        if (username.includes(search) || email.includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../../src/views/partials/footer.php'; ?>
