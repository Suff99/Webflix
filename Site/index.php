<!doctype html>
<html lang="en">


<head>
<?php
$identifier = "home";
require('includes/database.php');
require('includes/nav.php');
createMetaTags("Home", "Home", "");
?>
</head>

<body class="d-flex flex-column min-vh-100">
  <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> Home to over <?php echo noNewReleases($link) ?> releases! <br>
      Anytime - Anywhere - Any Device </p>
  </div>

<?php
require('includes/footer.php');
?>