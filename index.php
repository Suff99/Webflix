<!doctype html>
<html lang="en">

<head>
  <?php
  require('includes/header.php');
  ?>
</head>


<?php
$identifier = "home";
require('includes/database.php');
require('includes/nav.php');


createMetaTags("Home", "Home", "");
?>


<body class="d-flex flex-column min-vh-100">

<div class="card bg-dark text-white">
  <img class="card-img" src="https://image.tmdb.org/t/p/w300_and_h450_bestv2/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg" alt="Card image">
  <div class="card-img-overlay">
    <h5 class="card-title">Spiderman</h5>
    <p class="card-text">Spiderman</p>
  </div>
</div>

  <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> There are currently <?php echo noNewReleases($link) ?> releases on the platform </p>
  </div>


</body>





<?php
require('includes/footer.php');
?>