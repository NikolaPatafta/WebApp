<?php 
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: login.php");
    }   
?>
<?php
    $successMessage = isset($_GET['successMessage']) ? urldecode($_GET['successMessage']) : '';
    $errorMessage = isset($_GET['errorMessage']) ? urldecode($_GET['errorMessage']) : '';
    $isAdmin = isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1;

    require_once "database.php";
    $sqlAllAuthors = "SELECT author_id, name, surname, email, is_admin, last_active FROM authors";
    $resultAllAuthors = mysqli_query($connect, $sqlAllAuthors);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .last-active-cell {
            width: 250px !important;
        }
        input[type="checkbox"][disabled] {
        opacity: 7; /* Adjust the opacity for the desired level of gray */
        cursor: not-allowed;
        }

        input[type="checkbox"][disabled]:checked {
            background-color: red; /* Change this color for the checked state */
        }
    </style>
    <title>View Authors</title>
</head>

<body>
    <div class="container">
        <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th class="last-active-cell">Last Active</th>
                    <?php if ($isAdmin) : ?>
                        <th>Edit</th>
                        <th>Delete</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($author = mysqli_fetch_array($resultAllAuthors, MYSQLI_ASSOC)) : ?>
                    <tr>
                        <td><?php echo $author['name']; ?></td>
                        <td><?php echo $author['surname']; ?></td>
                        <td><?php echo $author['email']; ?></td>
                        <td><input type="checkbox" <?php echo $author['is_admin'] == 1 ? 'checked' : ''; ?> disabled></td>
                        <td class="last-active-cell"><?php echo $author['last_active']; ?></td>
                        <?php if ($isAdmin) : ?>
                            <td>
                                <a href="edit.php?author_id=<?php echo $author['author_id']; ?>" class="btn btn-warning">Edit</a>
                            </td>
                            <td>
                                <a href="#" onclick="confirmDelete(<?php echo $author['author_id']; ?>)" class="btn btn-danger">Delete</a>
                                <script>
                                    function confirmDelete(authorId) {
                                        var confirmDelete = confirm("Are you sure you want to delete this author?");
                                        if (confirmDelete) {
                                            window.location.href = "delete.php?author_id=" + authorId;
                                        }
                                    }
                                </script>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col">
                <a href="index.php" class="btn btn-info">Back to index</a>
            </div>
            <?php if ($isAdmin) : ?>
                <div class="col text-end">
                    <a href="addAuthor.php" class="btn btn-primary">Add new Author</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
