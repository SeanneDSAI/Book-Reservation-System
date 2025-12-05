<?php include 'check_auth.php'; ?>
<?php include 'header.php'; ?>

<!-- Success and Error Messages -->
<?php
if (isset($_GET['success'])) {
    echo '<div style="color: green; margin-bottom: 1rem; padding: 0.5rem; background: #d4edda; border: 1px solid #c3e6cb;">';
    if ($_GET['success'] === 'removed') {
        echo 'Reservation successfully removed.';
    }
    echo '</div>';
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
    echo '<div style="color: red; margin-bottom: 1rem; padding: 0.5rem; background: #f8d7da; border: 1px solid #f5c6cb;">';
    if ($error === 'not_your_reservation') {
        echo 'You cannot remove this reservation.';
    } elseif ($error === 'remove_failed') {
        echo 'Failed to remove reservation. Please try again.';
    }
    echo '</div>';
}
?>

<h2>My Reserved Books</h2>

<?php
include 'config.php';
$username = $_SESSION['username'];

// Get user's reservations
$sql = "SELECT r.ISBN, r.ReservedDate, b.BookTitle, b.Author, c.CategoryDescription 
        FROM Reservations r 
        JOIN Books b ON r.ISBN = b.ISBN 
        JOIN Categories c ON b.CategoryID = c.CategoryID 
        WHERE r.Username = ? 
        ORDER BY r.ReservedDate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1' style='width: 100%; border-collapse: collapse; margin-top: 1rem;'>";
    echo "<tr style='background: #f2f2f2;'>
            <th style='padding: 0.5rem;'>Title</th>
            <th style='padding: 0.5rem;'>Author</th>
            <th style='padding: 0.5rem;'>Category</th>
            <th style='padding: 0.5rem;'>Reserved Date</th>
            <th style='padding: 0.5rem;'>Action</th>
          </tr>";
    
    while ($reservation = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 0.5rem;'>{$reservation['BookTitle']}</td>";
        echo "<td style='padding: 0.5rem;'>{$reservation['Author']}</td>";
        echo "<td style='padding: 0.5rem;'>{$reservation['CategoryDescription']}</td>";
        echo "<td style='padding: 0.5rem;'>{$reservation['ReservedDate']}</td>";
        echo "<td style='padding: 0.5rem;'>";
        echo "<a href='remove_reservation.php?isbn={$reservation['ISBN']}' style='color: red;' onclick='return confirm(\"Are you sure you want to remove this reservation?\")'>Remove</a>";
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<p style='margin-top: 1rem;'><strong>{$result->num_rows}</strong> book(s) reserved.</p>";
    
} else {
    echo "<p>You have no reserved books.</p>";
    echo "<p><a href='search.php'>Search for books to reserve</a></p>";
}

$stmt->close();
$conn->close();
?>

<?php include 'footer.php'; ?>