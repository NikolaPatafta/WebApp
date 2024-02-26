<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: login.php");
    }
    if(!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1){
        header("Location: index.php");
    }
?>

<?php
require_once("database.php");

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $is_admin = isset($_POST["is_admin"]) ? (int)$_POST["is_admin"] : 0;

    // Validation
    if (empty($id) || empty($name) || empty($surname) || empty($email)) {
        $errorMessage = "All the fields are required";
    } else {
        // Update the record
        $sql = "UPDATE authors SET name = ?, surname = ?, email = ?, is_admin = ? WHERE author_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("sssii", $name, $surname, $email, $is_admin, $id);
        $stmt->execute();

        if ($stmt->error) {
            $errorMessage = "SQL Error: " . $stmt->error;
        } else {
            $successMessage = "Client updated successfully!";
            header("location: authors.php?successMessage=" . urlencode($successMessage));

            exit;
        }

        $stmt->close();
    }
}

// Fetch existing data for display
if (isset($_GET["author_id"])) {
    $id = $_GET["author_id"];
    $sql = "SELECT * FROM authors WHERE author_id = $id";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: index.php");
        exit;
    }

    $name = $row["name"];
    $surname = $row["surname"];
    $email = $row["email"];
    $is_admin = $row["is_admin"];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="name:" value="<?php echo $name ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="surname" placeholder="surname:" value="<?php echo $surname ?>">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="email:" value="<?php echo $email ?>">
            </div>
            <div class="form-btn">
                Administrator privileges:
                <input type="hidden" name="is_admin" value="0">
                <input type="checkbox" class="btn btn-primary" value="1" name="is_admin" <?php echo $is_admin == 1 ? 'checked' : ''; ?>>
            </div>
            <div class="form-btn row">
                <div class="col-md-6 text-end">
                    <input type="submit" class="btn btn-primary" value="Update" name="submit">
                </div>
                <div class="col-md-6 text-start">
                    <a href="authors.php" class="btn btn-warning">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>