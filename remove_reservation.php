<?php
include 'check_auth.php';
include 'config.php';

// Check if ISBN is provided
if (!isset($_GET['isbn'])) {
    header("Location: my_reservations.php?error=no_isbn");
    exit();
}

$isbn = $_GET['isbn'];
$username = $_SESSION['username'];

// Verify the reservation belongs to the current user
$verify_sql = "SELECT * FROM Reservations WHERE ISBN = ? AND Username = ?";
$stmt = $conn->prepare($verify_sql);
$stmt->bind_param("ss", $isbn, $username);
$stmt->execute();
$verify_result = $stmt->get_result();

if ($verify_result->num_rows === 0) {
    header("Location: my_reservations.php?error=not_your_reservation");
    exit();
}

// Remove the reservation
$delete_sql = "DELETE FROM Reservations WHERE ISBN = ? AND Username = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("ss", $isbn, $username);

if ($stmt->execute()) {
    // Update book status to available
    $update_book_sql = "UPDATE Books SET Reserved = 'N' WHERE ISBN = ?";
    $stmt2 = $conn->prepare($update_book_sql);
    $stmt2->bind_param("s", $isbn);
    $stmt2->execute();
    $stmt2->close();
    
    header("Location: my_reservations.php?success=removed");
} else {
    header("Location: my_reservations.php?error=remove_failed");
}

$stmt->close();
$conn->close();
exit();
?>