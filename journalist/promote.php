<?php
    session_start();
    include_once "../includes/connection.php";
    include_once "../includes/functions.php";

    if(!isset($_SESSION['user_id'])){
        header("Location: ../");
    }
    if($_SESSION['user_role'] == 'admin'){
        //Fine
    }else {
        echo "fuck off dicky";
        exit();
    }
    if(!isset($_GET['id'])){
        echo "<script>window.location = 'crafters.php'</script>";
        exit();
    }
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "UPDATE `users` SET `user_role`='journalist' WHERE `user_id`='$id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>window.location = 'crafters.php'</script>";
    }else {
        echo "Error";
    }
?>