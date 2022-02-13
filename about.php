<!doctype html>
<html lang="en">
  <head>
<?php
require('includes/header.php');
?>
 </head>

  <body class="d-flex flex-column min-vh-100">
  <div class="header">
    <?php
$identifier = "about";
require ('includes/database.php');
require('includes/nav.php');


createMeta("About", "About", "");
?>
  <h1>About Webflix</h1>

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> Under Construction </p>
</div>

<?php

//addMovies($link, 10);

function addMovies($link, $pages){
  for ($x = 1; $x <= $pages; $x++) {
    $url = file_get_contents("https://api.themoviedb.org/3/movie/popular?api_key=5cd5948d48817e54d6fb43905f56a80f&page=".$x);  
    $data  = json_decode($url);
   
  
    foreach($data->results as $key=>$value)
    {
      $youtube_id = "";
      $youtubeDate = file_get_contents("https://api.themoviedb.org/3/movie/".$value->id."/videos?api_key=5cd5948d48817e54d6fb43905f56a80f");
      $tvShowData = file_get_contents("https://api.themoviedb.org/3/movie/".$value->id."?api_key=5cd5948d48817e54d6fb43905f56a80f");
      
      $videos  = json_decode($youtubeDate);
      $tvShow  = json_decode($tvShowData);
  
      foreach($videos->results as $vkey=>$vid){ 
          if($vid->site == "YouTube"){
              $youtube_id = $vid->key;
          }
      } 
      if($youtube_id != ""){
      addRelease($link, $value->release_date, $value->original_title, $value->overview, $youtube_id, $value->vote_average, $value->id, "Movie", $tvShow->homepage);
    } else {
        printf("<p>Skipped $value->original_title</p>");
      }
    }
  }
}

function addTvsShows($link ,$pages){
  for ($x = 1; $x <= $pages; $x++) {
    $url = file_get_contents("https://api.themoviedb.org/3/tv/popular?api_key=5cd5948d48817e54d6fb43905f56a80f&page=".$x);  
    $data  = json_decode($url);
   
  
    foreach($data->results as $key=>$value)
    {
      $youtube_id = "";
      $youtubeDate = file_get_contents("https://api.themoviedb.org/3/tv/".$value->id."/videos?api_key=5cd5948d48817e54d6fb43905f56a80f");
      $tvShowData = file_get_contents("https://api.themoviedb.org/3/tv/".$value->id."?api_key=5cd5948d48817e54d6fb43905f56a80f");
      
      $videos  = json_decode($youtubeDate);
      $tvShow  = json_decode($tvShowData);
  
      foreach($videos->results as $vkey=>$vid){ 
          if($vid->site == "YouTube"){
              $youtube_id = $vid->key;
          }
      } 
      if($youtube_id != ""){
      addRelease($link, $value->first_air_date, $value->name, $value->overview, $youtube_id, $value->vote_average, $value->id, "TV Series", $tvShow->homepage);
      printf("<p>Added $value->name</p>");
    } else {
        printf("<p>Skipped $value->name</p>");
      }
    }
  }
}

require('includes/footer.php');
?>
