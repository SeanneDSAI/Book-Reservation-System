<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Server-side validation
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }

    // Check if user exists
    $sql = "SELECT * FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['Password'])) {
            // Password is correct, prevent session fixation
            session_regenerate_id(true);
            $_SESSION['username'] = $user['Username'];
            $_SESSION['firstname'] = $user['FirstName'];
            
            // Redirect to search page
            header("Location: search.php");
            exit();
        }
    }
    
    // If we get here, credentials are invalid
    header("Location: login.php?error=invalid_credentials");
    exit();
    
    $stmt->close();
    $conn->close();
}
?>