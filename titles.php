<!doctype html>
<html lang="en">
  <head>
<?php 
require('includes/header.php');
$identifier = 'whatson';
?>
 </head>

<?php
require('includes/database.php');
require('includes/nav.php');


$currentPage = 0; 

if (!isset ($_GET['page']) ) {  
  $currentPage = 1;  
} else {  
  $currentPage = $_GET['page'];  
}  

$moviesQuery = "SELECT * FROM wf_releases";

if (isset ($_GET['type']) ) {  

    $type = $_GET['type'];
    if(strcmp($type, "movies") == 0){
      $moviesQuery = "SELECT * FROM wf_releases where release_type = 'movie'";
    } 

    if(strcmp($type, "series") == 0){
      $moviesQuery = "SELECT * FROM wf_releases where release_type = 'tv'";
    } 
}  



$movies = mysqli_query($link, $moviesQuery);
$number_of_result = mysqli_num_rows($movies);  
$results_per_page = 20;  
$number_of_page = ceil ($number_of_result / $results_per_page); 
$page_first_result = ($currentPage-1) * $results_per_page;  

   $query = $moviesQuery . " LIMIT " . $page_first_result . ',' . $results_per_page;  
   $result = mysqli_query($link, $query);   

?>
<h1>What's on?</h1>
<div class="container-fluid">

<div class="row">
<?php
   while ($row = mysqli_fetch_array($result)) {  
    createMovieCard($row);
}  

?>
  </div>
</div>


<br><br><br>
<nav class="row g-0 text-center justify-content-center align-items-center mx-0 px-0">
  <ul class="pagination">
<?php
for($page = 1; $page<= $number_of_page; $page++) {  
  echo '<li class="page-item' . (($page== $currentPage)?' active':"") . '"><a class="page-link" href="titles.php?page=' .$page.'&type='.$_GET['type'].'">' .$page.'</a></li>';
} 
?>
 </ul>
</nav>

<?php
require('includes/footer.php');
?>
