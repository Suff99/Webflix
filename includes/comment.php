<?php

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  require ('database.php');
  require ('util.php');
  $potentialErrors = array();
  $comment = confirmGetExistence('comment', $link);
  $rating = confirmGetExistence('rating',  $link);
  $user_id = confirmGetExistence('user_id', $link);
  $release_id = htmlspecialchars($_GET["release_id"]);

  if(empty($potentialErrors))
  {
    addComment($link, $comment, $rating, $user_id, $release_id);
    header('Location: '. '../release.php?id='.$release_id);
  }
  else 
  {
    echo '<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Error!</h4>' ;
    foreach ( $potentialErrors as $msg )
    { echo "- $msg<br>" ; }
    echo 'Please try again.</p></div>';
    mysqli_close( $link );
  } 

}
