<?php
session_start();
include 'db_connection.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookNo = $_POST['BookNo'];
    $bookName = $_POST['BookName'];
    $authorName = $_POST['AuthorName'];
    $borrower = $_POST['BorrowerName'];
    $email = $_POST['EmailID'];
    $dept = $_POST['Department'];
    $issueDate = $_POST['IssueDate'];

    // Auto calculate due date: 1 month from issue date
    $dueDate = date('Y-m-d', strtotime('+1 month', strtotime($issueDate)));

    $sql = "INSERT INTO book_issued_details 
            (BookNo, BookName, AuthorName, BorrowerName, EmailID, Department, IssueDate, DueDate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $bookNo, $bookName, $authorName, $borrower, $email, $dept, $issueDate, $dueDate);

    if ($stmt->execute()) {
        $message = "<p class='success'>✅ Book issued successfully!</p>";
    } else {
        $message = "<p class='error'>❌ Error issuing book: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background-image: url('background.jfif');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            padding: 30px;
            margin: 0;
        }

        h2 {
            text-align: center;
            color: rgb(240, 11, 11);
        }

        form {
            max-width: 600px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-family: "Times New Roman", Times, serif;
        }

        button {
            background-color: #2e86de;
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-family: "Times New Roman", Times, serif;
        }

        button:hover {
            background-color: #1b4f72;
        }

        .submit-btn {
            text-align: center;
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
            background-color: rgb(25, 0, 248);
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-family: "Times New Roman", Times, serif;
        }

        .back-btn button:hover {
            background-color: rgb(0, 0, 255);
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin: 20px auto;
            text-align: center;
            max-width: 600px;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin: 20px auto;
            text-align: center;
            max-width: 600px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <h2>Issue Book</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" action="">
        <label>Book No:</label>
        <input type="text" name="BookNo" required>

        <label>Book Name:</label>
        <input type="text" name="BookName" required>

        <label>Author Name:</label>
        <input type="text" name="AuthorName" required>

        <label>Borrower Name:</label>
        <input type="text" name="BorrowerName" required>

        <label>Email ID:</label>
        <input type="email" name="EmailID" required>

        <label>Department:</label>
        <input type="text" name="Department" required>

        <label>Issue Date:</label>
        <input type="date" name="IssueDate" id="issueDate" required>

        <label>Due Date:</label>
        <input type="date" name="DueDate" id="dueDate" required readonly>

        <div class="submit-btn">
            <button type="submit">Issue Book</button>
        </div>
    </form>

    <div class="back-btn">
        <a href="navbar.html">
            <button>Back</button>
        </a>
    </div>

    <!-- JavaScript to auto-fill due date -->
    <script>
        document.getElementById('issueDate').addEventListener('change', function() {
            const issueDate = new Date(this.value);
            if (!isNaN(issueDate.getTime())) {
                const dueDate = new Date(issueDate);
                dueDate.setMonth(dueDate.getMonth() + 1);

                // Adjust for overflow (e.g., Jan 31 to Feb 28/29)
                if (dueDate.getDate() !== issueDate.getDate()) {
                    dueDate.setDate(0); // last day of previous month
                }

                const formattedDueDate = dueDate.toISOString().split('T')[0];
                document.getElementById('dueDate').value = formattedDueDate;
            }
        });
    </script>

</body>
</html>
