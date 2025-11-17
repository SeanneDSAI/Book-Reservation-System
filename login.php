<?php include 'header.php'; ?>

<h2>User Login</h2>

<?php
// Display error messages if any
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    echo '<div style="color: red; margin-bottom: 1rem;">';
    if ($error === 'invalid_credentials') {
        echo 'Invalid username or password.';
    } elseif ($error === 'empty_fields') {
        echo 'Please fill in both username and password.';
    }
    echo '</div>';
}

// Display logout message
if (isset($_GET['logout'])) {
    echo '<div style="color: green; margin-bottom: 1rem;">You have been successfully logged out.</div>';
}

// Display registration success message
if (isset($_GET['registered'])) {
    echo '<div style="color: green; margin-bottom: 1rem;">Registration successful! Please login with your credentials.</div>';
}
?>

<form action="process_login.php" method="POST">
    <div style="margin-bottom: 1rem;">
        <label>Username: *</label><br>
        <input type="text" name="username" required style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Password: *</label><br>
        <input type="password" name="password" required style="padding: 0.5rem; width: 300px;">
    </div>

    <button type="submit" style="padding: 0.5rem 2rem; background: #2c3e50; color: white; border: none; cursor: pointer;">
        Login
    </button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a>.</p>

<?php include 'footer.php'; ?>