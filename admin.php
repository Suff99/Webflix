<!doctype html>
<html lang="en">
  <head>
<?php
require('includes/header.php');
?>
 </head>

    
<?php
$identifier = "admin";
require ('includes/database.php');
require('includes/nav.php');


createMeta("Admin Panel", "Admin Panel", "");
?>


<body>
    
<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Add Title</h5>
        <p class="card-text">Register a new title</p>
        <a href="#" class="btn btn-primary">Register Movie</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
      <h5 class="card-title">Add Title</h5>
        <p class="card-text">Register a new title</p>
        <a href="#" class="btn btn-primary">Register Movie</a>
      </div>
    </div>
  </div>
</div>

</body>





<?php
require('includes/footer.php');
?>
