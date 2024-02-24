<?php
    if(isset($_GET["author_id"])){
        $author_id = $_GET["author_id"];

        require_once ("database.php");

        $sql = "DELETE FROM authors WHERE author_id = $author_id";
        $connect->query($sql);
    }

    header("location: authors.php");
    echo "User deleted succesfuly.";
    exit;
?>