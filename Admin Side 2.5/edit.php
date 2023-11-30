<?php
$host = 'localhost';
$database = 'db_nt3102';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

$bookID           = "";
$authorID         = "";
$publisherID      = "";
$bookTitle        = "";
$description      = "";
$genre            = "";
$ISBN             = "";
$publishDate      = "";

$errorMessage     = "";
$successMessage   = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // GET 
    
    if (!isset($_GET["bookID"])) {
        header("location: ./mainaddbook.php");
        exit;
    }

    $bookID = $_GET["bookID"];

    $sql = "SELECT book.*, CONCAT(author.authorFn, ' ', author.authorLn) AS authorName, publisher.publisherName 
            FROM book
            LEFT JOIN author ON book.authorID = author.authorID
            LEFT JOIN publisher ON book.publisherID = publisher.publisherID
            WHERE bookID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: ./mainaddbook.php");
        exit;
    }

    // Fetch data from the database
    $authorID         = $row['authorID'];
    $publisherID      = $row['publisherID'];
    $bookTitle        = $row['bookTitle'];
    $description      = $row['description'];
    $genre            = $row['genre'];
    $ISBN             = $row['ISBN'];
    $publishDate      = $row['publishDate'];

} else {
    // POST
    $bookID           = $_POST['bookID'];
    $authorID         = $_POST['authorID'];
    $publisherID      = $_POST['publisherID'];
    $bookTitle        = $_POST['bookTitle'];
    $description      = $_POST['description'];
    $genre            = $_POST['genre'];
    $ISBN             = $_POST['ISBN'];
    $publishDate      = $_POST['publishDate'];

    do {
        if (empty($bookID) || empty($authorID) || empty($publisherID) || empty($bookTitle) || 
            empty($description) || empty($genre) || empty($ISBN) || empty($publishDate)) {
            $errorMessage = "All the fields are required";
            break;
        }

        $sql = "UPDATE book SET authorID=?, publisherID=?, bookTitle=?, description=?, genre=?, ISBN=?, publishDate=? WHERE bookID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssssi", $authorID, $publisherID, $bookTitle, $description, $genre, $ISBN, $publishDate, $bookID);
        $stmt->execute();

        if ($stmt->error) {
            $errorMessage = "Invalid query: " . $stmt->error;
            break;
        }

        $successMessage = "Book updated correctly";

        // Fetch updated data to display in the form
        $sql = "SELECT book.*, CONCAT(author.authorFn, ' ', author.authorLn) AS authorName, publisher.publisherName 
                FROM book
                LEFT JOIN author ON book.authorID = author.authorID
                LEFT JOIN publisher ON book.publisherID = publisher.publisherID
                WHERE bookID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            header("location: ./mainaddbook.php");
            exit;
        }

        // Update variables with new data
        $authorID         = $row['authorID'];
        $publisherID      = $row['publisherID'];
        $bookTitle        = $row['bookTitle'];
        $description      = $row['description'];
        $genre            = $row['genre'];
        $ISBN             = $row['ISBN'];
        $publishDate      = $row['publishDate'];

    } while (false);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Edit Book Form</title>
</head>
<body>
    <div class="container my-5">
        <h2>Edit</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <?php
        if (!empty($successMessage)) {
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post">
            <input type="hidden" name="bookID" value="<?php echo $bookID; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Author</label>
                <div class="col-sm-6">
                    <select class="form-select" name="authorID" required>
                        <?php
                        // Fetch authors from the database and populate the dropdown
                        $authorQuery = "SELECT authorID, CONCAT(authorFn, ' ', authorLn) AS authorName FROM author";
                        $authorResult = $conn->query($authorQuery);
                        while ($authorRow = $authorResult->fetch_assoc()) {
                            $selected = ($authorRow['authorID'] == $authorID) ? 'selected' : '';
                            echo "<option value='{$authorRow['authorID']}' $selected>{$authorRow['authorName']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Publisher</label>
                <div class="col-sm-6">
                    <select class="form-select" name="publisherID" required>
                        <?php
                        // Fetch publishers from the database and populate the dropdown
                        $publisherQuery = "SELECT publisherID, publisherName FROM publisher";
                        $publisherResult = $conn->query($publisherQuery);
                        while ($publisherRow = $publisherResult->fetch_assoc()) {
                            $selected = ($publisherRow['publisherID'] == $publisherID) ? 'selected' : '';
                            echo "<option value='{$publisherRow['publisherID']}' $selected>{$publisherRow['publisherName']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="bookTitle" value="<?php echo $bookTitle; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">ISBN</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ISBN" value="<?php echo $ISBN; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-6">
                    <textarea name="description" rows="4" class="form-control"><?php echo $description; ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Genre</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="genre" value="<?php echo $genre; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Publish Date</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="publishDate" value="<?php echo $publishDate; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-danger" href="./mainaddbook.php" role="button">Cancel</a>
                </div>
            </div>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>