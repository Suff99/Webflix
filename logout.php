<?php 

$identifier = 'logout';
$title = "Logged out";
$description = "You are logged out..";
require('includes/database.php');
require('includes/nav.php');

session();
if(!isset($_SESSION[ 'username' ])){
    header('Location: '. 'login.php');
}

$_SESSION = array() ;
session_destroy() ;
?>

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
  <img class="card-img" src="img/logo.png" alt="Logo" style="width:20%">
  </div>

<?php
echo '<center><h1>Goodbye!</h1><p>You are now logged out.</p><br><a href="login.php"> <button type="button" class="btn btn-primary" role="button"> Login </button></a>  </center>' ;
require('includes/footer.php');
?>