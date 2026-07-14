<?php
session_start();
include 'db_connection.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issued Book Details</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background-color: #f5f7fa;
            padding: 30px;
            margin: 0;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px 15px;
            text-align: center;
            font-size: 15px;
        }
        th {
            background-color: #2e86de;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f6fc;
        }
        tr:hover {
            background-color: #dff0fa;
        }
        .container {
            max-width: 1200px;
            margin: auto;
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
        }
        .back-btn button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Issued Book Details</h2>

    <?php
    // Auto-delete returned books older than 1 month
    $deleteQuery = "DELETE FROM book_issued_details 
                    WHERE ReturnDate IS NOT NULL 
                      AND ReturnDate != '' 
                      AND ReturnDate < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    $conn->query($deleteQuery);
    ?>

    <table>
        <tr>
            <th>S.No</th>
            <th>Book No</th>
            <th>Book Name</th>
            <th>Author Name</th>
            <th>Borrower Name</th>
            <th>Email ID</th>
            <th>Department</th>
            <th>Issue Date</th>
            <th>Due Date</th>
            <th>Return Date</th>
            <th>Overdue Days</th>
            <th>Fine</th>
        </tr>

        <?php
        $query = "SELECT * FROM book_issued_details ORDER BY IssueDate ASC";
        $result = $conn->query($query);
        $serial = 1;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $returnDate = $row['ReturnDate'];
                $dueDate = $row['DueDate'];
                $overdue = 0;
                $fine = 0;

                if (!empty($returnDate)) {
                    $due = new DateTime($dueDate);
                    $return = new DateTime($returnDate);
                    if ($return > $due) {
                        $overdue = $return->diff($due)->days;
                        $fine = $overdue;  // 1₹ per day fine
                    }
                } else {
                    $today = new DateTime();
                    $due = new DateTime($dueDate);
                    if ($today > $due) {
                        $overdue = $today->diff($due)->days;
                        $fine = $overdue;  // 1₹ per day fine
                    }
                }

                echo "<tr>
                        <td>{$serial}</td>
                        <td>{$row['BookNo']}</td>
                        <td>{$row['BookName']}</td>
                        <td>{$row['AuthorName']}</td>
                        <td>{$row['BorrowerName']}</td>
                        <td>{$row['EmailID']}</td>
                        <td>{$row['Department']}</td>
                        <td>{$row['IssueDate']}</td>
                        <td>{$row['DueDate']}</td>
                        <td>{$row['ReturnDate']}</td>
                        <td>{$overdue}</td>
                        <td>{$fine}</td>
                    </tr>";
                $serial++;
            }
        } else {
            echo "<tr><td colspan='12'>No issued book records found.</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</div>

<!-- Back Button -->
<div class="back-btn">
    <a href="navbar.html">
        <button>Back</button>
    </a>
</div>

</body>
</html>
