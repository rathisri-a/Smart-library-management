<?php
include 'db_connection.php';  // Ensure your db_connection.php includes the correct database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Search</title>
    <link rel="stylesheet" href="../css/style3.css">  <!-- Add your CSS link here -->
</head>
<body>

    <?php
    // Check if a search query is provided
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        // Get the search term from the user input
        $searchTerm = $_GET['search'];

        // Prepare the SQL query to search for books
        $query = "SELECT * FROM departmentlibrarybookdetails WHERE NameOfBook LIKE ? OR Authors LIKE ?";
        $stmt = $conn->prepare($query);
        $searchTermWithWildcards = "%" . $searchTerm . "%";
        $stmt->bind_param("ss", $searchTermWithWildcards, $searchTermWithWildcards);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Display search results in a table
            echo "<table class='table table-bordered table-hover'>";
            echo "<tr style='background-color: white;'>";
            echo "<th>id</th>";
            echo "<th>AccessNo</th>";
            echo "<th>BookNo</th>";
            echo "<th>NameOfBook</th>";
            echo "<th>Authors</th>";
            echo "<th>Publisher</th>";
            echo "<th>BeroNo</th>";
            echo "<th>ShelfNo</th>";
            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['AccessNo']) . "</td>";
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
            echo "<p>No results found for '$searchTerm'.</p>";
        }

        // Close the statement and connection
        $stmt->close();
    }
    ?>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
