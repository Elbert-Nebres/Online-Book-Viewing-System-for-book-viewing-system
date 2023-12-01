<?php
function logAction($action, $entityType, $entityID) {
    global $conn; // Use the connection variable you have established

    // SQL query to insert into the updatelog table
    $logSql = "INSERT INTO updatelog (action, entityType, entityID, timestamp) VALUES (?, ?, ?, NOW())";
    
    // Prepare the statement
    $stmt_log = $conn->prepare($logSql);

    if ($stmt_log) {
        // Bind parameters
        $stmt_log->bind_param("ssi", $action, $entityType, $entityID);
        
        // Execute the statement
        if ($stmt_log->execute()) {
            echo "<script>alert('Action logged successfully')</script>";
        } else {
            echo "<script>alert('Error logging action: " . $conn->error . "')</script>";
        }

        // Close the statement
        $stmt_log->close();
    } else {
        echo "<script>alert('Error preparing log statement: " . $conn->error . "')</script>";
    }
}

if (isset($_GET["bookID"])) {
    $bookID = $_GET["bookID"];

    $host = 'localhost';
    $database = 'db_nt3102';
    $username = 'root';
    $password = '';

    $conn = new mysqli($host, $username, $password, $database);

    // Retrieve authorID and publisherID associated with the book
    $sqlSelect = "SELECT authorID, publisherID FROM book WHERE bookID=$bookID";
    $result = $conn->query($sqlSelect);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $authorID = $row['authorID'];
        $publisherID = $row['publisherID'];

        // Log the action before deleting the book
        logAction("DELETE", "book", $bookID);

        // Delete the book
        $sqlDeleteBook = "DELETE FROM book WHERE bookID=$bookID";
        $conn->query($sqlDeleteBook);

        // Check if the author is not associated with other books
        $sqlAuthorCheck = "SELECT COUNT(*) AS bookCount FROM book WHERE authorID=$authorID";
        $resultAuthorCheck = $conn->query($sqlAuthorCheck);
        $rowAuthorCheck = $resultAuthorCheck->fetch_assoc();
        $bookCountAuthor = $rowAuthorCheck['bookCount'];

        // Delete the author if not associated with other books
        if ($bookCountAuthor == 0) {
            $sqlDeleteAuthor = "DELETE FROM author WHERE authorID=$authorID";
            $conn->query($sqlDeleteAuthor);
        }

        // Check if the publisher is not associated with other books
        $sqlPublisherCheck = "SELECT COUNT(*) AS bookCount FROM book WHERE publisherID=$publisherID";
        $resultPublisherCheck = $conn->query($sqlPublisherCheck);
        $rowPublisherCheck = $resultPublisherCheck->fetch_assoc();
        $bookCountPublisher = $rowPublisherCheck['bookCount'];

        // Delete the publisher if not associated with other books
        if ($bookCountPublisher == 0) {
            $sqlDeletePublisher = "DELETE FROM publisher WHERE publisherID=$publisherID";
            $conn->query($sqlDeletePublisher);
        }
    }
}

header("location: ./mainaddbook.php");
exit;
?>