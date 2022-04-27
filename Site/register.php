<!doctype html>
<html lang="en">

<body class="d-flex flex-column min-vh-100">


<?php
$identifier = "Sign in";
require('includes/database.php');
require('includes/nav.php');


createMetaTags("Register", "Register", "");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('includes/database.php');
    $potentialErrors = array();
    $userName = confirmGetExistence('username', $link);
    $email = confirmGetExistence('email', $link);
    $first_name = confirmGetExistence('first_name', $link);
    $last_name = confirmGetExistence('last_name', $link);
    $password_validate = confirmGetExistence('password_validate', $link);
    $password = confirmGetExistence('password', $link);
    $dob = confirmGetExistence('dob', $link);
    $contact = confirmGetExistence('contact', $link);

    if (!$contact) {
        $potentialErrors[] = 'Please give a valid mobile number';
    }

    if (!$dob) {
        $potentialErrors[] = 'Please give a date of birth';
    }

    if (!$password) {
        $potentialErrors[] = 'Please give a password';
    }

    if (!$password_validate) {
        $potentialErrors[] = 'Please re-enter password for confirmation';
    }

    if (!$email) {
        $potentialErrors[] = 'Please enter your email';
    }

    if (!$userName) {
        $potentialErrors[] = 'Please give a username';
    }

    if (!$first_name || !$last_name) {
        $potentialErrors[] = 'Please enter both name fields';
    }

    $userAge = calculateAge($dob);
    if ($userAge < 18) {
        $potentialErrors[] = 'You must be 18 and above to register.';
    }


    $selectUser = "SELECT username FROM wf_users WHERE email='$email'";
    $userResults = @mysqli_query($link, $selectUser);
    if (mysqli_num_rows($userResults) != 0) {
        $potentialErrors[] = 'Email address already registered. <h1 href="login.php">Sign in</h2>';
    }


    $selectUserName = "SELECT username FROM wf_users WHERE username='$userName'";
    $result = @mysqli_query($link, $selectUserName);
    if (mysqli_num_rows($result) != 0) {
        $potentialErrors[] = 'Username already registered. <h1 href="login.php">Sign in</h1>';
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
        $potentialErrors[] = "Added User! You can now Sign in!";
        addUser($link, $email, $userName, $first_name, $last_name, $password, $dob, $contact);
        header('Location: ' . "login.php?&dialog=" . json_encode($potentialErrors));
    } else {
        header('Location: ' . "register.php?&error=true&dialog=" . json_encode($potentialErrors));
    }
}

function addUser($link, $uEmail, $uUsername, $uFirstName, $uLastName, $uPassword, $uDob, $uContact)
{
    $userInsert = "INSERT INTO wf_users (`email`, `username`, `first_name`, `last_name`, `password`, `registration`, `dob`, `contact_no`) VALUES ('$uEmail', '$uUsername', '$uFirstName', '$uLastName', SHA2('$uPassword',256), now(), STR_TO_DATE('$uDob','%d-%m-%Y'), '$uContact');";
    $addUser = @mysqli_query($link, $userInsert);
}

?>


<script>
    createDatePicker("#dob");
</script>


<div class="container">
    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    </div>
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
                <input type="text" class="form-control" name="username" id="username"
                       aria-describedby="inputGroupPrepend2" pattern="^[a-z0-9_-]{3,16}$"
                       title="Only letters, Numbers and underscores accepted" required>
            </div>
        </div>

        <div class="col-md-4">
            <label for="validationDefault03" class="form-label">Date Of Birth</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                <input id="dob" name="dob" type="text" class="form-control" aria-describedby="dobBlock"
                       readonly="readonly" required>
            </div>
        </div>

        <div class="col-md-4">
            <label for="validationDefault03" class="form-label">Contact Number</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                <input type="text" name="contact" id="contact" required="required"
                       pattern="^(((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$"
                       title="Please enter a valid UK Number beginning with 0 or +44" class="form-control">
            </div>
        </div>

        <div class="col-md-4">
            <label for="validationDefault03" class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                <input id="email" name="email" placeholder="example@example.com" type="email" required="required"
                       class="form-control" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
            </div>
        </div>

        <div class="col-md-4">
            <label for="validationDefault03" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                <input id="password" name="password" placeholder="Password" type="password" required="required"
                       class="form-control" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>">
            </div>
        </div>

        <div class="col-md-4">
            <label for="validationDefault03" class="form-label">Password (Re-Enter)</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                <input id="password_validate" name="password_validate" placeholder="Validate Password"
                       type="password" required="required" class="form-control"
                       value="<?php if (isset($_POST['password_validate'])) echo $_POST['password_validate']; ?>">
            </div>
        </div>

        <div class="col-12">
            <button name="register" type="submit" class="btn btn-primary">Register</button>
        </div>
    </form>
</div>


<?php
require('includes/footer.php');
?>