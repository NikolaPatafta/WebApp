<?php 
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: login.php");
    }   
?>
<?php
    require_once "database.php";
    
    if (isset($_GET['book_id'])) {
        $bookId = $_GET['book_id'];

        $sqlBook = "SELECT headline, text, authors.name AS author_name, authors.surname AS author_surname FROM book JOIN authors ON book.author_id = authors.author_id WHERE book_id = ?";
        $stmt = $connect->prepare($sqlBook);
        
        if ($stmt) {
            $stmt->bind_param("i", $bookId);
            $stmt->execute();
            $stmt->bind_result($headline, $text, $authorName, $authorSurname);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo "Error during prepare: " . $connect->error;
            exit();
        }
    } else {
        header("Location: viewBooks.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Preview Book</title>
</head>
<body>
    <div class="container">
        <h1><?php echo $headline; ?></h1>
        <p><strong>Author:</strong> <?php echo $authorName . ' ' . $authorSurname; ?></p>
        <p><strong></strong> <?php echo $text; ?></p>
        <div class="row">
            <div class="col">
                <a href="viewBooks.php" class="btn btn-info">Back to Books</a>
            </div>
        </div>
    </div>
</body>
</html>
