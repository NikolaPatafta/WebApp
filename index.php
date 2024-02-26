<?php
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: login.php");
    }
    
    function displayBook($bookName, $author, $randomText){
        echo '<div class="book-container">';
        echo '<h2>' . $bookName . '</h2>';
        echo '<p>Author: ' . $author . '</p>';
        echo '<p>' . $randomText . '</p>';
        echo '</div>';
    }
    $welcomeMessage = "Welcome to AppBook";
    if (isset($_SESSION["user_id"])) {
    require_once "database.php";
    
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT name FROM authors WHERE author_id = ?";
    $stmt = $connect->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();

        if (!empty($name)) {
            $welcomeMessage .= " " . $name;
        }
        $stmt->close();
    }
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
    <div class ="text-center">
    <h1 class ="mt-5">    <?php echo $welcomeMessage; ?>!</h1><br>
    </div>
    <div class="row mb-3">
        <div class="col">
            <a href="addbook.php" class="btn btn-success">Add New Book</a>
        </div>
        <div class="col text-center">
            <a href="viewBooks.php" class="btn btn-warning">Preview Books</a>
        </div>
        <div class="col text-end">
            <a href="authors.php" class="btn btn-info">View Authors</a>
        </div>
    </div>
    <div class="text-center">
        <img src="https://s26162.pcdn.co/wp-content/uploads/2021/03/olaser_libraries.jpg" alt="Library" class="img-fluid mb-3" style="max-width: 100%;">
    </div>
    <div class="text-center">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
</body>
</html>