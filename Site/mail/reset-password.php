<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include('../includes/database.php');
    require('../includes/util.php');
    require('email.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $resetCode = genRanCode();

    $dialogMessage = [];

    if (isset($_SESSION['username'])) {
        $dialogMessage = array("You are already logged in.");
        header('Location: ' . '../index.php?error=true&dialog=' . json_encode($dialogMessage));
        die();
    }


    $email = confirmGetExistence('email', $link);
    $emailLookup = "SELECT * FROM `wf_users` WHERE `email` = '$email'";
    $result = mysqli_query($link, $emailLookup);



    if ($result->num_rows == 0) {
        $dialogMessage = array("Account does not exist with that email!");
        header('Location: ' . '../login.php?error=true&dialog=' . json_encode($dialogMessage));
    }

    while ($row = mysqli_fetch_array($result)) {


        $message = "
<html>
<body>

<center><img class='card-img' src='https://craig.software/webflix/img/logo.png' alt='Logo' style='width:20%'><br>

<h1>Password Reset</h1>
<p>Hi " . $row['username'] . "</p>
<p>You have recieved this email because you have forgot your password</p>
<p>Your new password is: $resetCode</p>
<p>If you did not request a password reset, please ignore this email</p>
<a href='https://craig.software/webflix/login.php'> <button type='button' role='button' class='btn btn-primary'><h1>Login</h1></button></a>
<br></center>
</body>
</html>
";


        $subject = "Webflix - Password Reset";
        $headers = "From: Webflix <no-reply@webflix.com>\r\n";
        $headers .= "Reply-To: no-reply@webflix.com\r\n";
        $headers .= "Content-type: text/html\r\n";


        $sent = sendEmail($email, $subject, $message, $headers);

        if ($sent) {
            $dialogMessage = array("Email sent! Please check your spam folder!");
            $query = "UPDATE `wf_users` SET `password`=SHA2('$resetCode',256) WHERE `email` = '$email';";
            $result = mysqli_query($link, $query);
            header('Location: ' . '../login.php?error=false&dialog=' . json_encode($dialogMessage));
        }
    }
}
