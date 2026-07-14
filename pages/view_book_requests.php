
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Book Requests - Admin</title>
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Times New Roman', Times, serif;
            color: #333;
        }

        .admin-container {
            width: 90%;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #2c3e50;
        }

        .back-btn {
            margin-bottom: 20px;
            display: inline-block;
            padding: 8px 15px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #1a252f;
        }

        .btn-group {
            margin-bottom: 20px;
            text-align: right;
        }

        .btn-group button {
            padding: 8px 15px;
            margin-left: 10px;
            border: none;
            border-radius: 5px;
            font-family: 'Times New Roman', Times, serif;
            cursor: pointer;
        }

        .print-btn {
            background-color: #3498db;
            color: white;
        }

        .excel-btn {
            background-color: #27ae60;
            color: white;
        }

        .print-btn:hover {
            background-color: #2980b9;
        }

        .excel-btn:hover {
            background-color: #219150;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #999;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        p {
            text-align: center;
            font-size: 18px;
            margin-top: 30px;
        }
    </style>

    <script>
        function printTable() {
            window.print();
        }

        function downloadExcel() {
            var data = document.getElementById('requestTable').outerHTML;
            var blob = new Blob([data], { type: 'application/vnd.ms-excel' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'Requested_Books.xls';
            a.click();
        }
    </script>
</head>
<body>
    <div class="admin-container">
        <a href="navbar.html" class="back-btn">← Back to Dashboard</a>
        <h2>📚 Requested Book Details</h2>

        <div class="btn-group">
            <button class="print-btn" onclick="printTable()">🖨 Print</button>
            <button class="excel-btn" onclick="downloadExcel()">⬇ Download Excel</button>
        </div>

        <?php
        include 'db_connection.php';

        $sql = "SELECT * FROM book_request ORDER BY RequestDate ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table id='requestTable'>
                    <tr>
                        <th>ID</th>
                        <th>Book Name</th>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Email ID</th>
                        <th>Department</th>
                        <th>Request Date</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['ID'] . "</td>
                        <td>" . htmlspecialchars($row['BookName']) . "</td>
                        <td>" . htmlspecialchars($row['StudentName']) . "</td>
                        <td>" . htmlspecialchars($row['RollNo']) . "</td>
                        <td>" . htmlspecialchars($row['EmailID']) . "</td>
                        <td>" . htmlspecialchars($row['Department']) . "</td>
                        <td>" . $row['RequestDate'] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No book requests found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
