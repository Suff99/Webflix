<?php


require('database.php');
require('util.php');
$dialogMessage = array();
$category_id = confirmGetExistence('category_id', $link);

if (!$category_id) {
    array_push($dialogMessage, "Missing category id!");
}

if (empty($dialogMessage)) {
    array_push($dialogMessage, "Deleted Category: " . getCategoryFromId($link, $category_id)['name']);
    deleteCategory($link, $category_id);
    header('Location: ' . '../admin.php?error=false&dialog=' . json_encode($dialogMessage));
} else {
    header('Location: ' . '../admin.php?error=true&dialog=' . json_encode($dialogMessage));
}
