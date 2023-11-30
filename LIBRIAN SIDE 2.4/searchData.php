

<!DOCTYPE html>
<html>
<head>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Navbar-Right-Links-Dark-icons.css">
    <link rel="stylesheet" href="assets/css/Ultimate-Sidebar-Menu-BS5.css">
 
</head>

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

    <div>
        <div class="sidebar">
            <div class="dismiss"><i class="fa fa-caret-left"></i></div>
            <div class="BatState-U"><a class="navbar-brand" href="loginADMIN.php">Menu</a></div>
            <nav class="navbar navbar-dark navbar-expand">
            <div class="container-fluid">
                    <ul class="navbar-nav flex-column me-auto">
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
</body>
</html>

<?php
 
 $host = 'localhost'; 
 $database = 'db_nt3102'; 
 $username = 'root'; 
 $password = ""; 
 
 
 $connection = new mysqli($host, $username, $password, $database);
 
 if ($connection->connect_error) {
     die("Connection failed: " . $connection->connect_error);
 }


  $data = $_GET['data'];
 

  $sql = "SELECT book.*, author.authorFn, author.authorLn, publisher.publisherName FROM book 
  JOIN author ON book.authorID = author.authorID 
  JOIN publisher ON book.publisherID = publisher.publisherID 
  WHERE bookID = $data";
  $result = mysqli_query($connection, $sql);
  if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='book'>";
            echo "<h2><strong>Book Title:</strong> {$row['bookTitle']}</h2>";
            echo "<p><strong>Author:</strong> {$row['authorFn']} {$row['authorLn']}</p>";
            echo "<p><strong>Publisher:</strong> {$row['publisherName']}</p>";
            echo "<p><strong>Genre:</strong> {$row['genre']}</p>";
            echo "<p><strong>ISBN:</strong> {$row['ISBN']}</p>";
            echo "<p><strong>Quantity:</strong> {$row['quantity']}</p>";
            echo "<p><strong>Publish Date:</strong> {$row['publishDate']}</p>";
            echo "<p><strong>Description:</strong> {$row['description']}</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No books found.</p>";
    }
}
  $connection->close();
  ?>


