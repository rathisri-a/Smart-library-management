<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['admin_email']);
    $password = trim($_POST['admin_password']);

    if (empty($email) || empty($password)) {
        echo "<div style='text-align: center;'>
                <img src='oops1.jpg' alt='Oops! Invalid Input' style='width: 300px;'>
                <p style='color: red; font-size: 18px;'>Please enter both email and password.</p>
              </div>";
        exit();
    }

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM admin WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Redirect to admin dashboard
        header("Location: navbar.html");
        exit();
    } else {
        echo "<div style='text-align: center;'>
                <img src='oops1.jpg' alt='Oops! Invalid Credentials' style='width: 300px;'>
                <p style='color: red; font-size: 18px;</p>
              </div>";
    }

    $stmt->close();
}

$conn->close();
?>
