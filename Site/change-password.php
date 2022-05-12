<?php

if (isset($_SESSION['username'])) {
    $dialogMessage = array("You are already logged in");
    header('Location: ' . 'index.php?error=false&dialog=' . json_encode($dialogMessage));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = confirmGetExistence('email', $link);

    if($email){

    }

}


    


?>