<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "database.php";

// Retrieve author's details from the database
$author_id = "";
$author_name = $author_surname = "";

if (isset($_SESSION["user_id"])) {
    $author_id = $_SESSION["user_id"];

    // Fetch author's details from the database
    $sql = "SELECT name, surname FROM authors WHERE author_id = ?";
    $stmt = $connect->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $author_id);
        $stmt->execute();

        $stmt->bind_result($author_name, $author_surname);
        $stmt->fetch();

        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Ensure the required fields are set
    if (isset($_POST['headline'], $_POST['text']) && !empty($_POST['headline']) && !empty($_POST['text'])) {
        $headline = $_POST['headline'];
        $text = $_POST['text'];

        // Insert the book information into the database
        $sql = "INSERT INTO book (headline, text, author_id) VALUES (?, ?, ?)";
        $stmt = $connect->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssi", $headline, $text, $author_id);
            $stmt->execute();

            if ($stmt->error) {
                echo "Error during execution: " . $stmt->error;
            } else {
                echo "<div class='alert alert-success'>Book added successfully.</div>";
            }

            $stmt->close();
        } else {
            echo "Error during prepare: " . $connect->error;
        }
    } else {
        echo "<div class='alert alert-danger'>Please fill in all the required fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Display Author details -->
        <?php
        if (!empty($author_name) && !empty($author_surname)) {
            echo "<div class='alert alert-info'>Author: $author_name $author_surname</div>";
        }
        ?>

        <!-- Your existing form -->
        <form action="addbook.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="headline" placeholder="Book's headline:" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="text" placeholder="Book's text:" rows="5" required></textarea>
            </div>
            <div class="form-btn row">
        <div class="col">
            <input type="submit" class="btn btn-primary" value="Add New Book" name="submit">
        </div>
        <div class="col text-end">
        <a href="index.php" class="btn btn-info">Back to index</a>
        </div>
            </div>
            </div>  
        </form>
    </div>
</body>
</html>

