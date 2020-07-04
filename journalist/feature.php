<?php
    include "../includes/connection.php";
    include "../includes/functions.php";
    require '../steamauth/steamauth.php';
    if(isset($_SESSION['steamid'])) {
        include ('../steamauth/userInfo.php'); //To access the $steamprofile array
    }
    if(!isset($_SESSION['user_id'])){
    echo "<script>window.location=window.location</script>";
    exit();
    }
    if($_SESSION['user_role']!='admin'){
        echo "<script>window.location='../'</script>";
    }else {
        if(isset($_GET['id'])){
            $id = mysqli_real_escape_string($conn, $_GET['id']);
            $sql = "INSERT INTO `featured` (`article`) VALUES ('$id')";
            if(mysqli_query($conn, $sql)){
                echo "<script>window.location='all.php'</script>";
            }else {
                echo "SEVERE ERROR";
            }
        }else {
            echo "<script>window.location='../'</script>";
        }
    }
?>
