<?php


include('includes/util.php');
include('includes/database.php');


$sql = "Truncate wf_comments; Truncate wf_releases;;";
$result = @mysqli_query ( $link, $sql ) ;

addMovies($link, 20);
addTvsShows($link, 20);



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
          "languages" => $spoken_languages,
          "runtime" => $tvShow->runtime . " Minutes"
        );
  
        $images = array(
          "poster" => $poster,
          "backdrop" => $backdrop,
      );
  
      
        $categoryNames = [];
  
        foreach($tvShow->genres as $c_key){ 
          if(!in_array($c_key->name, $categoryNames)) {
           array_push($categoryNames, $c_key->name);
         }
       }
  
       $categoryIds = [];
  
       foreach($categoryNames as $n_c){ 
        $sql = "SELECT * FROM `wf_categories` WHERE `name`= '$n_c';";
        $result = @mysqli_query ( $link, $sql ) ;
  
        while ($row = mysqli_fetch_array($result)) { ;
          array_push($categoryIds, $row['id']);
        }
       }
  
        if($youtube_id != ""){
        $rel = addRelease($link, $value->release_date, mysqli_real_escape_string($link, $value->title), json_encode($information), $youtube_id, json_encode($images), "movie", $tvShow->homepage, json_encode($categoryIds));
        if($rel > 0){
        fakeReviews($link,$value->id, "movie", $rel); 
        }
    } else {
          printf("<p>Skipped $value->title</p>");
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
          "tagline" => utf8_encode($value->overview),
          "description" => utf8_encode($value->overview),
          "languages" => $spoken_languages,
          "runtime" => $tvShow->number_of_seasons . " Seasons"
      );
  
        $images = array(
          "poster" => $poster,
          "backdrop" => $backdrop,
      );
  
  
      $categoryNames = [];
  
      foreach($tvShow->genres as $c_key){ 
        if(!in_array($c_key->name, $categoryNames)) {
         array_push($categoryNames, $c_key->name);
       }
     }
  
     $categoryIds = [];
  
     foreach($categoryNames as $n_c){ 
      $sql = "SELECT * FROM `wf_categories` WHERE `name`= '$n_c';";
      $result = @mysqli_query ( $link, $sql ) ;
  
      while ($row = mysqli_fetch_array($result)) { 
        printf($row['id']);
        array_push($categoryIds, $row['id']);
      }
     }
  
        if($youtube_id != ""){
        $rel = addRelease($link, $value->first_air_date, mysqli_real_escape_string($link, $value->name), json_encode($information), $youtube_id, json_encode($images), "series", $tvShow->homepage, json_encode($categoryIds));
        if($rel > 0){
          fakeReviews($link,$value->id, "series", $rel); 
          }
        printf("<p>Added $value->name</p>");
      } else {
          printf("<p>Skipped $value->name</p>");
        }
      }
    }
  }


  function fakeReviews($link, $mv_id, $mv_t, $mv_local_id){  
    $url = file_get_contents("https://api.themoviedb.org/3/".$mv_t."/". $mv_id."/reviews?api_key=5cd5948d48817e54d6fb43905f56a80f");
    $data  = json_decode($url);
    if(!empty($data->results)){
    $content =  $data->results[0];
    if(!empty($content->content)){
    addComment($link, $content->content, rand(1,5), 5, $mv_local_id);
    }
  }
}  


function getPoster($api_id, $type){
  $url = file_get_contents("https://api.themoviedb.org/3/$type/$api_id?api_key=5cd5948d48817e54d6fb43905f56a80f");  
  $data  = json_decode($url);
  return "https://image.tmdb.org/t/p/w300_and_h450_bestv2" .$data->poster_path;
}

function getBackDrop($api_id, $type){
  $url = file_get_contents("https://api.themoviedb.org/3/$type/$api_id?api_key=5cd5948d48817e54d6fb43905f56a80f");  
  $data  = json_decode($url);
  return "https://image.tmdb.org/t/p/w300_and_h450_bestv2" .$data->backdrop_path;
}

