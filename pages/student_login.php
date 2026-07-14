<?php
// Include the database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_email = trim($_POST['student_email']); // Trim whitespace

    if (empty($student_email)) {
        echo "<div style='text-align: center;'>
                <img src='oops.png' alt='Oops! Invalid Email' style='width: 300px;'>
                <p style='color: red; font-size: 18px;'>Please enter an email address.</p>
              </div>";
        exit();
    }

    // Search for similar emails in the database
    $sql = "SELECT email FROM users WHERE email LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_email = "%" . $student_email . "%";
    $stmt->bind_param("s", $search_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Redirect to search page
        session_start();
        $_SESSION['search_email'] = $student_email;
        header("Location: ../pages/search.php");
        exit();
    } else {
        echo "<div style='text-align: center;'>
                <img src='oops.jpg' alt='Oops! Invalid Email' style='width: 300px;'>
                <p style='color: red; font-size: 18px;</p>
              </div>";
    }

    $stmt->close();
}

$conn->close();
?>
