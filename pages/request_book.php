<?php
include 'db_connection.php';
$successMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookName = $_POST['book_name'];
    $studentName = $_POST['student_name'];
    $rollNo = $_POST['roll_no'];
    $email = $_POST['email'];
    $department = $_POST['department'];

    $stmt = $conn->prepare("INSERT INTO book_request (BookName, StudentName, RollNo, EmailID, Department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $bookName, $studentName, $rollNo, $email, $department);

    if ($stmt->execute()) {
        $successMsg = "✅ Book request submitted successfully!";
    } else {
        $successMsg = "❌ Failed to submit book request.";
    }
}
?>