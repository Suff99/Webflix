<!doctype html>
<html lang="en">
<?php
$pageIdentifier = "Sign in";
require('includes/database.php');
require('includes/nav.php');
require_once('includes/util.php');


if (isset($_SESSION['username'])) {
    header('Location: ' . 'index.php');
}

createMetaTags("Sign in", "Sign in", "");

function validate($link, $email = '', $pwd = '')
{
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
        } else {
            $errors[] = 'Email address and password not found.';
        }
    }
    return array(false, $errors);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('includes/database.php');
    $prev_url = "index.php";
    if (isset($_SERVER['HTTP_REFERER'])) {
        $prev_url = $_SERVER['HTTP_REFERER'];
    }
    list($check, $data) = validate($link, $_POST['email'], $_POST['password']);
    if ($check) {
        session();
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['first_name'] = $data['first_name'];
        $_SESSION['last_name'] = $data['last_name'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['status'] = $data['status'];
        clearResetCode($link, $data['email']);
        header('Location: ' . $prev_url);
    } else {
        header('Location: ' . $prev_url . "?dialog=" . json_encode(array('Your details are incorrect. Please try again.')) . "&error=true");
    }
}

?>

<body class="d-flex flex-column min-vh-100">

<style>
    .card {
        display: inline-block;
    }
</style>

<div class="container">

    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
        <p>
        <h1>Sign in</h1>
        </p>
    </div>

    <div class="row">
        <div class="col-sm">
            <form action="login.php" method="post" class="alert-dismissible fade show" role="alert">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="example@emample.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <button name="login" type="submit" class="btn btn-primary">Sign in</button>
                <button type="button" class="btn btn-primary" onclick="window.location.href='register.php';">Register
                </button>
            </form>
        </div>
    </div>
</div>


<?php
require('includes/footer.php');
?>