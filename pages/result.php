<?php
include 'db_connection.php';

function normalizeText($text) {
    // Normalize text: lowercase, remove spaces and punctuation
    return strtolower(preg_replace('/[^\w]/', '', trim($text)));
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Library Book Search</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
            position: relative;
            min-height: 100vh;
        }
        .container {
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            padding-bottom: 60px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px 12px;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #2e86de;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f0f4f8;
        }
        tr:hover {
            background-color: #d6eaf8;
        }
        p {
            color: red;
            font-weight: bold;
            font-size: 18px;
        }
        .logout-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
            z-index: 1000;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<div class='container'>
    <h2>Book Search Result</h2>";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET)) {
    $bookNameInput = $_GET['NameOfBook'] ?? '';
    $authorInput = $_GET['Authors'] ?? '';
    $bookNoInput = $_GET['BookNo'] ?? '';
    $publisherInput = $_GET['Publisher'] ?? '';

    // Normalize all inputs
    $normalizedBook = normalizeText($bookNameInput);
    $normalizedAuthor = normalizeText($authorInput);
    $normalizedBookNo = normalizeText($bookNoInput);
    $normalizedPublisher = normalizeText($publisherInput);

    // Check if at least one field is entered
    if ($normalizedBook !== '' || $normalizedAuthor !== '' || $normalizedBookNo !== '' || $normalizedPublisher !== '') {
        $query = "SELECT * FROM departmentlibrarybookdetails";
        $result = $conn->query($query);

        $bookGroups = [];

        while ($row = $result->fetch_assoc()) {
            $match = false;

            // Normalize fields from DB row
            $normName = normalizeText($row['NameOfBook']);
            $normAuthor = normalizeText($row['Authors']);
            $normBookNo = normalizeText($row['BookNo']);
            $normPublisher = normalizeText($row['Publisher']);

            // Partial match logic
            if ($normalizedBook !== '' && strpos($normName, $normalizedBook) !== false) $match = true;
            if ($normalizedAuthor !== '' && strpos($normAuthor, $normalizedAuthor) !== false) $match = true;
            if ($normalizedBookNo !== '' && strpos($normBookNo, $normalizedBookNo) !== false) $match = true;
            if ($normalizedPublisher !== '' && strpos($normPublisher, $normalizedPublisher) !== false) $match = true;

            if ($match) {
                $groupKey = normalizeText($row['NameOfBook']);
                $bookGroups[$groupKey]['books'][] = $row;
                $bookGroups[$groupKey]['original_name'] = $row['NameOfBook'];
                $bookGroups[$groupKey]['Authors'] = $row['Authors'];
                $bookGroups[$groupKey]['Publisher'] = $row['Publisher'];
                $bookGroups[$groupKey]['BeroNo'] = $row['BeroNo'];
                $bookGroups[$groupKey]['ShelfNo'] = $row['ShelfNo'];
            }
        }

        if (!empty($bookGroups)) {
            echo "<table>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Bero No</th>
                    <th>Shelf No</th>
                    <th>Total Copies</th>
                    <th>Available Copies</th>
                    <th>No. of Copies Issued</th>
                </tr>";

            foreach ($bookGroups as $group) {
                $books = $group['books'];
                $totalCopies = count($books);
                $issuedCopies = 0;

                foreach ($books as $book) {
                    $bookNo = $book['BookNo'];
                    $issuedQuery = "SELECT COUNT(*) AS issuedCount FROM book_issued_details WHERE BookNo=? AND (ReturnDate IS NULL OR ReturnDate='')";
                    $stmt = $conn->prepare($issuedQuery);
                    $stmt->bind_param("s", $bookNo);
                    $stmt->execute();
                    $resultIssued = $stmt->get_result();
                    $rowIssued = $resultIssued->fetch_assoc();
                    $issuedCopies += (int)$rowIssued['issuedCount'];
                }

                $availableCopies = $totalCopies - $issuedCopies;

                echo "<tr>
                    <td>{$group['original_name']}</td>
                    <td>{$group['Authors']}</td>
                    <td>{$group['Publisher']}</td>
                    <td>{$group['BeroNo']}</td>
                    <td>{$group['ShelfNo']}</td>
                    <td>{$totalCopies}</td>
                    <td>{$availableCopies}</td>
                    <td>{$issuedCopies}</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No matching books found.</p>";
        }
    } else {
        echo "<p>No search keyword provided in URL.</p>";
    }
} else {
    echo "<p>No search input detected.</p>";
}

echo "</div>
<a href='logout.php'><button class='logout-btn'>Logout</button></a>
</body>
</html>";

$conn->close();
?>
