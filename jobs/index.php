<?php
    include "../includes/functions.php";
    include "../includes/connection.php";
    require '../steamauth/steamauth.php';
    if(isset($_SESSION['steamid'])) {
        include ('../steamauth/userInfo.php'); //To access the $steamprofile array
    }else {
      checkLogin();
    }
    
    if(isset($_GET['article'])){
      $id = mysqli_real_escape_string($conn, $_GET['article']);
      $sql = "SELECT * FROM `articles` WHERE `url`='$id'";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)>0){
        //Article exists
        while($row = mysqli_fetch_assoc($result)){
          $titlemeta = $row['title'];
          $descriptionmeta = $row['description'];
        }
      }else {
        $titlemeta = "Article not found";
        $descriptionmeta = "Article not found";
      }
    }else {
        $titlemeta = "Article not found";
        $descriptionmeta = "Article not found";
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paralake News - <?php echo $titlemeta; ?></title>
    <meta name="description" content="<?php echo $descriptionmeta; ?>">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/4.5/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/4.5/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/4.5/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon.ico">
    <meta name="msapplication-config" content="/docs/4.5/assets/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">


      <style>
        .bd-placeholder-img {
          font-size: 1.125rem;
          text-anchor: middle;
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          user-select: none;
        }

        @media (min-width: 768px) {
          .bd-placeholder-img-lg {
            font-size: 3.5rem;
          }
        }
      </style>
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="../bootstrap/blog.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/dist/style.min.css">
</head>
<body>
<div class="container">
  <header class="blog-header py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4 pt-1">
        <a class="text-muted" href="../jobs/">Jobs</a>
      </div>
      <div class="col-4 text-center">
        <a class="blog-header-logo text-dark" href="../">ParalakeNews</a>
      </div>
      <div class="col-4 d-flex justify-content-end align-items-center">
        <a class="text-muted" href="../search/" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
        </a>
        <?php
        if(isset($_SESSION['user_id'])){
          if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'journalist'){
            ?>
            <a class="btn btn-sm btn-outline-secondary" style="margin-right:10px;" href="../journalist/">journalist</a>
            <?php 
          }
          ?>

            <a class="btn btn-sm btn-outline-secondary" href="?logout">Logout</a>
          <?php
        }else {
          ?>
            <a class="btn btn-sm btn-outline-secondary" href="?login">Steam Login</a>
          <?php
        }
        ?>
        
      </div>
    </div>
  </header>
  <div class="nav-scroller py-1 mb-2">
    <nav class="nav d-flex justify-content-between">
    <?php
    $sql = "SELECT * FROM `categories`";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
      ?>
      <a class="p-2 text-muted" href="../cat/?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
      <?php
    }
    ?>
    </nav>
  </div>
  
  <strong class="d-inline-block mb-2 text-danger" style="color: red !important">Jobs</strong>
  <h1 class="display-4 font-italic">Apply to join ParalakeNews</h1>
  <div style="width:100%;display:block;">
  </div>
  
  

<main role="main" class="container" style="margin-top:20px;">
  <div class="row" style="padding-top:40px;">
      
    <div class="col-md-8 blog-main">
      
      <div class="blog-post">
          <?php
            $sql = "SELECT * FROM `jobs` ORDER BY `id` DESC";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                ?>
                <div class="job" style="margin-bottom: 20px;">
                    <h3><?php echo $row['name'] ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <a target="_blank" href="<?php echo $row['link']; ?>">Apply</a>
                </div>
                <?php
            }
          ?>
        
      </div><!-- /.blog-post -->

      
     

    </div><!-- /.blog-main -->



    <aside class="col-md-4 blog-sidebar">
      <div class="p-4 mb-3 bg-light rounded">
        <h4 class="font-italic">About</h4>
        <p class="mb-0">ParalakeNews is a community ran news website that is designed to keep the community in the loop!</p>
      </div>

      <div class="p-4">
        <h4 class="font-italic">Elsewhere</h4>
        <ol class="list-unstyled">
          <li><a href="#">Twitter</a></li>
          <li><a href="#">Forums</a></li>
          <li><a href="#">Apply</a></li>
        </ol>
      </div>
    </aside><!-- /.blog-sidebar -->
    
        
        
  </div><!-- /.row -->
<!-- Comments -->



<br>

</main><!-- /.container -->
</div>
<footer class="blog-footer">
  <p>News site built for the <a href="https://perpheads.com/">perpheads</a> community by <a href="https://perpheads.com/members/censoredexe.4585/">@censoredexe</a>.</p>
  <p>
    <a href="#">Back to top</a>
  </p>
</footer>
  
    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>