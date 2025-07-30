<?php
require_once __DIR__ . './../src/config/database.php';
require_once __DIR__ . './../src/views/partials/header.php';
require_once __DIR__ . '/../src/helpers/functions.php';
?>

<div class="brass-panel">
    <h1 class="gears-title">Welcome to Steampunk Construction</h1>
    <div class="copper-divider"></div>
    <p class="typewriter-text">Premium materials for your industrial revolution projects</p>
    
    <div class="steam-buttons">
        <a href="login.php" class="piston-button">Login</a>
        <a href="register.php" class="piston-button">Register</a>
    </div>
</div>

<?php require_once __DIR__ . '/../src/views/partials/footer.php'; ?>