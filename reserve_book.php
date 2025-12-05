<?php
include 'check_auth.php';
include 'config.php';

// Check if ISBN is provided
if (!isset($_GET['isbn'])) {
    header("Location: search.php?error=no_isbn");
    exit();
}

$isbn = $_GET['isbn'];
$username = $_SESSION['username'];

// Check if the book exists and is available
$book_sql = "SELECT b.*, c.CategoryDescription 
             FROM Books b 
             LEFT JOIN Categories c ON b.CategoryID = c.CategoryID 
             WHERE b.ISBN = ?";
$stmt = $conn->prepare($book_sql);
$stmt->bind_param("s", $isbn);
$stmt->execute();
$book_result = $stmt->get_result();

if ($book_result->num_rows === 0) {
    header("Location: search.php?error=book_not_found");
    exit();
}

$book = $book_result->fetch_assoc();

// Check if book is already reserved
if ($book['Reserved'] == 'Y') {
    header("Location: search.php?error=already_reserved");
    exit();
}

// Check if user already reserved this book (extra safety)
$existing_reservation_sql = "SELECT * FROM Reservations WHERE ISBN = ? AND Username = ?";
$stmt = $conn->prepare($existing_reservation_sql);
$stmt->bind_param("ss", $isbn, $username);
$stmt->execute();
$existing_result = $stmt->get_result();

if ($existing_result->num_rows > 0) {
    header("Location: search.php?error=already_reserved_by_you");
    exit();
}

// Reserve the book - Insert into Reservations table and update Books table
$reserve_sql = "INSERT INTO Reservations (ISBN, Username, ReservedDate) VALUES (?, ?, CURDATE())";
$stmt = $conn->prepare($reserve_sql);
$stmt->bind_param("ss", $isbn, $username);

if ($stmt->execute()) {
    // Update book status to reserved
    $update_book_sql = "UPDATE Books SET Reserved = 'Y' WHERE ISBN = ?";
    $stmt2 = $conn->prepare($update_book_sql);
    $stmt2->bind_param("s", $isbn);
    $stmt2->execute();
    $stmt2->close();
    
    header("Location: search.php?success=reserved&title=" . urlencode($book['BookTitle']));
} else {
    header("Location: search.php?error=reservation_failed");
}

$stmt->close();
$conn->close();
exit();
?>