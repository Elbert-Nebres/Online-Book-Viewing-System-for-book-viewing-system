<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Book Information</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Navbar-Right-Links-Dark-icons.css">
    <link rel="stylesheet" href="assets/css/Ultimate-Sidebar-Menu-BS5.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    
 
</head>

<?php
session_start();

// Check if the username is set in the session
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    // If the username is not set, redirect the user back to the login page
    header("Location: main.php");
    exit();
}
?>

<body class="text-start" style="font-size: 30px; border-bottom-color: rgb(0,0,0);">
    <a class="btn btn-primary btn-customized open-menu" role="button"><i class="fa fa-navicon"></i>&nbsp;Menu</a>
    <header></header>
    <nav class="navbar navbar-light navbar-expand-md bg-danger">
        <div class="container-fluid">
            <img src="assets/img/Batangas_State_Logo.png" width="95" height="70">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="#" style="line-height: 27px;">BatState-U<br>Library</a></li>
                <li class="nav-item"><a class="nav-link" href="#"></a></li>
                <li class="nav-item"><a class="nav-link" href="#"></a></li>
            </ul>
            <div class="collapse navbar-collapse" id="navcol-1"></div>
        </div>
    </nav>

    <div class="sidebar">
    <div class="dismiss"><i class="fa fa-caret-left"></i></div>
    <div class="BatState-U"><a class="navbar-brand" href="loginADMIN.php" >MENU</a></div>
    <nav class="navbar navbar-dark navbar-expand" >
    <div class="container-fluid">
            <ul class="navbar-nav flex-column me-auto" >
            <li class="nav-items"><a class="nav-link" href="#hit"><?php echo $username; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="main.php">Search Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="mainaddbook.php">Add Books</a>
                    <li class="nav-item"><a class="nav-link" href="adduser.php">Add User</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Logout</a>
        </ul>
        </div>
    </nav>
</div>
<div class="overlay"></div>
</div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Ultimate-Sidebar-Menu-BS5.js"></script>

    <div class="container">
        <main>
        <form method="POST" action="">
        <div class="search">
  <body>
    <h1>Book Search</h1>
    <form method="POST" action="">
      <input type="text" id="searchInput" name="input" placeholder="Search">
      <button type="submit" name="submit">Search</button>
      <select name="genre" id="bookDropdown">
    <option value="" selected>None</option>
    <option value="Fiction">Fiction</option>
    <option value="Science Fiction">Science Fiction</option>
    <option value="Mystery/Thriller">Mystery/Thriller</option>
    <option value="Fantasy">Fantasy</option>
    <option value="Non-Fiction">Non-Fiction</option>
    <option value="Biography">Biography</option>
    <option value="Science">Science</option>
    <option value="Research Papers">Research Papers</option>
    <option value="Codes">Codes</option>
    <option value="Comedy">Codes</option>
</select>
    </form>
    <script>
      var material = document.getElementById("materials");
      var input = document.getElementById("searchInput");
      var dropdown = document.getElementById("bookDropdown");
      var options = dropdown.getElementsByTagName("option");

      input.addEventListener("input", function() {
        var searchValue = input.value.toLowerCase();

        for (var i = 0; i < options.length; i++) {
          var option = options[i];
          var bookCategory = option.value.toLowerCase();

          if (bookCategory.includes(searchValue)) {
            option.style.display = "block";
          } else {
            option.style.display = "none";
          }
        }
      });
    </script>
  </body>
</div>
<div class="results">
  <table class="table">

  <?php
 
 $host = 'localhost'; 
 $database = 'db_nt3102'; 
 $username = 'root'; 
 $password = ""; 
 
 
 $connection = new mysqli($host, $username, $password, $database);
 
 if ($connection->connect_error) {
     die("Connection failed: " . $connection->connect_error);
 }
 


 if (isset($_POST['submit']) && isset($_POST['input'])) {
    $search = $_POST['input'];
    $genre = isset($_POST['genre']) ? $_POST['genre'] : "";

    $sql = "SELECT book.*, author.authorFn, author.authorLn
            FROM book
            JOIN author ON book.authorID = author.authorID
            WHERE (book.bookID LIKE '%$search%'
                OR bookTitle LIKE '%$search%'
                OR ISBN LIKE '%$search%'
                OR CONCAT(author.authorFn, ' ', author.authorLn) LIKE '%$search%'
                OR CONCAT(author.authorLn, ' ', author.authorFn) LIKE '%$search%')
                " . ($genre !== "" ? "AND genre = '$genre'" : "");

   $result = mysqli_query($connection, $sql);
   if ($result) {
     if (mysqli_num_rows($result) > 0) {
       echo "<div class='search-results'>";
      while( $row = $result->fetch_assoc()){
       echo "<div class='result-item'>";
       echo "<h3>
       <a href = 'searchData.php?data=".$row['bookID']."'>".$row['bookTitle']."</a>
       </h3>";
       echo "<p class='author'>by {$row['authorFn']} {$row['authorLn']}</p>";
       echo "<a class='isbn'>ISBN: {$row['ISBN']}</a>";
       echo "</div>";
       echo "</div>";
      
      }

     
 
     } else {
       echo "<p class='no-results'>No books found matching your search.</p>";
     }
     
   }
 }
 $connection->close();
 ?>