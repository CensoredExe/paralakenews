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
if($_SESSION['user_role']=='user'){
  echo "<script>window.location='../'</script>";
}
if(!isset($_GET['id'])){
    echo "<script>window.location='../'</script>";
    exit();
}else {
    $id = urlToID(mysqli_real_escape_string($conn, $_GET['id']));
}

$sql = "SELECT * FROM `articles` WHERE `id`='$id'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $title = $row['title'];
    $content = $row['content'];
    $url = $row['url'];
    $status = "pending";
    $image = $row['image'];
    $description = $row['description'];
    
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Journalist Page for ParalakeNews">
    <meta name="author" content="censored.exe">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Journalist - ParalakeNews</title>

    

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="../bootstrap/dashboard.css" rel="stylesheet">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/iv1kg2tsodpjhnb2lafrqr4llmz4im6ltrdsytogrr9t53u0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">ParalakeNews</a>
      
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="../">Home</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="index.php">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="create.php">
                  <span data-feather="file"></span>
                  Create Article
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="articles.php">
                  <span data-feather="shopping-cart"></span>
                  Your Articles
                </a>
              </li>
              
            </ul>

            <?php
            if($_SESSION['user_role']=='admin'){
              ?>
              <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Administration</span>
              
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="pending.php">
                  <span data-feather="file-text"></span>
                  Pending Posts 
                  <!-- <button style="font-size:12px; padding:1px 5px;" class="btn btn-danger">9</button> -->
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="all.php">
                  <span data-feather="file-text"></span>
                  All Posts
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="jobs.php">
                  <span data-feather="file-text"></span>
                  Jobs
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="users.php">
                  <span data-feather="users"></span>
                  Users
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="journalists.php">
                  <span data-feather="users"></span>
                  Journalists
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="categories.php">
                  <span data-feather="users"></span>
                  Categories
                </a>
              </li>
            </ul>
              <?php
            }
            ?>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Create a post</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              
            </div>
          </div>
          <!-- Content goes here -->
          <form method="POST" enctype="multipart/form-data">
          <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" id="title" aria-describedby="emailHelp" value="<?php echo $title; ?>" placeholder="title">
                
            </div>
            <div class="form-group">
                <label for="description">Description, appears as a preview (95 char max)</label>
                <input type="text" name="description" class="form-control" id="description" aria-describedby="emailHelp" value="<?php echo $description; ?>" placeholder="title">
                
            </div>
            <div class="form-group">
                <label for="url">URL, format like this test-title-example</label>
                <input type="text" name="url" class="form-control" id="url" aria-describedby="emailHelp" value="<?php echo $url; ?>" placeholder="title">
                
            </div>
            <div class="form-group">
              <label for="exampleFormControlSelect1">Category <span style="color:red;font-size:12px;">*CATEGORY DOESNT UPDATE, MANUALLY UPDATE WHILST EDITING*</span></label>
              <select name="cat" class="form-control" id="exampleFormControlSelect1">
                <?php 
                $sql = "SELECT * FROM `categories`";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                  ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content"><?php echo $content; ?></textarea>
                
            </div>
            <div class="form-group">
                <label for="exampleFormControlFile1">Banner (ideally landscape)</label>
                <input type="file" name="file" class="form-control-file" id="exampleFormControlFile1">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
          </form>
          <?php
            if(isset($_POST['submit'])){
              date_default_timezone_set("Europe/London");
              $cat = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['cat']));
              $description = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['description']));
              $title = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['title']));
              $content = mysqli_real_escape_string($conn, $_POST['content']);
              $url = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['url']));
              $status = "pending";
              $date = date("H:i:s d/m/y");
              
              if(empty($title) || empty($content) || empty($url) || empty($description)){
                echo "Error, empty fields";
                exit();
              }
              if(strlen($description)>95){
                echo "Error, description too long";
                exit();
              }


                $target_dir = "../uploads/";
                $target_file = $target_dir . basename($_FILES["file"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["file"]["tmp_name"]);
                if($check !== false) {
                    //echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["file"]["size"] > 500000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
                }

                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    //echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                    $file = basename( $_FILES["file"]["name"]);
                    
                    $sql = "UPDATE `articles` SET `title`='$title', `content`='$content', `date`='$date', `url`='$url', `status`='pending', `category`='$cat', `image`='$image' WHERE `id`='$id'";
                    if(mysqli_query($conn, $sql)){
                      echo "Article created.";
                    }else {
                      echo "Error.";
                      echo htmlspecialchars($sql);
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
                }
            }
          ?>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../assets/js/vendor/popper.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
    <!-- Tiny MCE -->
    <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      toolbar_mode: 'floating',
    });
  </script>
    
  </body>
</html>
