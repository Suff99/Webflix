<?php 

$identifier = 'logout';
$title = "Logged out";
$description = "You are logged out..";
require('includes/header.php');
require('includes/database.php');
require('includes/nav.php');

if (@session_id() == "") @session_start();
if(!isset($_SESSION[ 'username' ])){
    header('Location: '. 'login.php');
}

$_SESSION = array() ;
session_destroy() ;

echo '<center><h1>Goodbye!</h1><p>You are now logged out.</p><br><a href="login.php"> <button type="button" class="btn btn-primary" role="button"> Login </button></a>  </center>' ;
require('includes/footer.php');
?>
