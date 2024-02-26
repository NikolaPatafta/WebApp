<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}

$isAdmin = isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1;
$loggedInUserId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;



require_once "database.php";
$sqlAllBooks = "SELECT book_id, headline, text, authors.author_id, authors.name AS author_name, authors.surname AS author_surname FROM book JOIN authors ON book.author_id = authors.author_id";

$resultAllBooks = mysqli_query($connect, $sqlAllBooks);

if (!$resultAllBooks) {
    die("Error in SQL query: " . mysqli_error($connect));
}

$errorMessage = "";
$successMessage = "";
$successMessage = isset($_GET["successMessage"]) ? $_GET["successMessage"] : "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $headline = $_POST["headline"];
    $text = $_POST["text"];
    $author_id = isset($_POST["author_id"]) ? (int)$_POST["author_id"] : 0;

    // Validation
    if (empty($id) || empty($headline) || empty($text) || empty($author_id)) {
        $errorMessage = "All the fields are required";
    } else {
        // Update the record
        $sql = "UPDATE book SET headline = ?, text = ?, author_id = ? WHERE book_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssii", $headline, $text, $author_id, $id);
        $stmt->execute();

        if ($stmt->error) {
            $errorMessage = "SQL Error: " . $stmt->error;
        } else {
            $successMessage = "Book updated successfully!";
            header("location: viewBooks.php?successMessage=" . urlencode($successMessage)); // Corrected redirection
            exit;
        }

        $stmt->close();
    }
}

// Fetch existing data for display
if (isset($_GET["book_id"])) {
    $id = $_GET["book_id"];
    $sql = "SELECT * FROM book WHERE book_id = $id";
    $result = $connect->query($sql);

    if ($result) { // Check if the query was successful
        $row = $result->fetch_assoc();

        if ($row) {
            $headline = $row["headline"];
            $text = $row["text"];
            $author_id = $row["author_id"];
        } else {
            header("location: viewBooks.php");
            exit;
        }
    } else {
        echo "Error: " . $connect->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .last-active-cell{
            width: 250px !important;
        }
    </style>
    <title>View Books</title>
</head>
<body>
    <div class="container">
        <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Headline</th>
                    <th>Text</th>
                    <th>Author Name</th>
                    <?php if ($isAdmin) : ?>
                        <th>Edit</th>
                        <th>Delete</th>
                    <?php endif; ?>
                    <th>Preview</th> <!-- Add this line for the new column -->
                </tr>
            </thead>
            <tbody>
                <?php while ($book = mysqli_fetch_array($resultAllBooks, MYSQLI_ASSOC)) : ?>
                    <tr>
                        <td><?php echo $book['headline']; ?></td>
                        <td><?php echo substr($book['text'], 0, 15) . '...'; ?></td>
                        <td><?php echo $book['author_name'] . ' ' . $book['author_surname']; ?></td>
                        <?php if ($isAdmin || ($loggedInUserId && $loggedInUserId == $book['author_id'])) : ?>
                            <td>
                            <a href="editBooks.php?book_id=<?php echo $book['book_id']; ?>&author_id=<?php echo $book['author_id']; ?>" class="btn btn-warning">Edit
                            </a>
                            </td>
                            <td>
                                <a href="#" onclick="confirmDelete(<?php echo $book['book_id']; ?>)" class="btn btn-danger">Delete</a>
                                <script>
                                    function confirmDelete(bookId) {
                                        var confirmDelete = confirm("Are you sure you want to delete this book?");
                                        if (confirmDelete) {
                                            window.location.href = "deletebook.php?book_id=" + bookId;
                                        }
                                    }
                                </script>
                            </td>
                        <?php endif; ?>
                        <td>
                            <a href="previewBooks.php?book_id=<?php echo $book['book_id']; ?>" class="btn btn-success">Preview Book</a>
                        </td>
                    </tr>
                <?php endwhile; ?>  
            </tbody>
        </table>
        <div class="row">
            <div class="col">
                <a href="index.php" class="btn btn-info">Back to index</a>
            </div>
        </div>
    </div>
</body>
</html>
