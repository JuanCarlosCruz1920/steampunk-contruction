<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/helpers/functions.php';

$auth = new AuthController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->login($_POST['username'], $_POST['password']);
    if ($result['success']) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = $result['error'];
    }
}

require_once dirname(__DIR__) . '/src/views/partials/header.php';
?>

<div class="brass-panel login-container">
    <h1 class="gears-title">Login to Your Workshop</h1>
    <?php if ($error): ?>
        <div class="steam-alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" class="steam-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required class="gear-input">
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required class="gear-input">
        </div>
        
        <button type="submit" class="piston-button">Login</button>
    </form>
    
    <div class="steam-links">
        <a href="forgot-password.php" class="copper-link">Forgot Password?</a>
        <a href="register.php" class="copper-link">Create New Account</a>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/src/views/partials/footer.php'; ?>