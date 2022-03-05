<?php

include('database.php');
include('util.php');

$newRole = confirmGetExistence('role', $link);

session();

if ($_SESSION['user_id'] = "10") {
    changeRole($link, 10, $newRole);
    $SESSION['role'] = $newRole;
}


?>