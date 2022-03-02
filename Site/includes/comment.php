<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require('database.php');
  require('util.php');

  session();

  $dialogMessage = array();

  if (!isset($_SESSION['username'])) {
    array_push($dialogMessage, "You must be logged in to perform this action.");
    header('Location: ' . '../login.php?error=true&dialog=' . json_encode($dialogMessage));
  }

  $comment = confirmGetExistence('comment', $link);
  $rating = confirmGetExistence('rating',  $link);
  $user_id = $_SESSION['user_id'];
  $release_id = htmlspecialchars($_GET["release_id"]);

  if (!$comment) {
    array_push($dialogMessage, "Missing comment text!");
  }

  if (!$rating) {
    array_push($dialogMessage, "Missing rating!!");
  }

  if (!$release_id) {
    array_push($dialogMessage, "Missing release id!");
  }


  if (empty($potentialErrors)) {
    addComment($link, $comment, $rating, $user_id, $release_id);
    header('Location: ' . '../release.php?id=' . $release_id);
  } else {
    header('Location: ' . '../release.php?id=' . $release_id . "&dialog=" . json_encode($potentialErrors) . "&error=true");
  }
}
