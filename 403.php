<!doctype html>
<html lang="en">
  <head>
<?php
require('includes/header.php');
?>
 </head>

    
<?php
$identifier = "home";
require ('includes/database.php');
require('includes/nav.php');


createMeta("Home", "Home", "");
?>


<body>
<h1>Error 403!</h1>

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> You do not have permission to access the intended page.</p><br>
    </div>
    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <a href="index.php"> <button type="button" class="btn btn-primary" role="button"> Take me away! </button></a>
</div>
</body>


<?php
require('includes/footer.php');
?>
