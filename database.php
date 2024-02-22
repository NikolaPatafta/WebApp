<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "bookappdb";
    $connect = "";

    try{
        $connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    }
    catch(mysqli_sql_exception){
        echo "Could not connect to the database! <br>";
    }   
?>