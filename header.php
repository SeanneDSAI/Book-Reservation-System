<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Book Reservation System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header { background: #2c3e50; color: white; padding: 1rem; }
        .nav { display: flex; justify-content: space-between; align-items: center; }
        .nav-links { display: flex; gap: 1rem; }
        .nav a { color: white; text-decoration: none; }
        .nav a:hover { text-decoration: underline; }
        .container { padding: 2rem; }
        .user-info { color: #ecf0f1; }
    </style>
</head>
<body>
    <div class="header">
        <div class="nav">
            <div class="nav-links">
                <a href="index.php">Home</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="search.php">Search Books</a>
                    <a href="my_reservations.php">My Reservations</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="user-info">
                    Welcome, <?php echo $_SESSION['firstname']; ?>!
                    <a href="logout.php" style="margin-left: 1rem;">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">