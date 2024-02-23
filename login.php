<?php
session_start();
if(isset($_SESSION["user"])){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href = "style.css">
</head>
<body>
    <div class ="container">
        <?php
            if(isset($_POST["login"])){
                $email = $_POST["email"];
                $password = $_POST["password"];
                require_once "database.php";
                $sql = "SELECT * FROM authors WHERE email = '$email'";
                $result = mysqli_query($connect, $sql);
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if($user){
                    if(password_verify($password, $user["password"])){
                        header("Location: index.php");
                        session_start();
                        $_SESSION["user"] = "yes";
                        die();
                    }else{
                        echo "<div class = 'alert alert-danger'>Password does not match.</div>"; 
                    }
                }
                else{
                    echo "<div class = 'alert alert-danger'>Email does not exist.</div>";
                }
            }
        ?>

        <form action = "login.php" method = "post">
            <div class = "form-group">
                <input type = "email" placeholder = "Enter email:" name ="email" class ="form-control">
            </div>
            <div class = "form-group">
                <input type = "password" placeholder = "Enter password:" name ="password" class ="form-control">
            </div>
            <div class="form-btn">
                <input type = "submit" value = "Login" name = "login" class = "btn btn-primary">
            </div>
        </form>
        <div><p><br>Not registred yet? <a href="register.php"> Register here </a></p></div>
    </div>
</body>
</html>