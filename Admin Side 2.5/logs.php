<!DOCTYPE html>
<html lang="en" style="color: var(--bs-red);">

<head>
    <title>Book Information</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta charset="utf-8">
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
                    <li class="nav-item"><a class="nav-link" href="logs.php">Logs</a></li>
                    <li class="nav-item"><a class="nav-link" href="mainaddbook.php">Add Books</a>
                    <li class="nav-item"><a class="nav-link" href="addstaff.php">Add Staff</a></li>
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

    <!-- ...Your existing HTML code -->
    <div class="container">
        <h2>Update Logs</h2>
        <!-- Add a refresh button -->
        <button id="refreshBtn" class="btn btn-primary">Refresh Logs</button>
        <table class="table">
    <thead class="thead-dark">
        <tr>
            <th>Action</th>
            <th>Entity Type</th>
            <th>Entity ID</th>
            <th>Time Stamp</th>
        </tr>
    </thead>         
            </ul>
            <div class="collapse navbar-collapse" id="navcol-1"></div>
        </div>
    </nav>
    <div>
   
         
    <tbody id="logTableBody">
        <?php
        $host = 'localhost';
        $database = 'db_nt3102';
        $username = 'root';
        $password = '';

        $connection = new mysqli($host, $username, $password, $database);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $sql = "SELECT * FROM `updatelog`";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["action"] . "</td>
                        <td>" . $row["entityType"] . "</td>
                        <td>" . $row["entityID"] . "</td>
                        <td>" . $row["timestamp"] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No records found</td></tr>";
        }

        $connection->close();
        ?>
    </tbody>
</table>
    </div>
     <script>
        // JavaScript to handle the refresh button click
        document.getElementById('refreshBtn').addEventListener('click', function() {
            // Reload the page to refresh the logs (this will re-run the PHP code)
            location.reload();
        });
    </script>
</body>

</html>