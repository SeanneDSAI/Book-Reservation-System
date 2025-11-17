<?php include 'header.php'; ?>

<h2>User Registration</h2>

<?php
// Display error messages if any
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    echo '<div style="color: red; margin-bottom: 1rem;">';
    if ($error === 'empty_fields') {
        echo 'Please fill in all required fields.';
    } elseif ($error === 'invalid_mobile') {
        echo 'Mobile number must be 10 digits and numeric.';
    } elseif ($error === 'password_length') {
        echo 'Password must be at least 6 characters long.';
    } elseif ($error === 'password_mismatch') {
        echo 'Passwords do not match.';
    } elseif ($error === 'username_exists') {
        echo 'Username already exists. Please choose another.';
    }
    echo '</div>';
}

// Display success message
if (isset($_GET['success'])) {
    echo '<div style="color: green; margin-bottom: 1rem;">Registration successful! You can now <a href="login.php">login</a>.</div>';
}
?>

<form action="process_registration.php" method="POST">
    <div style="margin-bottom: 1rem;">
        <label>Username: *</label><br>
        <input type="text" name="username" required style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Password: * (min 6 characters)</label><br>
        <input type="password" name="password" required style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Confirm Password: *</label><br>
        <input type="password" name="confirm_password" required style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>First Name: *</label><br>
        <input type="text" name="firstname" required style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Surname: *</label><br>
        <input type="text" name="surname" required style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Address Line 1: *</label><br>
        <input type="text" name="address1" required style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Address Line 2:</label><br>
        <input type="text" name="address2" style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>City: *</label><br>
        <input type="text" name="city" required style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Telephone:</label><br>
        <input type="text" name="telephone" style="padding: 0.5rem; width: 300px;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Mobile: * (10 digits)</label><br>
        <input type="text" name="mobile" required style="padding: 0.5rem; width: 300px;">
    </div>

    <button type="submit" style="padding: 0.5rem 2rem; background: #2c3e50; color: white; border: none; cursor: pointer;">
        Register
    </button>
</form>

<p>Already have an account? <a href="login.php">Login here</a>.</p>

<?php include 'footer.php'; ?>