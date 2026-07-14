<?php
include 'db_connection.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Return Book</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-image: url('../images/background.jfif');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            padding: 20px;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
        }

        form {
            margin-top: 20px;
            text-align: center;
        }

        input {
            padding: 10px;
            margin: 10px auto;
            width: 80%;
            max-width: 400px;
            font-size: 16px;
            font-family: 'Times New Roman', Times, serif;
            border-radius: 5px;
            border: 1px solid #ccc;
            display: block;
        }

        .submit-btn {
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Times New Roman', Times, serif;
        }

        button:hover {
            background-color: #1f6392;
        }

        .message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
            font-family: 'Times New Roman', Times, serif;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
            font-family: 'Times New Roman', Times, serif;
        }

        .back-btn {
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 999;
        }

        .back-btn a {
            text-decoration: none;
        }

        .back-btn button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Times New Roman', Times, serif;
        }

        .back-btn button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Return Book</h2>
        <form method="POST" action="">
            <input type="text" name="BookNo" placeholder="Enter Book Number" required>
            <input type="text" name="BookName" placeholder="Enter Book Name" required>
            <input type="text" name="Author" placeholder="Enter Author Name" required>
            <input type="text" name="BorrowerName" placeholder="Enter Borrower Name" required>
            <div class="submit-btn">
                <button type="submit" name="return">Return Book</button>
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return'])) {
            $bookNo = trim($_POST['BookNo']);
            $bookName = trim($_POST['BookName']);
            $author = trim($_POST['Author']);
            $borrowerName = trim($_POST['BorrowerName']);

            $query = "SELECT * FROM book_issued_details WHERE BookNo = ? AND BorrowerName = ? AND BookName = ? AND Author = ? AND ReturnDate IS NULL";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $bookNo, $borrowerName, $bookName, $author);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $today = date("Y-m-d H:i:s");
                $updateQuery = "UPDATE book_issued_details 
                                SET ReturnDate = ?, OverdueDays = DATEDIFF(?, DueDate) 
                                WHERE BookNo = ? AND BorrowerName = ? AND BookName = ? AND Author = ? AND ReturnDate IS NULL";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ssssss", $today, $today, $bookNo, $borrowerName, $bookName, $author);
                if ($updateStmt->execute()) {
                    echo "<p class='message'>Book returned successfully!</p>";
                } else {
                    echo "<p class='error'>Error while updating return date.</p>";
                }
            } else {
                echo "<p class='error'>No matching issued book found or already returned.</p>";
            }
        }
        ?>
    </div>

    <div class="back-btn">
        <a href="navbar.html">
            <button>Back</button>
        </a>
    </div>
</body>
</html>
