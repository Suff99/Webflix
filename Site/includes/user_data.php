<?php

include('database.php');
include('util.php');

session();
if (!isset($_SESSION['username'])) {
    $dialogMessage = [];
    array_push($dialogMessage, "You must be logged in to perform this action.");
    header('Location: ' . '../login.php?error=true&dialog=' . json_encode($dialogMessage));
}

function query_to_csv($db_conn, $query, $filename, $attachment = false, $headers = true)
{

    if ($attachment) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename);
        $fp = fopen('php://output', 'w');
    } else {
        $fp = fopen($filename, 'w');
    }

    $result = mysqli_query($db_conn, $query) or die(mysqli_error($db_conn));

    if ($headers) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            fputcsv($fp, array_keys($row));
            mysqli_data_seek($result, 0);
        }
    }

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($fp, $row);
    }

    fclose($fp);
}

// Using the function
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM `wf_users` WHERE `user_id`='$user_id'";

query_to_csv($link, $sql, $_SESSION['username'] . ".csv", true);
?>