<?php
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Welcome to home page of BookApp: 
    <?php echo $_SESSION["user"]?>. <br></h2>
    <form action = home.php method = "post">
        <input type = "submit" name = "logout" value = "logout">
    </form>
</body>
</html>
<?php
    if(isset($_POST["logout"])){
        session_destroy();
        header("Location: login.php");
    }
?>