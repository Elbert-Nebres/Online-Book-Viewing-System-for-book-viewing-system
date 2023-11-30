<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Book Information</title>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Navbar-Right-Links-Dark-icons.css">
    <link rel="stylesheet" href="assets/css/Ultimate-Sidebar-Menu-BS5.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

 
</head>
<style>
    .nav-items .nav-link {
        font-size: 18px;
    }
</style>
<?php
session_start();

// Check if the username is set in the session
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    // If the username is not set, redirect the user back to the login page
    header("Location: loginADMIN.php");
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

    <div>
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
</head>
<body>
    <div class="container my-5">
        <h2>Book Information</h2>
        <a class="btn btn-primary" href="./addBook.php" role="button">Add New Book</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>BookID</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Description</th>
                    <th>Genre</th>
                    <th>PublishDate</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $host = 'localhost';
                    $database = 'db_nt3102';
                    $username = 'root';
                    $password = '';

                    $conn = new mysqli($host, $username, $password, $database);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT book.*, CONCAT(author.authorFn, ' ', author.authorLn) AS authorName, publisher.publisherName 
                            FROM book
                            LEFT JOIN author ON book.authorID = author.authorID
                            LEFT JOIN publisher ON book.publisherID = publisher.publisherID";
                    $result = $conn->query($sql);

                    if (!$result) {
                        die("Invalid query" . $conn->error);
                    }

                    while ($row = $result->fetch_assoc()) {
                    echo "
                        <tr>
                            <td>$row[bookID]</td>
                            <td>$row[authorName]</td>
                            <td>$row[publisherName]</td>
                            <td>$row[bookTitle]</td>
                            <td>$row[ISBN]</td>
                            <td>$row[description]</td>
                            <td>$row[genre]</td>
                            <td>$row[publishDate]</td>
                            <td>
                                <a class='btn btn-primary btn-sm' href='./edit.php?bookID=" . $row['bookID'] . "'>Edit</a>
                                <a class='btn btn-danger btn-sm' href='./del.php?bookID=" . $row['bookID'] . "'>Delete</a>
                            </td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>           
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>