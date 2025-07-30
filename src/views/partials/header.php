<?php
// Session should already be started in the calling page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steampunk Construction</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="steampunk-theme">
    <nav class="steam-nav">
        <a href="/" class="nav-logo">Steampunk Construction</a>
        <div class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="/admin/dashboard.php" class="nav-link">Admin Dashboard</a>
                <?php else: ?>
                    <a href="/dashboard.php" class="nav-link">Dashboard</a>
                <?php endif; ?>
                <a href="/logout.php" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="/login.php" class="nav-link">Login</a>
                <a href="/register.php" class="nav-link">Register</a>
            <?php endif; ?>
            <a href="/about.php" class="nav-link">About</a>
        </div>
    </nav>
    </div>
    
    <main class="main-content">