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

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


// Your database connection and logAction function definition here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_button'])) {
        $student_id = $_POST['student_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $user_password = $_POST['user_password'];
        $user_type = $_POST['user_type'];

        if ($user_type === 'student') {
            // Check if the student exists in the tb_studentinfo table
            $check_query = "SELECT * FROM tb_studentinfo WHERE studid = ? AND firstname = ? AND lastname = ?";
            $stmt = $connection->prepare($check_query);

            if ($stmt === false) {
                die("Prepare failed: " . $connection->error);
            }

            $stmt->bind_param("iss", $student_id, $first_name, $last_name);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Check if the student user already exists in the users table
                $check_user_query = "SELECT * FROM users WHERE `SR-Code` = ?";
                $stmt_user = $connection->prepare($check_user_query);

                if (!$stmt_user) {
                    echo "<script>alert('Error preparing statement: " . $connection->error . "')</script>";
                } else {
                    $stmt_user->bind_param("i", $student_id);
                    $stmt_user->execute();
                    $result_user = $stmt_user->get_result();

                    if ($result_user->num_rows > 0) {
                        echo "<script>alert('Student user already exists')</script>";
                    } else {
                        // Insert into users table for students
                        $insert_query = "INSERT INTO users (`SR-Code`, UserS_password) VALUES (?, ?)";
                        $stmt_insert = $connection->prepare($insert_query);

                        if (!$stmt_insert) {
                            echo "<script>alert('Error preparing insert statement: " . $connection->error . "')</script>";
                        } else {
                            $stmt_insert->bind_param("is", $student_id, $user_password);

                            if ($stmt_insert->execute()) {
                                // Log the action into the 'updatelog' table
                                logAction('Add', 'Student', $student_id);
                                echo "<script>alert('New student user added successfully')</script>";
                            } else {
                                echo "<script>alert('Error: Unable to add student user. Error: " . $connection->error . "')</script>";
                            }
                            $stmt_insert->close();
                        }
                    }
                    $stmt_user->close();
                }
            } else {
                echo "<script>alert('Invalid StudentID, first name, or last name')</script>";
            }
            $stmt->close();
        } elseif ($user_type === 'faculty') {
            // Check if the faculty exists in the tbemployee table
            $check_query = "SELECT * FROM tbemployee WHERE empid = ? AND firstname = ? AND lastname = ?";
            $stmt = $connection->prepare($check_query);

            if ($stmt === false) {
                die("Prepare failed: " . $connection->error);
            }

            $stmt->bind_param("iss", $student_id, $first_name, $last_name);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Check if the faculty user already exists in the usere table
                $check_user_query = "SELECT * FROM usere WHERE empid = ?";
                $stmt_user = $connection->prepare($check_user_query);

                if (!$stmt_user) {
                    echo "<script>alert('Error preparing statement: " . $connection->error . "')</script>";
                } else {
                    $stmt_user->bind_param("i", $student_id);
                    $stmt_user->execute();
                    $result_user = $stmt_user->get_result();

                    if ($result_user->num_rows > 0) {
                        echo "<script>alert('Faculty user already exists')</script>";
                    } else {
                        // Insert into usere table for faculty
                        $insert_query = "INSERT INTO usere (empid, UserE_password) VALUES (?, ?)";
                        $stmt_insert = $connection->prepare($insert_query);

                        if (!$stmt_insert) {
                            echo "<script>alert('Error preparing insert statement: " . $connection->error . "')</script>";
                        } else {
                            $stmt_insert->bind_param("is", $student_id, $user_password);

                            if ($stmt_insert->execute()) {
                                // Log the action into the 'updatelog' table
                                logAction('Add', 'Faculty', $student_id);
                                echo "<script>alert('New faculty user added successfully')</script>";
                            } else {
                                echo "<script>alert('Error: Unable to add faculty user. Error: " . $connection->error . "')</script>";
                            }
                            $stmt_insert->close();
                        }
                    }
                    $stmt_user->close();
                }
            } else {
                echo "<script>alert('Invalid Employee ID, first name, or last name')</script>";
            }
            $stmt->close();
        }
        } elseif (isset($_POST['delete_button'])) {
            $student_id = $_POST['student_id'];
            $user_type = $_POST['user_type'];
    
            // Validate and sanitize inputs
            $student_id = filter_var($student_id, FILTER_SANITIZE_NUMBER_INT);
            $user_type = filter_var($user_type, FILTER_SANITIZE_STRING);
    
            if ($user_type === 'student') {
                $delete_query = "DELETE FROM users WHERE `SR-Code` = ?";
                $stmt_delete = $connection->prepare($delete_query);
    
                if ($stmt_delete === false) {
                    die("Prepare failed: " . $connection->error);
                }
    
                $stmt_delete->bind_param("i", $student_id);
                if ($stmt_delete->execute()) {
                    // Log the action into the 'updatelog' table
                    logAction('Delete', 'Student', $student_id);
                    echo "<script>alert('Student user deleted successfully')</script>";
                } else {
                    echo "<script>alert('Error: Unable to delete student user. Error: " . $connection->error . "')</script>";
                }
                $stmt_delete->close();
            } elseif ($user_type === 'faculty') {
                $delete_query = "DELETE FROM usere WHERE empid = ?";
                $stmt_delete = $connection->prepare($delete_query);
    
                if ($stmt_delete === false) {
                    die("Prepare failed: " . $connection->error);
                }
    
                $stmt_delete->bind_param("i", $student_id);
                if ($stmt_delete->execute()) {
                    // Log the action into the 'updatelog' table
                    logAction('Delete', 'Faculty', $student_id);
                    echo "<script>alert('Faculty user deleted successfully')</script>";
                } else {
                    echo "<script>alert('Error: Unable to delete faculty user. Error: " . $connection->error . "')</script>";
                }
                $stmt_delete->close();
            }
        }
    }
    
    $connection->close();
?>

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
<!-- ... Your existing HTML code ... -->
<div class="container" style="color: var(--bs-black);">
<form method="post" action="">
    <div class="col-md-6">
        <small style="display: block; margin-bottom: 5px; margin-left: 30px;">User First Name</small>
        <input type="text" name="first_name" placeholder="First Name" style="margin-left: 30px; width: calc(100% - 60px);">
    </div>

    <div class="col-md-6">
        <small style="display: block; margin-bottom: 5px; margin-left: 32px; border-bottom-color: rgb(0,0,0);">User Last Name</small>
        <input type="text" id="lastName" name="last_name" placeholder="Last Name" style="margin-left: 32px; width: calc(100% - 64px);">
    </div>
    <div class="col-md-6">
            <small style="display: block; margin-bottom: 5px; margin-left: 32px; border-bottom-color: rgb(0,0,0);">User ID</small>
            <input type="text" name="student_id" placeholder="Student ID" style="margin-left: 32px; width: calc(100% - 64px);">
    </div>

    <div class="col-md-6">
        <small style="display: block; margin-bottom: 5px; margin-left: 32px; border-bottom-color: rgb(0,0,0);">User Password</small>
        <input type="text" id="userpassword" name="user_password" placeholder="Input Password" style="margin-left: 32px; width: calc(100% - 64px);">
    </div>
   
            
        </div>

       <div class="row">
       <div class="col-md-6" style="display: flex; align-items: center;">
       <small style="display: block; margin-bottom: 5px; margin-left: 32px; border-bottom-color: rgb(0,0,0); color: black;">User Type</small>
                <div style="display: flex; gap: 10px; margin-left: 32px; color: black;">
                    <label>
                        <input type="radio" name="user_type" value="faculty">Faculty
                    </label>
                    <label>
                        <input type="radio" name="user_type" value="student">Student
                    </label>
                </div>
            </div>
</div>
    </div>
                <button class="btn btn-primary" type="submit" name="add_button" value="Add User" style="position: relative; overflow: visible; display: inline-flex; transform: translate(324px);">Add</button>
                <button class="btn btn-primary" type="submit" name="delete_button" style="position: relative; overflow: visible; display: inline-flex; transform: translate(324px);">Delete</button>
            </div>
        </div>
    </form>
</div>

<div>
        <div class="sidebar">
            <div class="dismiss"><i class="fa fa-caret-left"></i></div>
            <div class="BatState-U"><a class="navbar-brand" href="loginADMIN.php">Menu</a></div>
            <nav class="navbar navbar-dark navbar-expand">
            <div class="container-fluid">
            <ul class="navbar-nav flex-column me-auto">
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
</body>
</html>