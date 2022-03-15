<?php


require('database.php');
require('util.php');
session();
checkForAdmin();

$dialogMessage = array();
$release = confirmGetExistence('release', $link);

if (!$release) {
    array_push($dialogMessage, "Missing release id!");
}

if (empty($dialogMessage)) {
    array_push($dialogMessage, "Deleted Title:" . getTitleFromId($link, $release)['title']);
    deleteTitle($link, $release);
    header('Location: ' . '../admin.php?error=false&dialog=' . json_encode($dialogMessage));
} else {
    header('Location: ' . '../admin.php?error=true&dialog=' . json_encode($dialogMessage));
}
