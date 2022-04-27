<?php

include('../includes/database.php');
require('../includes/util.php');
require('email.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$resetCode = generateRandomString();

$dialogMessage = [];

$email = "craigtbalmer@gmail.com";

$message = "
<html>
<head>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.css'>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.js'></script>
</head>
<body>

<center><img class='card-img' src='https://craig.software/webflix/img/logo.png' alt='Logo' style='width:20%'><br>

<h1>Password Reset</h1>

<p>If you forgot your password, use the link below to reset it</p>
<p>If you did not request a password reset, please ignore this email</p>
<a href='https://craig.software/webflix/reset-password.php?email=$email&code=$resetCode'> <button type='button' role='button' class='btn btn-primary'><h1>Reset your password</h1></button></a>
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
    createSaveResetCode($link, $resetCode, $email);
    header('Location: ' . '../login.php?error=false&dialog=' . json_encode($dialogMessage));
} else {
    $dialogMessage = array("Account does not exist with that email!");
    header('Location: ' . '../login.php?error=false&dialog=' . json_encode($dialogMessage));
}


function createSaveResetCode($link, $code, $email)
{
    $query = "UPDATE `wf_users` SET `password_reset`='$code' WHERE `email` = '$email';";
    $result = mysqli_query($link, $query);
}

