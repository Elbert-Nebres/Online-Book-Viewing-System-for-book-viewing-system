<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            background-image: url("bsu_gate.jpg");
            background-size: cover;
            background-repeat: no-repeat;
        }

        .login-form {
            width: 400px;
            height: 400px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 5px solid red;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 100px;
            height: 100px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        input[type=text],
        input[type=password] {
            width: 80%;
            padding: 10px;
            margin: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid gray;
        }

        label {
            display: block;
            text-align: center;
        }

        input[type=submit] {
            width: 80%;
            padding: 10px;
            margin: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            background-color: red;
            color: white;
            border: none;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="login-form">
        <img src="Batangas_State_Logo.png" alt="Logo" class="logo">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="Username">Username</label>
            <input type="text" id="Username" name="Username" placeholder="Enter your username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <input type="submit" value="Login">
        </form>
    </div>

    <?php
// Starting session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST["Username"];
    $pass = $_POST["password"];

    $host = 'localhost';
    $database = 'db_nt3102';
    $username = 'root';
    $password = "";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query for student info
    $sql1 = "SELECT tb_studentinfo.lastname, tb_studentinfo.firstname, users.`SR-Code`, users.UserS_password
        FROM tb_studentinfo
        INNER JOIN users ON tb_studentinfo.studid = users.`SR-Code`
        WHERE CONCAT(tb_studentinfo.lastname, ' ', tb_studentinfo.firstname) = '" . $conn->real_escape_string($uname) . "'";

    $result1 = $conn->query($sql1);

    if ($result1->num_rows > 0) {
        $row1 = $result1->fetch_assoc();
    
        // Assuming $pass is the password entered by the user
        if ($pass === $row1["UserS_password"]) {
            // Start the session and set the username
            session_start();
            $_SESSION["username"] = $row1["firstname"] . " " . $row1["lastname"];
            header("Location: main.php");
            exit();
        } else {
        // No matching student found, proceed to employee check
        // Query for employee info
        $sql2 = "SELECT tbemployee.*, usere.empid, usere.UserE_password
            FROM tbemployee
            INNER JOIN usere ON tbemployee.empid = usere.empid
            WHERE CONCAT(tbemployee.firstname, ' ', tbemployee.lastname) = '$uname'";

        $result2 = $conn->query($sql2);

        if (!$result2) {
            die("Query failed: " . $conn->error);
        }

        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            // Assuming $pass is the password entered by the user
            if ($pass === $row2["UserE_password"]) {
                header("Location: index.php");
                exit();
            } else {
                // Invalid password
                echo '<script>
                    Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "Invalid username or password!",
                    });
                  </script>';
            }
        } else {
            // No matching user found
            echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "User not found!",
                });
              </script>';
        }
    }
    }
    $conn->close();
}
?>

</body>

</html>
  <!-- Naka comment muna pero eto yung pede nateng gamitin dun sa menu
  session_start(); // Start the session to store user information
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = $_POST['Username'];
      $password = $_POST['password'];
  
      // Connect to the database (replace these credentials with your actual database details)
      $host = 'localhost';
      $database = 'your_database_name';
      $db_username = 'your_username';
      $db_password = 'your_password';
  
      $connection = new mysqli($host, $db_username, $db_password, $database);
      if ($connection->connect_error) {
          die("Connection failed: " . $connection->connect_error);
      }
  
      // Query to fetch user details based on the entered username (you may need to handle passwords securely)
      $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $connection->query($query);
  
      if ($result->num_rows > 0) {
          // Valid user, fetch user details
          $user = $result->fetch_assoc();
  
          // Store user details in session variables
          $_SESSION['userFN'] = $user['userFN'];
          $_SESSION['userLN'] = $user['userLN'];
  
          // Redirect to a page where the menu is displayed (e.g., index.php)
          header('Location: index.php');
          exit();
      } else {
          // Invalid credentials
          echo '<script>alert("Invalid username or password. Please try again.");</script>';
      }
  
      $connection->close();
  }
    -->

