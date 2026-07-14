<?php
include 'db_connection.php';  // Ensure your db_connection.php includes the correct database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books List</title>
    <link rel="stylesheet" href="css/style3.css">  <!-- Add your CSS link here -->
    <style>
        /* Add CSS for the search bar */
        .search-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .search-container input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        /* Style the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 60px;  /* Ensure there's space below the fixed search bar */
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        /* Add styling for the back button */
        .back-button {
            position: fixed;
            bottom: 20px;
            right: 100px; /* Adjust right position to avoid overlapping with search bar */
            padding: 10px 15px;
            font-size: 14px;
            background-color: rgb(42, 72, 238);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: rgb(23, 63, 240);
        }
    </style>
</head>
<body>
    <!-- Back button -->
    <a href="javascript:history.back()" class="back-button">Back</a>

    <!-- Search bar at the top right -->
    <div class="search-container">
        <form action="pages/book_search.php" method="GET">
            <input type="text" name="search" placeholder="Search books...">
        </form>
    </div>

    <h2>List Of Books</h2>
    
    <?php
    // Fetch books without id and AccessNo
    $query = "SELECT BookNo, NameOfBook, Authors, Publisher, BeroNo, ShelfNo FROM departmentlibrarybookdetails";
    $res = $conn->query($query);

    if ($res->num_rows > 0) {
        echo "<table class='table table-bordered table-hover'>";
        echo "<tr style='background-color: white;'>";
        echo "<th>S.No</th>";  // Serial Number Column
        echo "<th>Book No</th>";
        echo "<th>Name of Book</th>";
        echo "<th>Authors</th>";
        echo "<th>Publisher</th>";
        echo "<th>Bero No</th>";
        echo "<th>Shelf No</th>";
        echo "</tr>";

        $serialNo = 1; // Initialize serial number
        while ($row = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $serialNo++ . "</td>"; // Auto-incrementing serial number
            echo "<td>" . htmlspecialchars($row['BookNo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['NameOfBook']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Authors']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Publisher']) . "</td>";
            echo "<td>" . htmlspecialchars($row['BeroNo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ShelfNo']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No books found.</p>";
    }

    // Close the connection
    $conn->close();
    ?>
</body>
</html>
