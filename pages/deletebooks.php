<?php
include 'db_connection.php';

// Delete a book from the database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $nameOfBook = $_POST['nameOfBook'];
    $authors = $_POST['author'];
    $bookNo = $_POST['bookNo'];

    $sql = "DELETE FROM departmentlibrarybookdetails WHERE NameOfBook = ? AND Authors = ? AND BookNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nameOfBook, $authors, $bookNo);

    if ($stmt->execute()) {
        // Redirect to success page
        header("Location: delete.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Books</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-image: url('../images/background.jfif');
            background-size: cover;
            background-position: center;
            text-align: center;
            padding: 50px;
        }
        form {
            background: rgba(255, 255, 255, 0.8);
            padding: 50px;
            border-radius: 30px;
            width: 350px;
            margin: auto;
            box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .back-button {
            position: absolute;
            top: 10px;
            right: 10px;
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
    <a href="navbar.html" class="back-button">Back</a>

    <h2 style="color: red;">Delete a Book</h2>
    <form method="post">
        <label>Name of Book:</label>
        <input type="text" name="nameOfBook" required><br>

        <label>Author:</label>
        <input type="text" name="author" required><br>
        
        <label>Book No:</label>
        <input type="text" name="bookNo" required><br>
        
        <button type="submit" name="delete">Delete Book</button>
    </form>
</body>
</html>
