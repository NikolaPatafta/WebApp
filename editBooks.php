<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}

$isAdmin = isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1;
$loggedInUserId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$author_id = isset($_GET['author_id']) ? (int)$_GET['author_id'] : null;
if (!$isAdmin && $loggedInUserId != $author_id) {
    header("Location: index.php");
    exit;
}

require_once("database.php");

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $headline = $_POST["headline"];
    $text = $_POST["text"];
    $author_id = isset($_POST["author_id"]) ? (int)$_POST["author_id"] : 0;

    if (empty($id) || empty($headline) || empty($text) || empty($author_id)) {
        $errorMessage = "All the fields are required";
    } else {
        $sql = "UPDATE book SET headline = ?, text = ?, author_id = ? WHERE book_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssii", $headline, $text, $author_id, $id);
        $stmt->execute();

        if ($stmt->error) {
            $errorMessage = "SQL Error: " . $stmt->error;
        } else {
            $successMessage = "Book updated successfully!";
            header("location: viewBooks.php?successMessage=" . urlencode($successMessage));
            exit;
        }
        $stmt->close();
    }
}

if (isset($_GET["book_id"])) {
    $id = $_GET["book_id"];
    $author_id = isset($_GET["author_id"]) ? $_GET["author_id"] : null;
    $sql = "SELECT book.*, authors.name AS author_name, authors.surname AS author_surname FROM book
            JOIN authors ON book.author_id = authors.author_id
            WHERE book_id = $id";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: viewBooks.php");
        exit;
    }

    $headline = $row["headline"];
    $text = $row["text"];
    $author_name = $row["author_name"];
    $author_surname = $row["author_surname"];
    $author_id = $row["author_id"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

    <body>
        <div class="container">
                    <form method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="author_id" value="<?php echo $author_id; ?>">
                <div class="form-group">
                    <label for="headline">Headline:</label>
                    <input type="text" class="form-control" name="headline" value="<?php echo $headline ?>">
                </div>
                <div class="form-group">
                    <label for="text">Text:</label>
                    <textarea class="form-control" name="text" rows="5"><?php echo $text ?></textarea>
                </div>
                <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" class="form-control" value="<?php echo $author_name . ' ' . $author_surname; ?>" readonly>
                </div>
                <div class="form-btn row">
                    <div class="col-md-6 text-end">
                        <input type="submit" class="btn btn-primary" value="Update" name="submit">
                    </div>
                    <div class="col-md-6 text-start">
                        <a href="viewBooks.php" class="btn btn-warning">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </body>

</html>