<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $firstname = $_POST['firstname'];
    $surname = $_POST['surname'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $telephone = $_POST['telephone'];
    $mobile = $_POST['mobile'];

    // Server-side validation
    $errors = [];

    // Check empty required fields
    if (empty($username) || empty($password) || empty($confirm_password) || 
        empty($firstname) || empty($surname) || empty($address1) || 
        empty($city) || empty($mobile)) {
        header("Location: register.php?error=empty_fields");
        exit();
    }

    // Check mobile number (numeric and 10 characters)
    if (!is_numeric($mobile) || strlen($mobile) != 10) {
        header("Location: register.php?error=invalid_mobile");
        exit();
    }

    // Check password length
    if (strlen($password) < 6) {
        header("Location: register.php?error=password_length");
        exit();
    }

    // Check password confirmation
    if ($password !== $confirm_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }

    // Check if username already exists
    $check_username_sql = "SELECT * FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($check_username_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: register.php?error=username_exists");
        exit();
    }

    // If all validation passes, insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $insert_sql = "INSERT INTO Users (Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssssss", $username, $hashed_password, $firstname, $surname, $address1, $address2, $city, $telephone, $mobile);
    
    if ($stmt->execute()) {
        header("Location: register.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>