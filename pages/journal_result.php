<?php
// DB config
$host = "localhost";
$user = "root";
$password = "";
$database = "details";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from form
$journal_title = isset($_GET['journal_title']) ? trim($_GET['journal_title']) : '';
$journal_publisher = isset($_GET['journal_publisher']) ? trim($_GET['journal_publisher']) : '';
$subject_area = isset($_GET['subject_area']) ? trim($_GET['subject_area']) : '';

// Build dynamic query
$sql = "SELECT journal_title, publisher, subject_area, journal_type, file_path FROM ece_journals WHERE 1=1";
$params = [];
$types = "";

if (!empty($journal_title)) {
    $sql .= " AND journal_title LIKE ?";
    $params[] = "%" . $journal_title . "%";
    $types .= "s";
}
if (!empty($journal_publisher)) {
    $sql .= " AND publisher LIKE ?";
    $params[] = "%" . $journal_publisher . "%";
    $types .= "s";
}
if (!empty($subject_area)) {
    $sql .= " AND subject_area LIKE ?";
    $params[] = "%" . $subject_area . "%";
    $types .= "s";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Journal Search Results</title>
  <style>
    body {
      font-family: 'Times New Roman', serif;
      background: #f5f5f5;
      padding: 20px;
    }
    h2 {
      color: #2c3e50;
      text-align: center;
    }
    table {
      width: 95%;
      margin: 20px auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #3498db;
      color: white;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .no-results {
      text-align: center;
      color: red;
      font-weight: bold;
    }
    .action-buttons {
      display: flex;
      gap: 10px;
      justify-content: center;
    }
    .action-buttons a {
      padding: 8px 16px;
      font-size: 14px;
      font-weight: bold;
      color: white;
      background: linear-gradient(135deg, #2980b9, #3498db);
      border-radius: 5px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }
    .action-buttons a:hover {
      background: linear-gradient(135deg, #1f6391, #2a89c6);
      transform: scale(1.05);
    }
    /* Logout button */
    .logout-btn {
      position: fixed;
      bottom: 20px;
      right: 30px;
      z-index: 999;
    }
    .logout-btn a {
      padding: 10px 20px;
      font-size: 15px;
      font-weight: bold;
      color: white;
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      border-radius: 5px;
      text-decoration: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
    }
    .logout-btn a:hover {
      background: linear-gradient(135deg, #c0392b, #a93226);
      transform: scale(1.05);
    }
  </style>
</head>
<body>

<h2>Journal Search Result</h2>

<?php
if (!empty($journal_title) || !empty($journal_publisher) || !empty($subject_area)) {
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "<p class='no-results'>Query preparation failed: " . $conn->error . "</p>";
    } else {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                      <th>Journal Title</th>
                      <th>Publisher</th>
                      <th>Subject Area</th>
                      <th>Journal Type</th>
                      <th>Actions</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                $filePath = htmlspecialchars($row['file_path']);
                echo "<tr>
                        <td>" . htmlspecialchars($row['journal_title']) . "</td>
                        <td>" . htmlspecialchars($row['publisher']) . "</td>
                        <td>" . htmlspecialchars($row['subject_area']) . "</td>
                        <td>" . htmlspecialchars($row['journal_type']) . "</td>
                        <td class='action-buttons'>
                          <a href='$filePath' target='_blank'>Preview</a>
                          <a href='$filePath' download>Download</a>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-results'>No journals found matching your criteria.</p>";
        }

        $stmt->close();
    }
} else {
    echo "<p class='no-results'>Please enter at least one search field.</p>";
}

$conn->close();
?>

<!-- Logout Button -->
<div class="logout-btn">
  <a href="logout.php">Logout</a>
</div>

</body>
</html>
