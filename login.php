<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action = "index.php" method = "post">
        <h2> Welcome to BookApp </h2>
        Email:<br>
        <input type = "email" name= "email"> <br>
        Password: <br>
        <input type = "password" name = "password"> <br>
        <input type = "submit" name = "submit" value = "login">
    </form>   
</body>
</html>

<?php
    if(isset($_POST["login"])){
        if(!empty($_POST["email"] && !empty($_POST["password"]))){
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["password"] = $_POST["password"];

            echo $_SESSION["email"] . "<br>";
            echo $_SESSION["password"] . "<br>";
        }
        else{
            echo "Missing username/password";
        }
    }
?>