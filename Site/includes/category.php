<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('database.php');
    require('util.php');
    $dialogMessage = array();
    $category = confirmGetExistence('category', $link);
    $description = confirmGetExistence('description', $link);

    if (!$category) {
        array_push($dialogMessage, "Please give a category name");
    }

    if (empty($dialogMessage)) {
        array_push($dialogMessage, "Added Category: $category");
        addCategory($link, $category, $description);
        header('Location: ' . '../admin.php?error=false&dialog=' . json_encode($dialogMessage));
    } else {
        header('Location: ' . '../admin.php?error=true&dialog=' . json_encode($dialogMessage));
    }
}
