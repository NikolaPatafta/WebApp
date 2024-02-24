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
    <meta http-equiv="X-UA-Compatible" content ="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href = "style.css">
</head>
<body>
    <div class = "container">
    <?php
        if(isset($_POST["submit"])){
            $name = $_POST["name"];
            $surname = $_POST["surname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];

            if(isset($_POST["is_admin"])){
                $is_admin = 1;
            }
            else{
                $is_admin = 0;
            }

        
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();
        
            if(empty($name) || empty($surname) || empty($email) || empty($password) || empty($passwordRepeat)){
                array_push($errors, "All fields are required.");
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                array_push($errors, "Email is not valid");
            }
            if(strlen($password) < 8){
                array_push($errors, "Password must be at least 8 characters long.");
            }
            if($password!== $passwordRepeat){
                array_push($errors, "Password does not match.");
            }
            require_once "database.php";
            $sql = "SELECT * FROM authors WHERE email = '$email'";
            $result = mysqli_query($connect, $sql);
            $rowCount = mysqli_num_rows($result);
            if($rowCount>0){
                array_push($errors, "Email already exists!");
            }
            if(count($errors) > 0){
                foreach($errors as $error){
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            else{
                
                $sql = "INSERT INTO authors (name, surname, email, password, is_admin) VALUES(?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($connect);
                $preparestmt = mysqli_stmt_prepare($stmt, $sql);
                if($preparestmt){
                    mysqli_stmt_bind_param($stmt, "ssssi", $name, $surname, $email, $passwordhash, $is_admin);
                    mysqli_stmt_execute($stmt);
                    echo "<div class = 'alert alert-success'> You are registered succesfully.</div>";
                }
                else{
                    die("Something went wrong");
                }
            
            }
        }
        ?>
        <form action = "register.php" method = "post">
            <div class = "form-group">
                <input type = "text" class = "form-control" name = "name" placeholder="name:">
            </div>
            <div class = "form-group">
                <input type = "text" class = "form-control" name = "surname" placeholder="surname:">
            </div>
            <div class = "form-group">
                <input type = "email" class = "form-control"name = "email" placeholder="email:">
            </div>
            <div class = "form-group">
                <input type = "password" class = "form-control" name = "password" placeholder="password:">
            </div>
            <div class = "form-group">
                <input type = "password" class = "form-control" name = "repeat_password" placeholder="repeat password:">
            </div>
            <div class = "form-btn">
                Administrator privilages:
                <input type = "checkbox" class ="btn btn-primary" value = "is_admin" name ="is_admin">
            </div>
            <div class = "form-btn">
                <input type = "submit" class ="btn btn-primary" value = "Register" name ="submit">
            </div>
            
        </from>
        <div><p><br>Already registred? <a href="login.php">Login here </a></p></div>
    </div>
</body>
</html>