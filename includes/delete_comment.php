<?php

  require ('database.php');
  require ('util.php');

  session();

  $comment_id = htmlspecialchars($_GET["comment"]);
  $user_id = $_SESSION['user_id'];
  $release_id = htmlspecialchars($_GET["release"]);

  deleteComment($link, $comment_id, $user_id);
  header('Location: '. '../release.php?id='.$release_id);

