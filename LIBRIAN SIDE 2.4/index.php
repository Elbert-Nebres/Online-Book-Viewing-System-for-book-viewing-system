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
    input[type=text], input[type=password] {
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
</head>
<body>
  <div class="login-form">
    <img src="Batangas_State_Logo.png" alt="Logo" class="logo">
    <center> <h1>Librarian</h1> </center>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <label for="Username">Username</label>
      <input type="text" id="Username" name="Username" placeholder="Enter your username" required>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" required>
      <input type="submit" value="Login">
    </form>
  </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
   session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST["Username"];
    $pass = $_POST["password"];

    $host = 'localhost'; // corrected lowercase 'localhost'
    $database = 'db_nt3102'; 
    $username = 'root'; 
    $password = ""; 

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT tbemployee.*, tbemployee.lastname, tbemployee.firstname, librarian.librarianPassword
    FROM tbemployee
    INNER JOIN librarian ON tbemployee.empid = librarian.empid
    WHERE CONCAT(tbemployee.firstname, ' ', tbemployee.lastname) = '" . $conn->real_escape_string($uname) . "'";

    
    $result = $conn->query($sql);
    
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    if ($result->num_rows > 0) {
        // Username exists, check password
        $row = $result->fetch_assoc();
        if ($pass === $row["librarianPassword"]) {
            session_start();
                $_SESSION["username"] = $row["firstname"] . " " . $row["lastname"];
                header("Location: main.php"); // Redirect to the dashboard or desired page
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
    } 

    $conn->close();
  }
?>
</body>
</html>