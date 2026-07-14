<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookNo = $_POST['BookNo'];
    $bookName = $_POST['BookName'];
    $authorName = $_POST['AuthorName'];
    $borrower = $_POST['BorrowerName'];
    $email = $_POST['EmailID'];
    $dept = $_POST['Department'];
    $issueDate = $_POST['IssueDate'];
    $dueDate = $_POST['DueDate'];

    // Calculate fine (₹1 per day after due date)
    $today = date("Y-m-d");
    $fine = 0;

    if (strtotime($today) > strtotime($dueDate)) {
        $diff = strtotime($today) - strtotime($dueDate);
        $daysLate = floor($diff / (60 * 60 * 24));
        $fine = $daysLate;  // ₹1 per day
    }

    $sql = "INSERT INTO book_issued_details 
            (BookNo, BookName, AuthorName, BorrowerName, EmailID, Department, IssueDate, DueDate, Fine)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $bookNo, $bookName, $authorName, $borrower, $email, $dept, $issueDate, $dueDate, $fine);

    if ($stmt->execute()) {
        echo "✅ Book issued successfully!";
        if ($fine > 0) {
            echo "<br>⚠️ Fine already applicable: ₹" . $fine;
        }
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
