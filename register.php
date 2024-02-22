<?php
    include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action ="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method = "post">
        <h2> Welcome to BookApp </h2>
        Name:<br>
        <input type = "text" name= "name"> <br>
        Surname:<br>
        <input type = "text" name= "surname"> <br>
        Email:<br>
        <input type = "email" name= "email"> <br>
        Password: <br>
        <input type = "password" name = "password"> <br>
        Admin privileges:
        <input type = "checkbox" name = "is_admin"> <br>
        <input type = "submit" name= "submit" value = "register">
    </form>
</body>
</html>
<?php 
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $surname = filter_input(INPUT_POST, "surname", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($name)){
            echo "Please enter a name.";
        }
        elseif(empty($surname)){
            echo "Please enter a surname.";
        }
        elseif(empty($password)){
            echo "Please enter a password.";
        }
        elseif(empty($email)){
            echo "Please enter an email.";
        }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO authors(name, surname, email,password) VALUES('$name' , '$surname', '$email', '$hash')";
            
            try{
                mysqli_query($connect, $sql);
                echo "You are now registered.";
            }
            catch(mysqli_sql_exception){
                echo "That email already exists!";
            }  
        }
    }

    mysqli_close($connect);
?>