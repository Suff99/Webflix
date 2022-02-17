<?php


  require ('database.php');
  require ('util.php');
  $potentialErrors = array();
  $category_id = htmlspecialchars($_GET["category_id"]);

  if(empty($potentialErrors))
  {
    deleteCategory($link, $category_id);
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

