<!doctype html>
<html lang="en">
  <head>
<?php
require('includes/header.php');
?>
 </head>


    <?php
$identifier = "about";
require ('includes/database.php');
require('includes/nav.php');


createMeta("About", "About", "");
?>

<body class="d-flex flex-column min-vh-100">
<h1>About Webflix</h1>

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> Under Construction </p>
</div>

<?php

//addMovies($link, 20);
//addTvsShows($link, 20);

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

      $poster = getPoster($value->id, "movie");
      $backdrop = getBackDrop($value->id, "movie");


      $spoken_languages = [];

      foreach($tvShow->spoken_languages as $lkey=>$lang){ 
       if(!in_array($lang->english_name, $spoken_languages)) {
        array_push($spoken_languages, $lang->english_name);
      }
    }

      $information = array(
        "tagline" => $tvShow->tagline,
        "description" => $value->overview,
        "languages" => $spoken_languages
      );

      $images = array(
        "poster" => $poster,
        "backdrop" => $backdrop,
    );

    

    

      if($youtube_id != ""){
      addRelease($link, $value->release_date, $value->original_title, json_encode($information), $youtube_id, json_encode($images), $value->id, "movie", $tvShow->homepage);
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

      $poster = getPoster($value->id, "tv");
      $backdrop = getBackDrop($value->id, "tv");

      $spoken_languages = [];

      foreach($tvShow->spoken_languages as $lkey=>$lang){ 
       if(!in_array($lang->english_name, $spoken_languages)) {
        array_push($spoken_languages, $lang->english_name);
      }
    }

      $information = array(
        "tagline" => $value->overview,
        "description" => $value->overview,
        "languages" => $spoken_languages
    );

      $images = array(
        "poster" => $poster,
        "backdrop" => $backdrop,
    );

      if($youtube_id != ""){
      addRelease($link, $value->first_air_date, $value->name, json_encode($information), $youtube_id, json_encode($images), $value->id, "series", $tvShow->homepage);
      printf("<p>Added $value->name</p>");
    } else {
        printf("<p>Skipped $value->name</p>");
      }
    }
  }
}

require('includes/footer.php');
?>
