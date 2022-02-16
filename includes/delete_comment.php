<?php

  require ('database.php');
  require ('util.php');

  if (@session_id() == "") @session_start();

  printf("HELLO ALL");

  $comment_id = htmlspecialchars($_GET["comment"]);
  $user_id = $_SESSION['user_id'];
  $release_id = htmlspecialchars($_GET["release"]);

  printf("test" . $comment_id . " " . $user_id . " " . $release_id);
  deleteComment($link, $comment_id, $user_id);
  header('Location: '. '../release.php?id='.$release_id);


function deleteComment($link, $comm, $user){
  $deleteQuery = "DELETE FROM wf_comments WHERE `comment_id` = $comm AND `user_id` = $user";
  $result = @mysqli_query ( $link, $deleteQuery ) ;
}

?>