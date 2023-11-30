<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya+SC&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Navbar-Right-Links-Dark-icons.css">
    <link rel="stylesheet" href="assets/css/Ultimate-Sidebar-Menu-BS5.css">
 
</head>

<style>
    .nav-items .nav-link {
        font-size: 15px;
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
            <div class="dismiss">
            <i class="fa fa-caret-left"></i></div>
            <a class="nav-link" href="#hit">Menu</a></li>
            <nav class="navbar navbar-dark navbar-expand">
            <div class="container-fluid">
                    <ul class="navbar-nav flex-column me-auto">
                    <li class="nav-items"><a class="nav-link" href="#hit"><?php echo $username;?></a></li>
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
<!-- ... Your existing HTML code ... -->
<!-- ... Existing HTML code ... -->
<form method="post" action="">
        <div class="container" style="color: var(--bs-black);">
            <div class="row">
                <div class="col-md-6">
                    <small style="display: block; margin-bottom: 5px; margin-left: 30px;">Employee First Name</small>
                    <input type="text" id="firstName" name="staff_first_name" placeholder="First Name" style="margin-left: 30px; width: calc(100% - 60px);">
                </div>

                <div class="col-md-6">
                    <small style="display: block; margin-bottom: 5px; margin-left: 32px; border-bottom-color: rgb(0,0,0);">Employee Last Name</small>
                    <input type="text" id="lastName" name="staff_last_name" placeholder="Last Name" style="margin-left: 32px; width: calc(100% - 64px);">
                </div>

                <div class="col-md-6">
                    <small style="display: block; margin-bottom: 5px; margin-left: 30px;">Employee Password</small>
                    <input type="text" id="staffpassword" name="staff_password" placeholder="Input Password" style="margin-left: 30px; width: calc(100% - 60px);">
                </div>

                <div class="col-md-6">
                    <small style="display: block; margin-bottom: 5px; margin-left: 32px; border-bottom-color: rgb(0,0,0);">Employee ID</small>
                    <input type="text" id="staffID" name="staff_id" placeholder="Input Number" style="margin-left: 32px; width: calc(100% - 64px);">
                </div>

                <div class="col-md-6" style="display: flex; align-items: center;">
                    <small style="display: block; margin-bottom: 5px; margin-left: 32px;">Employee Type</small>
                    <div style="display: flex; gap: 10px; margin-left: 32px;">
                        <label>
                            <input type="radio" name="staff_user_type" value="administrator">Admin
                            <input type="radio" name="staff_user_type" value="Librarian">Librarian
                        </label>
                        <label>
                    <button class="btn btn-primary" type="submit" name="add_button">Add</button>
                    <button class="btn btn-primary" type="submit" name="delete_button">delete</button>
                </div>
            </div>
        </div>
    </form>
<!-- ... Other HTML content ... -->

</body>
</html>

<?php
$host = 'localhost';
$database = 'db_nt3102';
$username = 'root';
$password = '';

$connection = new mysqli($host, $username, $password, $database);

function logAction($action, $entityType, $entityID) {
    global $connection;
    $logSql = "INSERT INTO updatelog (action, entityType, entityID, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt_log = $connection->prepare($logSql);
    
    if ($stmt_log) {
        $stmt_log->bind_param("ssi", $action, $entityType, $entityID);
        
        if ($stmt_log->execute()) {
            echo "<script>alert('Action logged successfully')</script>";
        } else {
            echo "<script>alert('Error logging action: " . $connection->error . "')</script>";
        }

        $stmt_log->close();
    } else {
        echo "<script>alert('Error preparing log statement: " . $connection->error . "')</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_button'])) {
        // Retrieve form data for adding
        $staff_first_name = $_POST['staff_first_name'];
        $staff_last_name = $_POST['staff_last_name'];
        $staff_password = $_POST['staff_password'];
        $staff_id = $_POST['staff_id'];
        $staff_user_type = $_POST['staff_user_type'];

        $checkExistingQuery = "SELECT COUNT(*) as count FROM librarian WHERE empid = ? UNION 
                                SELECT COUNT(*) as count FROM administrator WHERE empid = ?";

        $stmt = $connection->prepare($checkExistingQuery);
        $stmt->bind_param("ii", $staff_id, $staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalExisting = 0;


        while ($row = $result->fetch_assoc()) {
            $totalExisting += $row['count'];
        }

        if ($staff_user_type === 'administrator') {
            if ($totalExisting > 0) {
                echo "<script>alert('This Employee is already added as either librian or administrator')</script>";
            } else {
                $insertQuery = "INSERT INTO administrator (empid, administratorPassword) 
                                SELECT empid, ? FROM tbemployee WHERE firstname = ? AND lastname = ?";

                $stmt = $connection->prepare($insertQuery);
                $stmt->bind_param("sss", $staff_password, $staff_first_name, $staff_last_name);
                if ($stmt->execute()) {
                    logAction('Add', ($staff_user_type === 'administrator') ? 'administrator' : 'librarian', $staff_id);
                    echo "<script>alert('Administrator added successfully')</script>";
                } else {
                    echo "<script>alert('Failed to add administrator')</script>";
                }
                $stmt->close();
            }

        } elseif ($staff_user_type === 'Librarian') {
            if ($totalExisting > 0) {
                echo "<script>alert('This user is already added as either Librarian or administrator')</script>";
            } else {
                $insertQuery = "INSERT INTO librarian (empid, librarianPassword) 
                                SELECT empid, ? FROM tbemployee WHERE firstname = ? AND lastname = ?";

                $stmt = $connection->prepare($insertQuery);
                $stmt->bind_param("sss", $staff_password, $staff_first_name, $staff_last_name);

                if ($stmt->execute()) {
                    logAction('Add', ($staff_user_type === 'Librarian') ? 'Librarian' : 'librarian', $staff_id);
                    echo "<script>alert('Librian added successfully')</script>";
                } else {
                    echo "<script>alert('Failed to add Librarian')</script>";
                }
                $stmt->close();
            }
        }
        
    } elseif (isset($_POST['delete_button'])) {
        // Retrieve form data for deleting
        $staff_id = $_POST['staff_id'];
        $staff_user_type = $_POST['staff_user_type'];
    
        $checkExistingQuery = "SELECT COUNT(*) as count FROM librarian WHERE empid = ?";
        $checkExistingQuery = "SELECT COUNT(*) as count FROM administrator WHERE empid = ?";
        $stmtCheck = $connection->prepare($checkExistingQuery);
        $stmtCheck->bind_param("i", $staff_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $rowCheck = $resultCheck->fetch_assoc();
    
        // Check if the staff ID exists before deletion
        if ($rowCheck['count'] > 0) {
            if ($staff_user_type === 'administrator') {
                $deleteQuery = "DELETE FROM administrator WHERE empid = ?";
                $stmt = $connection->prepare($deleteQuery);
                $stmt->bind_param("i", $staff_id);
    
                if ($stmt->execute()) {
                    logAction('Delete', 'administrator', $staff_id);
                    echo "<script>alert('Administrator deleted successfully')</script>";
                } else {
                    echo "<script>alert('Failed to delete administrator')</script>";
                }
                $stmt->close();
        
            } elseif ($staff_user_type === 'Librarian') {
                $deleteQuery = "DELETE FROM librarian WHERE empid = ?";
                $stmt = $connection->prepare($deleteQuery);
                $stmt->bind_param("i", $staff_id);
    
                if ($stmt->execute()) {
                    logAction('Delete', 'Librarian', $staff_id);
                    echo "<script>alert('Librarian deleted successfully')</script>";
                } else {
                    echo "<script>alert('Failed to delete staff')</script>";
                }
                $stmt->close();
            }
        } else {
            echo "<script>alert('Employee ID does not exist. No action performed.')</script>";
        }
    }
}
?>           