<?php
function logAction($action, $entityType, $entityID) {
    global $conn; // Use the connection variable you have established

    // SQL query to insert into the updatelog table
    $logSql = "INSERT INTO updatelog (action, entityType, entityID, timestamp) VALUES (?, ?, ?, NOW())";
    
    // Prepare the statement
    $stmt_log = $conn->prepare($logSql);

    if ($stmt_log) {
        // Bind parameters
        $stmt_log->bindParam(1, $action, PDO::PARAM_STR);
        $stmt_log->bindParam(2, $entityType, PDO::PARAM_STR);
        $stmt_log->bindParam(3, $entityID, PDO::PARAM_INT);
        
        // Execute the statement
        if ($stmt_log->execute()) {
            echo "<script>alert('Action logged successfully')</script>";
        } else {
            echo "<script>alert('Error logging action: " . $conn->error . "')</script>";
        }

        // Close the statement
        $stmt_log->closeCursor();
    } else {
        echo "<script>alert('Error preparing log statement: " . $conn->error . "')</script>";
    }
}
$host = 'localhost';
$database = 'db_nt3102';
$username = 'root';
$password = '';

// Create connection
$conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check connection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $dsn = "mysql:host=localhost;dbname=db_nt3102";
        $dbUsername = "root";
        $dbPassword = "";

        // New Author
        $dbAuthor = new PDO($dsn, $dbUsername, $dbPassword);
        
        // Get the selected author name from the form
        $authorName = $_POST['authorName'];

        // Fetch author ID based on the selected author name
        $authorQuery = "SELECT authorID FROM author WHERE CONCAT(authorFn, ' ', authorLn) = ?";
        $authorStmt = $dbAuthor->prepare($authorQuery);
        $authorStmt->execute([$authorName]);
        $authorID = $authorStmt->fetchColumn();

        // New Publisher
        $dbPublisher = new PDO($dsn, $dbUsername, $dbPassword);
        
        // Get the selected publisher name from the form
        $publisherName = $_POST['publisherName'];

        // Fetch publisher ID based on the selected publisher name
        $publisherQuery = "SELECT publisherID FROM publisher WHERE publisherName = ?";
        $publisherStmt = $dbPublisher->prepare($publisherQuery);
        $publisherStmt->execute([$publisherName]);
        $publisherID = $publisherStmt->fetchColumn();

        // If author does not exist, insert a new author
        if ($authorName === 'new') {
            // Additional form fields for a new author
            $newAuthorFn = $_POST['newAuthorFn'];
            $newAuthorLn = $_POST['newAuthorLn'];

            // Insert the new author into the database
            $insertAuthorQuery = "INSERT INTO author (authorFn, authorLn) VALUES (?, ?)";
            $insertAuthorStmt = $dbAuthor->prepare($insertAuthorQuery);
            $insertAuthorStmt->execute([$newAuthorFn, $newAuthorLn]);

            // Retrieve the newly generated author ID
            $authorID = $dbAuthor->lastInsertId();

            // Log the action
            logAction("INSERT", "author", $authorID);

            $successAuthor = "New author added successfully with ID: $authorID";
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successAuthor</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";

        } elseif (!$authorID) {
            $nameParts = explode(' ', $authorName, 2);

            // Check if $nameParts is an array and has at least two elements
            if (is_array($nameParts) && count($nameParts) >= 2) {
                list($authorFn, $authorLn) = $nameParts;
                $insertAuthorQuery = "INSERT INTO author (authorFn, authorLn) VALUES (?, ?)";
                $insertAuthorStmt = $dbAuthor->prepare($insertAuthorQuery);
                $insertAuthorStmt->execute([$authorFn, $authorLn]);

                // Retrieve the newly generated author ID
                $authorID = $dbAuthor->lastInsertId();

                // Log the action
                logAction("INSERT", "author", $authorID);

                $errorAuthorFnLn = "Error: Unable to extract first and last name from authorName.";
            } else {
                // Handle the case where explode did not return the expected array
                die("
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>$errorAuthorFnLn</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
                ");
            }
        }

        // If publisher does not exist, insert a new publisher
        if ($publisherName === 'new') {
            // Additional form fields for a new publisher
            $publisherNameNew = $_POST['publisherNameNew'];
            $publisherAddress = $_POST['publisherAddress'];

            // Insert the new publisher into the database
            $insertPublisherQuery = "INSERT INTO publisher (publisherName, publisherAddress) VALUES (?, ?)";
            $insertPublisherStmt = $dbPublisher->prepare($insertPublisherQuery);
            $insertPublisherStmt->execute([$publisherNameNew, $publisherAddress]);

            // Retrieve the newly generated publisher ID
            $publisherID = $dbPublisher->lastInsertId();

            // Log the action
            logAction("INSERT", "publisher", $publisherID);

            $successPublisher = "New publisher added successfully with ID: $publisherID";
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successPublisher</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        } elseif (!$publisherID) {
            list($publisherName, $publisherAddress) = explode(' ', $publisherName, 2);
            $insertPublisherQuery = "INSERT INTO publisher (publisherName, publisherAddress) VALUES (?, ?)";
            $insertPublisherStmt = $dbPublisher->prepare($insertPublisherQuery);
            $insertPublisherStmt->execute([$publisherName, $publisherAddress]);

            // Retrieve the newly generated publisher ID
            $publisherID = $dbPublisher->lastInsertId();

            // Log the action
            logAction("INSERT", "publisher", $publisherID);
        }

        // Insert a new record
        $insertQuery = "INSERT INTO book (authorID, publisherID, bookTitle, ISBN, description, genre, publishDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);

        // Get values from the form
        $bookTitle = $_POST['bookTitle'];
        $isbn = $_POST['isbn'];
        $description = $_POST['description'];
        $genre = $_POST['genre'];
        $publishDate = $_POST['publishDate'];

        // Pass $authorID and $publisherID as parameters in the execute method
        $result = $stmtInsert->execute([$authorID, $publisherID, $bookTitle, $isbn, $description, $genre, $publishDate]);

        if ($result) {
            $successSaved = 'Successfully saved.';
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successSaved</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";

            // Log the action
            logAction("INSERT", "book", $conn->lastInsertId());
        } else {
            $errorSave = 'There were errors while saving the data.';
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorSave</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script>
        function handleAuthorSelection() {
            var authorDropdown = document.getElementById("authorName");
            var newAuthorFnInput = document.getElementById("newAuthorFn");
            var newAuthorLnInput = document.getElementById("newAuthorLn");

            if (authorDropdown.value === "new") {
                newAuthorFnInput.required = true;
                newAuthorLnInput.required = true;
                newAuthorFnInput.disabled = false;
                newAuthorLnInput.disabled = false;
            } else {
                newAuthorFnInput.required = false;
                newAuthorLnInput.required = false;
                newAuthorFnInput.disabled = true;
                newAuthorLnInput.disabled = true;
            }
        }
        function handlePublisherSelection() {
            var publisherDropdown = document.getElementById("publisherName");
            var publisherNameNewInput = document.getElementById("publisherNameNew");
            var publisherAddressInput = document.getElementById("publisherAddress");

            if (publisherDropdown.value === "new") {
                publisherNameNewInput.required = true;
                publisherAddressInput.required = true;
                publisherNameNewInput.disabled = false;
                publisherAddressInput.disabled = false;
            } else {
                publisherNameNewInput.required = false;
                publisherAddressInput.required = false;
                publisherNameNewInput.disabled = true;
                publisherAddressInput.disabled = true;
            }
        }
    </script>
    <title>Add Book Form</title>
</head>

<body>
    <div class="container my-5">
        <h2>Add Book Form</h2>
        <form method="post" action="" onsubmit="return handleAuthorSelection()" onsubmit="return handlePublisherSelection()">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="bookTitle" id="bookTitle" placeholder="Title of the Book" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Author</label>
                <div class="col-sm-6">
                    <select class="form-select" name="authorName" id="authorName" onchange="handleAuthorSelection()" required>
                        <option value="" disabled selected>Select an author</option>
                        <?php
                        try {

                            // Connect to the database
                            $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Fetch data from the database
                            $query = "SELECT CONCAT(authorFn, ' ', authorLn) AS authorName FROM author";
                            $stmt = $conn->query($query);

                            // Loop through the results and create options for the dropdown
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['authorName']}'>{$row['authorName']}</option>";
                            }

                            // Add an option for adding a new author
                            echo "<option value='new'>Add New Author</option>";

                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">New Author Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="newAuthorFn" id="newAuthorFn" placeholder="First Name" disabled>
                    <input type="text" class="form-control" name="newAuthorLn" id="newAuthorLn" placeholder="Last Name" disabled>
                </div>
            </div>
           
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Publisher</label>
                <div class="col-sm-6">
                    <select class="form-select" name="publisherName" id="publisherName" onchange="handlePublisherSelection()" required>
                        <option value="" disabled selected>Select a publisher</option>
                        <?php
                        try {

                            // Connect to the database
                            $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Fetch data from the database
                            $query = "SELECT publisherName FROM publisher";
                            $stmt = $conn->query($query);

                            // Loop through the results and create options for the dropdown
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['publisherName']}'>{$row['publisherName']}</option>";
                            }

                            // Add an option for adding a new author
                            echo "<option value='new'>Add New Publisher</option>";

                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">New Publisher Name & Address</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="publisherNameNew" id="publisherNameNew" placeholder="Name" disabled>
                    <input type="text" class="form-control" name="publisherAddress" id="publisherAddress" placeholder="Address" disabled>
                </div>
            </div>
            
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Genre</label>
                <div class="col-sm-6">
                    <select name="genre" class="form-control" id="bookDropdown" required>
                        <option value="" disabled selected>Select a genre</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Science Fiction">Science Fiction</option>
                        <option value="Mystery/Thriller">Mystery/Thriller</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Biography">Biography</option>
                        <option value="Science">Science</option>
                        <option value="Research Papers">Research Papers</option>
                        <option value="Codes">Codes</option>
                        <option value="Comedy">Comedy</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">ISBN</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="isbn" id="isbn" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Publish Date</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="publishDate" id="publishDate" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-danger" href="./mainaddbook.php" role="button">Back</a>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
    


    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>