<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Library Portal</title>
  <link rel="stylesheet" href="../css/style2.css" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 0;
    }

    .library-container {
      max-width: 900px;
      margin: 0 auto;
      padding: 30px;
      text-align: center;
    }

    h1 {
      margin-bottom: 30px;
    }

    .tab-buttons {
      text-align: center;
      margin-bottom: 20px;
    }

    .tab-buttons button {
      padding: 10px 25px;
      margin: 0 8px;
      border: none;
      background-color: rgb(52, 219, 200);
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .tab-buttons button.active,
    .tab-buttons button:hover {
      background-color: #2c3e50;
    }

    .tab-section {
      display: none;
    }

    .tab-section.active {
      display: block;
    }

    .search-container {
      max-width: 600px;
      margin: 0 auto;
      padding: 25px;
      border: 1px solid #ccc;
      border-radius: 15px;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      text-align: left;
    }

    .search-group {
      margin-bottom: 20px;
    }

    .search-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .search-group input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
    }

    .buttons-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 25px;
    }

    .buttons-container button {
      background-color: rgb(52, 219, 200);
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 25px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .buttons-container button:hover {
      background-color: #2c3e50;
    }

    .search-container input[type="submit"] {
      background-color: rgb(52, 219, 200);
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 25px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    .search-container input[type="submit"]:hover {
      background-color: #2c3e50;
    }

    /* Back Button Styling */
    .back-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color:rgb(52, 219, 200);
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 30px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      z-index: 1000;
    }

    .back-button:hover {
      background-color: #2c3e50;
    }
  </style>

  <script>
    function voiceSearch() {
      const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
      recognition.lang = 'en-US';
      recognition.start();
      const bookField = document.getElementById('book');
      bookField.focus();
      recognition.onresult = function(event) {
        bookField.value = event.results[0][0].transcript;
        document.getElementById('searchForm').submit();
      };
      recognition.onerror = function(event) {
        alert("Voice search error: " + event.error);
      };
    }

    function validateSearch(event) {
      let author = document.getElementById('author').value.trim();
      let book = document.getElementById('book').value.trim();
      let bookNo = document.getElementById('bookNo').value.trim();
      let publisher = document.getElementById('publisher').value.trim();
      if (author === "" && book === "" && bookNo === "" && publisher === "") {
        alert("Please enter at least one search criteria.");
        event.preventDefault();
      }
    }

    function showTab(tabId) {
      const sections = ['searchSection', 'requestSection', 'journalSection'];
      const buttons = ['btnSearch', 'btnRequest', 'btnJournal'];
      sections.forEach(section => document.getElementById(section).classList.remove("active"));
      buttons.forEach(btn => document.getElementById(btn).classList.remove("active"));
      document.getElementById(tabId).classList.add("active");
      if (tabId === 'searchSection') document.getElementById('btnSearch').classList.add('active');
      if (tabId === 'requestSection') document.getElementById('btnRequest').classList.add('active');
      if (tabId === 'journalSection') document.getElementById('btnJournal').classList.add('active');
    }

    function goBack() {
      window.history.back();
    }
  </script>
</head>
<body>
  <div class="library-container">
    <h1>📚 Library Portal</h1>

    <div class="tab-buttons">
      <button id="btnSearch" class="active" onclick="showTab('searchSection')">🔍 Book Search</button>
      <button id="btnRequest" onclick="showTab('requestSection')">📚 Request Book</button>
      <button id="btnJournal" onclick="showTab('journalSection')">📖 Journal Search</button>
    </div>

    <!-- SEARCH SECTION -->
    <div id="searchSection" class="tab-section active">
      <form action="../pages/result.php" method="GET" id="searchForm" class="search-container" onsubmit="validateSearch(event)">
        <div class="search-group">
          <label for="book">Search by Book:</label>
          <input type="text" id="book" name="NameOfBook" placeholder="Enter book title" list="bookList" />
          <datalist id="bookList">
            <?php
            include 'db_connection.php';
            $result = $conn->query("SELECT DISTINCT NameOfBook FROM departmentlibrarybookdetails");
            while ($row = $result->fetch_assoc()) {
              echo "<option value='" . htmlspecialchars($row['NameOfBook']) . "'>";
            }
            ?>
          </datalist>
        </div>

        <div class="search-group">
          <label for="author">Search by Author:</label>
          <input type="text" id="author" name="Authors" placeholder="Enter author name" list="authorList" />
          <datalist id="authorList">
            <?php
            $result = $conn->query("SELECT DISTINCT Authors FROM departmentlibrarybookdetails");
            while ($row = $result->fetch_assoc()) {
              echo "<option value='" . htmlspecialchars($row['Authors']) . "'>";
            }
            ?>
          </datalist>
        </div>

        <div class="search-group">
          <label for="publisher">Search by Publisher:</label>
          <input type="text" id="publisher" name="Publisher" placeholder="Enter publisher" list="publisherList" />
          <datalist id="publisherList">
            <?php
            $result = $conn->query("SELECT DISTINCT Publisher FROM departmentlibrarybookdetails");
            while ($row = $result->fetch_assoc()) {
              echo "<option value='" . htmlspecialchars($row['Publisher']) . "'>";
            }
            ?>
          </datalist>
        </div>

        <div class="search-group">
          <label for="bookNo">Search by Book No:</label>
          <input type="text" id="bookNo" name="BookNo" placeholder="Enter book number" />
        </div>

        <div class="buttons-container">
          <button type="button" onclick="voiceSearch()">🎙 Voice Search</button>
          <button type="submit">Search</button>
        </div>
      </form>
    </div>

    <!-- REQUEST SECTION -->
    <div id="requestSection" class="tab-section">
      <form method="POST" action="request_book.php" class="search-container">
        <div class="search-group">
          <label for="request_book">Book Name:</label>
          <input type="text" id="request_book" name="book_name" placeholder="Enter Book Name" required />
        </div>
        <div class="search-group">
          <label for="student_name">Student Name:</label>
          <input type="text" id="student_name" name="student_name" placeholder="Enter Name" required />
        </div>
        <div class="search-group">
          <label for="roll_no">Roll No:</label>
          <input type="text" id="roll_no" name="roll_no" placeholder="Enter Roll no" required />
        </div>
        <div class="search-group">
          <label for="email">Email ID:</label>
          <input type="email" id="email" name="email" placeholder="Enter Email ID" required />
        </div>
        <div class="search-group">
          <label for="department">Department:</label>
          <input type="text" id="department" name="department" placeholder="Enter Department" required />
        </div>
        <div class="buttons-container">
          <button type="submit">Submit Book Request</button>
        </div>
      </form>
    </div>

    <!-- JOURNAL SECTION -->
    <div id="journalSection" class="tab-section">
      <form action="../pages/journal_result.php" method="get" class="search-container">
        <div class="search-group">
          <label for="journal_title">Journal Title:</label>
          <input type="text" name="journal_title" placeholder="Enter Journal Name " id="journal_title" />
        </div>
        <div class="search-group">
          <label for="journal_publisher">Publisher:</label>
          <input type="text" name="journal_publisher" placeholder="Enter Publisher" id="journal_publisher" />
        </div>
        <div class="search-group">
          <label for="subject_area">Subject Area:</label>
          <input type="text" name="subject_area" placeholder="Enter Subject area" id="subject_area" />
        </div>
        <div class="buttons-container">
          <button type="submit">Search Journal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Back Button -->
  <button class="back-button" onclick="goBack()">⬅ Back</button>
</body>
</html>
