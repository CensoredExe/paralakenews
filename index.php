<?php
    include "includes/connection.php";
    require 'steamauth/steamauth.php';
    if(isset($_SESSION['steamid'])) {
        include ('steamauth/userInfo.php'); //To access the $steamprofile array
    }   
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paralake News - The latest news from the city of paralake!</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
     <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dist/style.min.css">
    <!-- Text area -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({selector:'textarea'});</script>
</head>
<body>
<nav>
    <div class="nav-wrapper">
        <div class="row">
        <a href="#" class="brand-logo">Paralake News</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            
            <li>
                <form>
                    <div class="input-field">
                        <input style="width:600px !important;" id="search" type="search" required>
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
            </li>
            <li><?php
                if(isset($_SESSION['user_id'])){
                    // Logged in
                    ?>
                    <a href="?logout">Logout</a>
                    <?php
                }else {
                    ?>
                    <a href="?login">Login</a>
                    <?php

                }
            ?></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="row">
    <?php
    echo $steamprofile['steamid'];
    ?>
  </div>
  
</body>
</html>