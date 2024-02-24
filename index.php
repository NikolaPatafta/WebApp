<?php
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: login.php");
    }
?>
<?php
    function displayBook($bookName, $author, $randomText){
        echo '<div class="book-container">';
        echo '<h2>' . $bookName . '</h2>';
        echo '<p>Author: ' . $author . '</p>';
        echo '<p>' . $randomText . '</p>';
        echo '</div>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href = "style.css">
    <title>User Dashboard</title>

</head>
<body>
    <div class="container">
        <h1> Welcome to BookApp!</h1>
        <div class="row">
            <div class="col">
                <input type="submit" value="Create new book" name="newbook" class="btn btn-primary">
            </div>
            <div class="col">
                <a href="authors.php" class="btn btn-primary">View authors</a>
            </div>
        </div>

        <?php
        // Displaying multiple books for testing purposes
        displayBook("Book 1", "Author 1", "Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        displayBook("Book 2", "Author 2", "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");
        ?>   
        <div class="form-btn">
            <a href="logout.php" class="btn btn-warning">Logout</a>
        </div>
    </div>
</body>
</html>