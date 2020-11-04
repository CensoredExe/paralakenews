<?php
include_once "../includes/functions.php";
include "../includes/connection.php";
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
                <a class="nav-link" href="create.php">
                  <span data-feather="file"></span>
                  Create Article
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="articles.php">
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
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              
            </div>
          </div>
          <!-- Content goes here -->
          <h2>Your articles</h2>
          

          <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Date</th>
                <th scope="col">Views</th>
                <th scope="col">Status</th>
                <th scope="col">Edit</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $id = $_SESSION['user_id'];
            $sql = "SELECT * FROM `articles` WHERE `author`='$id' ORDER BY `id` DESC";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <th scope="row"><?php echo $row['id']; ?></th>
                    <td><a href="../article/?article=<?php echo $row['url']; ?>"><?php echo $row['title']; ?></a></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?Php echo getViews($row['id']); ?></td>
                    <td <?php if($row['status']=='active'){?> style="color: green;" <?php }else{ ?> style="color:orange;" <?php } ?>><?php echo $row['status']; ?></td>
                    <td><a href="edit.php?id=<?php echo $row['url']; ?>">EDIT</a></td>
                </tr>
                <?php
            }
            ?>
                
               
            </tbody>
        </table>


          
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
