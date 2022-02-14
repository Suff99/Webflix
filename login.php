<!doctype html>
<html lang="en">
  <head>
<?php
require('includes/header.php');
?>
 </head>
<?php
$identifier = "login";
require ('includes/database.php');
require('includes/nav.php');
require_once('includes/util.php');


if(isset($_SESSION[ 'username' ])){
    header('Location: '. 'index.php');
}

createMeta("Login", "Login", "");

function validate($link, $email = '', $pwd = '') {
        $errors = array();
        if (empty($email)) {
            $errors[] = 'Enter your email address.';
        } else {
            $e = mysqli_real_escape_string($link, trim($email));
        }
        if (empty($pwd)) {
            $errors[] = 'Enter your password.';
        } else {
            $p = mysqli_real_escape_string($link, trim($pwd));
        }
        if (empty($errors)) {
            $q = "SELECT * FROM wf_users WHERE `email`='$e' AND `password`=SHA2('$p',256)";
            $r = mysqli_query($link, $q);
            if (@mysqli_num_rows($r) == 1) {
                $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
                return array(true, $row);
            }
            else {
                $errors[] = 'Email address and password not found.';
            }
        }
        return array(false, $errors);
}
    

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  require ( 'includes/database.php' ) ;
  $prev_url = "index.php";
  if(isset($_SERVER['HTTP_REFERER'])){
      $prev_url = $_SERVER['HTTP_REFERER'];
  }
  list ( $check, $data ) = validate ( $link, $_POST[ 'email' ], $_POST[ 'password' ] ) ;
  if ( $check )  
  {
    if (@session_id() == "") @session_start();
    $_SESSION[ 'user_id' ] = $data[ 'user_id' ] ;
    $_SESSION[ 'first_name' ] = $data[ 'first_name' ] ;
    $_SESSION[ 'last_name' ] = $data[ 'last_name' ] ;
    $_SESSION[ 'role' ] = $data[ 'role' ] ;
    $_SESSION[ 'username' ] = $data[ 'username' ] ;
    header('Location: '. $prev_url);
  }
  else {
       $errors = $data; 
       echo '<div class="alert alert-warning" role="alert">
       <h4 class="alert-heading">Error!</h4>' ;
       foreach ( $errors as $msg )
       { echo "- $msg<br>" ; }
       echo 'Please try again.</p></div>';
       mysqli_close( $link );
    } 

  mysqli_close( $link ) ; 
}

?>

<body class="d-flex flex-column min-vh-100">    

<h1>Login</h1>

<style>
.card {display:inline-block;}
 </style>

<div class="container">
    <div class="row">
        <div class="col-sm">
        <form action="login.php" method="post" class="alert-dismissible fade show" role="alert" >
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" name="email" placeholder="example@emample.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary" value="Login" >Sign in</button>
            <button type="button" class="btn btn-primary" onclick="window.location.href='register.php';" >Register</button>
        </form>
          </div>
    </div>
</div>







<?php
require('includes/footer.php');
?>
