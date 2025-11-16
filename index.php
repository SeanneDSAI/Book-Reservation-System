<?php include 'header.php'; ?>

<h1>Welcome to the Library Book Reservation System</h1>

<?php if (isset($_SESSION['username'])): ?>
    <p>Hello <strong><?php echo $_SESSION['firstname']; ?></strong>! What would you like to do?</p>
    <ul>
        <li><a href="search.php">Search for Books</a></li>
        <li><a href="my_reservations.php">View My Reservations</a></li>
    </ul>
<?php else: ?>
    <p>Please <a href="login.php">login</a> or <a href="register.php">register</a> to search and reserve books.</p>
<?php endif; ?>

<?php include 'footer.php'; ?>