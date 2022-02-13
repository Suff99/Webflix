<!doctype html>
<html lang="en">
  <head>
<?php
require('includes/header.php');
?>
 </head>

  <body class="d-flex flex-column min-vh-100">
  <div class="header">
      <?php
$identifier = "admin";
require ('includes/database.php');
require('includes/nav.php');

session_start();
createMeta("Admin Panel", "Create and Edit", "");
?>
  <h1>Admin Panel</h1>



  <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> Under Construction </p>
</div>




<?php
require('includes/footer.php');
?>
