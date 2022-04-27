<?php

include('database.php');
include('util.php');

$search = confirmGetExistence('search', $link);

if (!$search) {
    giveAllTitles($link);
} else {
    searchFor($link, $search);
}


function searchFor($link, $value)
{
    $searchResult = "SELECT * FROM wf_releases where title LIKE '%$value%'";
    $result = mysqli_query($link, $searchResult);
    header('Content-Type: application/json; charset=utf-8');
    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }

    echo json_encode($rows);
}


function giveAllTitles($link)
{
    $searchResult = "SELECT * FROM wf_releases";
    $result = mysqli_query($link, $searchResult);
    header('Content-Type: application/json; charset=utf-8');

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }

    echo json_encode($rows);
}