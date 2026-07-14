<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookName = $_POST['NameOfBook'];
    $authors = $_POST['Author'];
    $bookNo = $_POST['BookNo'];
    $publisher = $_POST['Publisher'];
    $beroNo = $_POST['BeroNo'];
    $shelfNo = $_POST['ShelfNo'];
    
    $sql = "INSERT INTO departmentlibrarybookdetails (NameOfBook, Authors, BookNo, Publisher, BeroNo, ShelfNo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $bookName, $authors, $bookNo, $publisher, $beroNo, $shelfNo);
    
    if ($stmt->execute()) {
        // Redirect to success page
        header("Location: success.html");
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
    <title>Add Book</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-image: url('../images/background.jfif');
            background-size: cover;
            background-position: center;
            text-align: center;
            padding: 50px;
            position: relative;
            min-height: 100vh;
        }
        form {
            background: rgba(255, 255, 255, 0.8);
            padding: 50px;
            border-radius: 30px;
            width: 350px;
            margin: auto;
            box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.3);
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
            position: fixed;
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

<h2 style="color: red;">Add a New Book</h2>
<form method="post">
    <label>Name of Book:</label>
    <input type="text" name="NameOfBook" required><br>

    <label>Author:</label>
    <input type="text" name="Author" required><br>

    <label>Book No:</label>
    <input type="text" name="BookNo" required><br>

    <label>Publisher:</label>
    <input type="text" name="Publisher" required><br>

    <label>Bero No:</label>
    <input type="text" name="BeroNo" required><br>

    <label>Shelf No:</label>
    <input type="text" name="ShelfNo" required><br>

    <button type="submit">Add Book</button>
</form>

</body>
</html>
