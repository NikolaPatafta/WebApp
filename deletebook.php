<?php
    if (isset($_GET["book_id"])) {
        $book_id = $_GET["book_id"];

        require_once("database.php");

        $sql = "DELETE FROM book WHERE book_id = $book_id";
        $connect->query($sql);
    }
    header("location: viewBooks.php");
    echo "Book deleted successfully.";
    exit;
?>
