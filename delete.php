<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["author_id"])) {
    $author_id = $_GET["author_id"];

    require_once("database.php");
    
    $isAdmin = isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1;

    $sqlCheckAdmin = "SELECT is_admin, IFNULL(TIMESTAMPDIFF(MINUTE, last_active, NOW()), 0) AS inactive_minutes FROM authors WHERE author_id = ?";
    $stmtCheckAdmin = $connect->prepare($sqlCheckAdmin);
    $stmtCheckAdmin->bind_param("i", $author_id);
    $stmtCheckAdmin->execute();
    $stmtCheckAdmin->bind_result($is_admin, $inactive_minutes);
    $stmtCheckAdmin->fetch();
    $stmtCheckAdmin->close();

    if ($_SESSION["user_id"] == $author_id) {
        $sql = "DELETE FROM authors WHERE author_id = $author_id";
        $connect->query($sql);

        session_destroy();
        header("Location: login.php?successMessage=Your account has been deleted");
        exit;
        
        #***********
        # Administrator može izbrisati drugi administrator ako je neaktivan neko vrijeme.
        # Trenutno postavljeno kada je drugi administrator neaktivan vise od 10 minuta. (u svrhe testiranja)
        #***********
    } elseif ($isAdmin && ($is_admin != 1 || $inactive_minutes > 10)) {
        $sql = "DELETE FROM authors WHERE author_id = $author_id";
        $connect->query($sql);

        header("Location: authors.php?successMessage=User deleted successfully.");
        exit;
    } else {
        header("Location: authors.php?errorMessage=You cannot delete this active administrator!");
        exit;
    }
} else {
    header("Location: authors.php?errorMessage=Author ID not provided");
    exit;
}
?>
