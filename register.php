<!doctype html>
<html lang="en">

<head>
  <?php
  require('includes/header.php');
  ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  $identifier = "login";
  require('includes/database.php');
  require('includes/nav.php');


  createMetaTags("Register", "Register", "");


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('includes/database.php');
    $potentialErrors = array();
    $userName = validateGet('username', 'Please give a username', $potentialErrors, $link);
    $email = validateGet('email', 'Please give a email', $potentialErrors, $link);
    $first_name = validateGet('first_name', 'Please give first name', $potentialErrors, $link);
    $last_name = validateGet('last_name', 'Please give last name', $potentialErrors, $link);
    $password_validate = validateGet('password_validate', 'Please render your password', $potentialErrors, $link);
    $password = validateGet('password', 'Please give a password', $potentialErrors, $link);
    $dob = validateGet('dob', 'Please give DOB', $potentialErrors, $link);
    $contact = validateGet('contact', 'Please give Contact Number', $potentialErrors, $link);

    $userAge = calculateAge($dob);
    if ($userAge < 13) {
      $potentialErrors[] = 'You must be 13 and above to register.';
    }


    if (empty($potentialErrors)) {
      $selectUser = "SELECT username FROM wf_users WHERE email='$email'";
      $userResults = @mysqli_query($link, $selectUser);
      if (mysqli_num_rows($userResults) != 0) {
        $potentialErrors[] = 'Email address already registered. <h1 href="login.php">Login</h2>';
      }
    }

    if (empty($potentialErrors)) {
      $selectUserName = "SELECT username FROM wf_users WHERE username='$userName'";
      $result = @mysqli_query($link, $selectUserName);
      if (mysqli_num_rows($result) != 0) {
        $potentialErrors[] = 'Username already registered. <h1 href="login.php">Login</h1>';
      }
    }

    if (!empty($_POST['password'])) {
      if ($_POST['password'] != $_POST['password_validate']) {
        $potentialErrors[] = 'Passwords do not match.';
      } else {
        $password = mysqli_real_escape_string($link, trim($_POST['password']));
      }
    } else {
      $errors[] = 'Enter your password.';
    }

    if (empty($potentialErrors)) {
      addUser($link, $email, $userName,  $first_name, $last_name, $password, $dob, $contact);
      //header("Refresh:0");
    } else {
      echo '<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Error!</h4>';
      foreach ($potentialErrors as $msg) {
        echo "- $msg<br>";
      }
      echo 'Please try again.</p></div>';
      mysqli_close($link);
    }
  }

  function addUser($link, $uEmail, $uUsername,  $uFirstName, $uLastName, $uPassword, $uDob, $uContact)
  {
    $userInsert = "INSERT INTO wf_users (`email`, `username`, `first_name`, `last_name`, `password`, `registration`, `dob`, `contact_no`) VALUES ('$uEmail', '$uUsername', '$uFirstName', '$uLastName', SHA2('$uPassword',256), now(), STR_TO_DATE('$uDob','%d-%m-%Y'), '$uContact');";
    $addUser = @mysqli_query($link, $userInsert);
  }
  ?>


  <script>
  createDatePicker("#dob");
  </script>


  <div class="container">

    <h1>Register</h1>
    <form action="register.php" class="row g-3" method="post" class="alert-dismissible fade show" role="alert">
      <div class="col-md-4">
        <label for="validationDefault01" class="form-label">First name</label>
        <input id="first_name" name="first_name" type="text" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label for="validationDefault02" class="form-label">Last name</label>
        <input id="last_name" name="last_name" type="text" required="required" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label for="validationDefaultUsername" class="form-label">Username</label>
        <div class="input-group">
          <span class="input-group-text">@</span>
          <input type="text" class="form-control" name="username" id="username" aria-describedby="inputGroupPrepend2" required>
        </div>
      </div>

      <div class="col-md-4">
        <label for="validationDefault03" class="form-label">Date Of Birth</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
          <input id="dob" name="dob" type="text" class="form-control" aria-describedby="dobBlock" readonly="readonly" required>
        </div>
      </div>

      <div class="col-md-4">
        <label for="validationDefault03" class="form-label">Contact Number</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
          <input type="text" name="contact" id="contact" required="required" pattern="^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$" title="Please enter a valid UK Number beginning with 0" class="form-control">
        </div>
      </div>

      <div class="col-md-4">
        <label for="validationDefault03" class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-envelope"></i></span>
          <input id="email" name="email" placeholder="example@example.com" type="email" required="required" class="form-control" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
        </div>
      </div>

      <div class="col-md-7">
        <label for="validationDefault03" class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-envelope"></i></span>
          <input id="password" name="password" placeholder="Password" type="password" required="required" class="form-control" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>">
        </div>
      </div>

      <div class="col-md-7">
        <label for="validationDefault03" class="form-label">Password (Re-Enter)</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-envelope"></i></span>
          <input id="password_validate" name="password_validate" placeholder="Validate Password" type="password" required="required" class="form-control" value="<?php if (isset($_POST['password_validate'])) echo $_POST['password_validate']; ?>">
        </div>
      </div>

      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
          <label class="form-check-label" for="invalidCheck2">
            Agree to terms and conditions
          </label>
        </div>
      </div>
      <div class="col-12">
        <button name="submit" type="submit" class="btn btn-primary">Register</button>
      </div>
    </form>
  </div>








  <?php
  require('includes/footer.php');
  ?>