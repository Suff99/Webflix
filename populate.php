<?php


include('includes/util.php');
include('includes/database.php');


$sql = "Truncate wf_comments; Truncate wf_releases;;";
$result = @mysqli_query($link, $sql);

addMovies($link, 20);
addTvsShows($link, 20);



function addMovies($link, $pages)
{
  for ($x = 1; $x <= $pages; $x++) {
    $url = file_get_contents_utf8("https://api.themoviedb.org/3/movie/popular?api_key=5cd5948d48817e54d6fb43905f56a80f&page=" . $x);
    $data  = json_decode($url);


    foreach ($data->results as $key => $value) {

      $youtube_id = "";
      $youtubeDate = file_get_contents_utf8("https://api.themoviedb.org/3/movie/" . $value->id . "/videos?api_key=5cd5948d48817e54d6fb43905f56a80f");
      $tvShowData = file_get_contents_utf8("https://api.themoviedb.org/3/movie/" . $value->id . "?api_key=5cd5948d48817e54d6fb43905f56a80f");

      $videos  = json_decode($youtubeDate);
      $tvShow  = json_decode($tvShowData);

      foreach ($videos->results as $vkey => $vid) {
        if ($vid->site == "YouTube") {
          $youtube_id = $vid->key;
        }
      }

      $poster = getPoster($value->id, "movie");
      $backdrop = getBackDrop($value->id, "movie");


      $spoken_languages = [];

      foreach ($tvShow->spoken_languages as $lkey => $lang) {
        if (!in_array($lang->english_name, $spoken_languages)) {
          array_push($spoken_languages, $lang->english_name);
        }
      }

  

      $runtime = $tvShow->runtime;
      if(empty($runtime) || strcmp($runtime, "0") === 0){
        $runtime = "Unknown (Title may not be released yet)";
      } else {
        $runtime .= " Minutes";
      }

      $addtional_info = array(
        "languages" => $spoken_languages,
        "runtime" => $runtime
      );

      $images = array(
        "poster" => $poster,
        "backdrop" => $backdrop,
      );


      $categoryNames = [];

      foreach ($tvShow->genres as $c_key) {
        if (!in_array($c_key->name, $categoryNames)) {
          array_push($categoryNames, $c_key->name);
        }
      }

      $categoryIds = [];

      foreach ($categoryNames as $n_c) {
        $sql = "SELECT * FROM `wf_categories` WHERE `name`= '$n_c';";
        $result = @mysqli_query($link, $sql);

        while ($row = mysqli_fetch_array($result)) {;
          array_push($categoryIds, $row['id']);
        }
      }

      if ($youtube_id != "") {
        $rel = addReleaseButEdited($link, $value->release_date, $value->original_title, mysqli_real_escape_string($link, $tvShow->tagline), mysqli_real_escape_string($link, $tvShow->overview), $youtube_id, json_encode($images), "movie", $tvShow->homepage, json_encode($categoryIds), json_encode($addtional_info));
        if ($rel > 0) {
          fakeReviews($link, $value->id, "movie", $rel);
        }
        printf("<p>Added $value->title</p>");
      } else {
        printf("<p>Skipped $value->title</p>");
      }
    }
  }
}

function addTvsShows($link, $pages)
{
  for ($x = 1; $x <= $pages; $x++) {
    $url = file_get_contents_utf8("https://api.themoviedb.org/3/tv/popular?api_key=5cd5948d48817e54d6fb43905f56a80f&page=" . $x);
    $data  = json_decode($url);


    foreach ($data->results as $key => $value) {
      $youtube_id = "";
      $youtubeDate = file_get_contents_utf8("https://api.themoviedb.org/3/tv/" . $value->id . "/videos?api_key=5cd5948d48817e54d6fb43905f56a80f");
      $tvShowData = file_get_contents_utf8("https://api.themoviedb.org/3/tv/" . $value->id . "?api_key=5cd5948d48817e54d6fb43905f56a80f");

      $videos  = json_decode($youtubeDate);
      $tvShow  = json_decode($tvShowData);

      foreach ($videos->results as $vkey => $vid) {
        if ($vid->site == "YouTube") {
          $youtube_id = $vid->key;
        }
      }

      $poster = getPoster($value->id, "tv");
      $backdrop = getBackDrop($value->id, "tv");

      $spoken_languages = [];

      foreach ($tvShow->spoken_languages as $lkey => $lang) {
        if (!in_array($lang->english_name, $spoken_languages)) {
          array_push($spoken_languages, $lang->english_name);
        }
      }

      $seasons = $tvShow->number_of_seasons;
      if(empty($seasons) || strcmp($seasons, "0") === 0){
        $seasons = "Unknown (Title may not be released yet)";
      } else {
        $seasons .= " Seasons";
      }

      $addtional_info = array(
        "languages" => $spoken_languages,
        "runtime" => $seasons
      );

      $images = array(
        "poster" => $poster,
        "backdrop" => $backdrop,
      );


      $categoryNames = [];

      foreach ($tvShow->genres as $c_key) {
        if (!in_array($c_key->name, $categoryNames)) {
          array_push($categoryNames, $c_key->name);
        }
      }

      $categoryIds = [];

      foreach ($categoryNames as $n_c) {
        $sql = "SELECT * FROM `wf_categories` WHERE `name`= '$n_c';";
        $result = @mysqli_query($link, $sql);

        while ($row = mysqli_fetch_array($result)) {
          printf($row['id']);
          array_push($categoryIds, $row['id']);
        }
      }

      if ($youtube_id != "") {
        $rel = addReleaseButEdited($link, $value->first_air_date, $value->name, mysqli_real_escape_string($link, $tvShow->overview), mysqli_real_escape_string($link, $tvShow->overview), $youtube_id, json_encode($images), "series", $tvShow->homepage, json_encode($categoryIds), json_encode($addtional_info));
        if ($rel > 0) {
          fakeReviews($link, $value->id, "series", $rel);
        }
        printf("<p>Added $value->name</p>");
      } else {
        printf("<p>Skipped $value->name</p>");
      }
    }
  }
}


function fakeReviews($link, $mv_id, $mv_t, $mv_local_id)
{
  $url = file_get_contents_utf8("https://api.themoviedb.org/3/" . $mv_t . "/" . $mv_id . "/reviews?api_key=5cd5948d48817e54d6fb43905f56a80f");
  $data  = json_decode($url);
  if (!empty($data->results)) {
    $content =  $data->results[0];
    if (!empty($content->content)) {
      addComment($link, $content->content, rand(1, 5), 5, $mv_local_id);
    }
  }
}


function getPoster($api_id, $type)
{
  $url = file_get_contents_utf8("https://api.themoviedb.org/3/$type/$api_id?api_key=5cd5948d48817e54d6fb43905f56a80f");
  $data  = json_decode($url);
  return "https://image.tmdb.org/t/p/w300_and_h450_bestv2" . $data->poster_path;
}

function getBackDrop($api_id, $type)
{
  $url = file_get_contents_utf8("https://api.themoviedb.org/3/$type/$api_id?api_key=5cd5948d48817e54d6fb43905f56a80f");
  $data  = json_decode($url);
  return "https://image.tmdb.org/t/p/w300_and_h450_bestv2" . $data->backdrop_path;
}


function file_get_contents_utf8($fn) {
  $content = file_get_contents($fn);
   return mb_convert_encoding($content, 'UTF-8',
       mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

function addReleaseButEdited($db, $date, $release_title, $tagline, $desc, $trailer_id, $image_json, $release_type, $watch_link, $categories, $addtional_info)
{
    $titleToReadable = backToReadable($release_title);
    $titleLookup = "SELECT release_title FROM wf_releases WHERE title='$titleToReadable'";
    $results = @mysqli_query($db, $titleLookup);
    
    if (mysqli_num_rows($results) == 0) {
        $addMovieQuery = "INSERT INTO wf_releases(`title`, `tagline`, `description`, `trailer`,`watch_link`, `date`, `images`, `release_type`, `categories`, `addtional_info`) VALUES ('$release_title','$tagline','$desc','$trailer_id','$watch_link','$date','$image_json','$release_type','$categories','$addtional_info')";
        $result = @mysqli_query($db, $addMovieQuery);
        if (!$result) {
            echo "ERROR!</br>";
            echo "$addMovieQuery</br></br>";
        }
        return $db->insert_id;
    }  
}