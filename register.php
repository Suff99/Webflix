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
$identifier = "register";
require ('includes/database.php');
require('includes/nav.php');


createMeta("Register", "Register", "");


if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  require ('includes/database.php');
  $potentialErrors = array();
  $userName = validateGet('username', 'Please give a username', $potentialErrors, $link);
  $email = validateGet('email', 'Please give a email', $potentialErrors, $link);
  $first_name = validateGet('first_name', 'Please give first name', $potentialErrors, $link);
  $last_name = validateGet('last_name', 'Please give last name', $potentialErrors, $link);
  $password_validate = validateGet('password_validate', 'Please render your password', $potentialErrors, $link);
  $password = validateGet('password', 'Please give a password', $potentialErrors, $link);

  if ( empty( $potentialErrors ) )
  {
    $selectUser = "SELECT username FROM wf_users WHERE email='$email'" ;
    $userResults = @mysqli_query ( $link, $selectUser ) ;
    if ( mysqli_num_rows( $userResults ) != 0 ) {
      $potentialErrors[] = 'Email address already registered. <h1 href="login.php">Login</h2>';
    }
  }

  if ( empty( $potentialErrors ) )
  {
    $selectUserName = "SELECT username FROM wf_users WHERE username='$userName'" ;
    $result = @mysqli_query ( $link, $selectUserName) ;
    if(mysqli_num_rows($result) != 0){
      $potentialErrors[] = 'Username already registered. <h1 href="login.php">Login</h1>' ;
    }
  }

  if ( !empty($_POST[ 'password' ] ) )
  {
    if ( $_POST[ 'password' ] != $_POST[ 'password_validate' ] )
    { $potentialErrors[] = 'Passwords do not match.' ; }
    else
    { $password = mysqli_real_escape_string( $link, trim( $_POST[ 'password' ] ) ) ; }
  }
  else { $errors[] = 'Enter your password.' ; }

  if(empty($potentialErrors))
  {
    addUser($link, $email, $userName,  $first_name, $last_name, $password);
    //header("Refresh:0");
  }
  else 
  {
    echo '<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Error!</h4>' ;
    foreach ( $potentialErrors as $msg )
    { echo "- $msg<br>" ; }
    echo 'Please try again.</p></div>';
    mysqli_close( $link );
  } 

}

function addUser($link, $uEmail, $uUsername,  $uFirstName, $uLastName, $uPassword){
  $userInsert = "INSERT INTO wf_users (`email`, `username`, `first_name`, `last_name`, `password`, `registration`) VALUES ('$uEmail', '$uUsername', '$uFirstName', '$uLastName', SHA2('$uPassword',256), now());";
  $addUser = @mysqli_query ( $link, $userInsert ) ;
}

?>

<h1>Register</h1>

<style>
.card {display:inline-block;}
 </style>

<div class="container">
    <div class="col-sm">
<form action="register.php" method="post" class="alert-dismissible fade show" role="alert" >
  <div class="form-group">
    <label for="username">Username</label> 
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fa fa-at"></i>
        </div>
      </div> 
      <input id="username" name="username" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="first_name">First Name</label> 
    <input id="first_name" name="first_name" type="text" required="required" class="form-control" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>">
  </div>
  <div class="form-group">
    <label for="last_name">Last Name</label> 
    <input id="last_name" name="last_name" type="text" required="required" class="form-control" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>">
  </div>
  <div class="form-group">
    <label for="email">Email</label> 
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fa fa-envelope"></i>
        </div>
      </div> 
      <input id="email" name="email" placeholder="example@example.com" type="email" required="required" class="form-control" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="password">Password</label> 
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fa fa-key"></i>
        </div>
      </div> 
      <input id="password" name="password" placeholder="Password" type="password" required="required" class="form-control" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>">
    </div>
  </div> 

  <div class="form-group">
    <label for="password_valid">Re-Enter Password</label> 
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fa fa-key"></i>
        </div>
      </div> 
      <input id="password_validate" name="password_validate" placeholder="Validate Password" type="password" required="required" class="form-control" value="<?php if (isset($_POST['password_validate'])) echo $_POST['password_validate']; ?>">
    </div>
  </div> 

  <div class="form-group">
    <button name="submit" type="submit" class="btn btn-primary">Register</button>
  </div>
</form>
    </div>
</div>
<?php
 //$moviesQuery = "SELECT * FROM wf_releases order by RAND() LIMIT 4;";
 //$result = mysqli_query($link, $moviesQuery);
 // while ($movie = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
 //     createMovieCard($movie);
 // }
?>

</div>






<?php
require('includes/footer.php');
?>
