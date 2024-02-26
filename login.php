<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
$successMessage = isset($_GET['successMessage']) ? urldecode($_GET['successMessage']) : '';

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once "database.php";

    $sql = "SELECT author_id, password, is_admin FROM authors WHERE email = ?";
    $stmt = $connect->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password, $is_admin);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                
                $_SESSION["user"] = $email;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["is_admin"] = $is_admin;

                $sqlUpdateLastActive = "UPDATE authors SET last_active = NOW() WHERE author_id = ?";
                $stmtUpdateLastActive = $connect->prepare($sqlUpdateLastActive);
                $stmtUpdateLastActive->bind_param("i", $user_id);
                $stmtUpdateLastActive->execute();
                $stmtUpdateLastActive->close();

                header("Location: index.php");
                die();
            } else {
                $error_message = "Password does not match.";
            }
        } else {
            $error_message = "Email does not exist.";
        }

        $stmt->close();
    } else {
        $error_message = "Error: " . $connect->error;
    }
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
            if (isset($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            if (!empty($successMessage)) {
                echo "<div class='alert alert-success'>$successMessage</div>";
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