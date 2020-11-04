<?php
    include "../includes/functions.php";
    include "../includes/connection.php";
    require '../steamauth/steamauth.php';
    if(isset($_SESSION['steamid'])) {
        include ('../steamauth/userInfo.php'); //To access the $steamprofile array
    }else {
      checkLogin();
    }
    if(!isset($_GET['id'])){
        echo "<script>window.location='../'</script>";
    }else {
        $catid = mysqli_real_escape_string($conn, $_GET['id']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paralake News - The latest news from the city of paralake!</title>
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
      <div class="col-4 pt-1 mobile-disappear">
        <a class="text-muted mobile-disappear" href="../jobs/">Jobs</a>
      </div>
      <div class="col-4 text-center">
        <a class="blog-header-logo text-dark" href="../">ParalakeNews</a>
      </div>
      <div class="col-4 d-flex justify-content-end align-items-center">
        <!-- <a class="text-muted" href="../search/" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
        </a> -->
        <?php
        if(isset($_SESSION['user_id'])){
          if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'journalist'){
            ?>
            <a class="btn btn-sm btn-outline-secondary mobile-disappear" style="margin-right:10px;" href="../journalist/">journalist</a>
            <?php 
          }
          ?>

            <a class="btn btn-sm btn-outline-secondary mobile-disappear" href="?logout">Logout</a>
          <?php
        }else {
          ?>
            <a class="btn btn-sm btn-outline-secondary mobile-disappear" href="?login">Steam Login</a>
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
<?php
  $sql = "SELECT * FROM `featured` ORDER BY `id` DESC LIMIT 1";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)){
    $article = $row['article'];
    $sql = "SELECT * FROM `articles` WHERE `id`='$article'";
    $result2 = mysqli_query($conn, $sql);
    while($row2 = mysqli_fetch_assoc($result2)){
      $image = $row2['image'];
      $title = $row2['title'];
      $cat = $row2['category'];
      $description = $row2['description'];
      $url = $row2['url'];
    }
    ?>
    <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark featured-post">
    <div class="col-md-6 px-0">
        
      <h1 class="display-4 font-italic"><?php echo $title; ?></h1>
      <strong class="d-inline-block mb-2 text-danger" style="color: <?php echo getCatColor($cat); ?> !important;"><?php echo getCat($cat); ?></strong>
      <p class="lead my-3"><?php echo $description; ?></p>
      <p class="lead mb-0"><a href="../article/?article=<?php echo $url; ?>" class="text-white font-weight-bold">Continue reading...</a></p>
    </div>
  </div>
  <style>
  .featured-post {
    background-image:linear-gradient(to right, rgba(0, 0, 0, 0.644), rgba(0, 0, 0, 0.267)), url("../uploads/<?php echo $image; ?>");
    background-size: cover;
    background-position: center center;
}
</style>
    <?php
  }
?>
  
  
  <div class="row mb-2">
      
  <?php 
  $sql = "SELECT * FROM `articles` WHERE `status`='active' AND `category`='$catid' ORDER BY `id` DESC LIMIT 100";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)){
    ?>
    <div class="col-md-6">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-success" style="color: <?php echo getCatColor($row['category']); ?> !important;"><?php echo getCat($row['category']); ?></strong>
            <h3 class="mb-0"><?php echo $row['title']; ?></h3>
            <div class="mb-1 text-muted"><?php echo $row['date']; ?></div>
            <p class="card-text mb-auto"><?php echo $row['description']; ?></p>
            <a href="../article/?article=<?php echo $row['url']; ?>" class="stretched-link">Continue reading</a>
          </div> 
          
        </div>
      </div>
    <?php
  }
  ?>
    
    
  </div>
</div>
<main role="main" class="container">
  <div class="row">
    
    
  </div><!-- /.row -->
      
</main><!-- /.container -->

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