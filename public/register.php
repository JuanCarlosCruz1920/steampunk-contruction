<?php
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/helpers/functions.php';

$auth = new AuthController();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->register($_POST);
    if ($result['success']) {
        header('Location: login.php');
        exit;
    } else {
        $errors = $result['errors'];
    }
}

require_once __DIR__ . '/../src/views/partials/header.php';
?>

<div class="brass-panel">
    <h1 class="gears-title">Join Our Guild</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="steam-alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="steam-form">
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required class="gear-input">
            </div>
            
            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" id="middle_name" name="middle_name" class="gear-input">
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required class="gear-input">
            </div>
        </div>
        
        <div class="form-group">
            <label for="birthday">Birthday</label>
            <input type="date" id="birthday" name="birthday" required class="gear-input">
        </div>
        
        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" required class="gear-input"></textarea>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required class="gear-input">
        </div>
        
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" class="gear-input">
        </div>
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required class="gear-input">
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required class="gear-input">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required class="gear-input">
        </div>
        
        <button type="submit" class="piston-button">Register</button>
    </form>
</div>

<?php require_once __DIR__ . '/../src/views/partials/footer.php'; ?>