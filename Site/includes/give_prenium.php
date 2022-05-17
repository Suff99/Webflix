<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require('database.php');
    require('util.php');

    session();
    $user_id = $_SESSION['user_id'];

    $giveQuery = "UPDATE `wf_users` SET `status`='paid' WHERE `user_id` = $user_id";
    $execute = mysqli_query($link, $giveQuery);
    $_SESSION['status'] = 'paid';

    $card_number = confirmGetExistence('card_number', $link);
    $card_exp = confirmGetExistence('exp_date', $link);
    $card_cvv = confirmGetExistence('cvc', $link);


    $changeBanking = "UPDATE `wf_users` SET `card_number`='$card_number',`card_exp`='$card_exp',`card_cvv`='$card_cvv' WHERE `user_id` = $user_id";
    $execute = mysqli_query($link, $changeBanking);



    header("Location: ../titles.php"  . "?dialog=" . json_encode(array('You now have premium.')) . "&error=false");
}
