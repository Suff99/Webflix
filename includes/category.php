<?php

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  require ('database.php');
  require ('util.php');
  $potentialErrors = array();
  $category = validateGet('category', 'Please give a name', $potentialErrors, $link);
  $description = validateGet('description', 'Please give a description', $potentialErrors, $link);

  if(empty($potentialErrors))
  {
    addCategory($link, $category, $description);
    header('Location: '. '../admin.php');
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
