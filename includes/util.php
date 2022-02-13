<?php

function validateGet($value, $error_msg, $errors_array, $sql){
    if ( empty( $_POST[ $value ] ) )
    { $errors[] = $error_msg ; }
    else { 
    return mysqli_real_escape_string($sql, trim( $_POST[ $value  ] ) ) ; 
}
    return false;
}

function addRelease($db_link, $date, $release_title, $release_desc, $trailer_id, $vote, $api_id, $release_type, $watch_link){
    $addMovieQuery = "INSERT INTO wf_releases(`title`, `description`, `trailer`, `watch_link`, `date`, `vote_average`, `release_type`, `api_id`) VALUES ('$release_title','$release_desc','$trailer_id','$watch_link','$date','$vote','$release_type','$api_id')";
    $result = @mysqli_query ( $db_link, $addMovieQuery ) ;
}

function createMeta($title, $description, $thumbnail){
  if(empty($thumbnail)){
   $thumbnail = "https://craig.software/webflix/img/logo.png";
  }  
  echo '<title>' . $title . '</title>';
  echo '<meta name="title" content="' . $title.'"/>'; 
  echo '<meta name="description" content="' . $description.'"/>';
  echo '<meta property="og:locale" content="en_GB" />';
  echo '<meta property="og:image" content="' .$thumbnail. '"/>';
  echo '<link rel="icon" type="image/png" href="' . $thumbnail . '"/>';
}

function addComment($link, $r_comment, $r_rating, $r_userid, $r_release_id){
    $addCommentQ = "INSERT INTO `wf_comments` (`message`, `rating`, `release_id`, `user_id`) VALUES ('$r_comment', '$r_rating', '$r_release_id', '$r_userid');";
    $result = @mysqli_query ( $link, $addCommentQ ) ;
}

function createMovieCard($movie){


    echo '
    <div class="col zoom"> <div class="col-sm"> 
    <div class="card" style="width: 20rem;">';
    echo '<a data-toggle="collapse" href="#release_'.$movie['id'].'" role="button" aria-expanded="false" aria-controls="collapse">';
    echo '<img class="card-img-top" src="' . getPoster($movie['api_id'], $movie['release_type']) .'" alt="'. $movie['title'] . ' logo"></a>';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $movie['title'] . ' ' . '<i class="'. ($movie['release_type'] == "movie" ? "bi bi-film" : "bi bi-tv-fill") . '" >'.'</i>'  . createBadge($movie) . '</h5>';
    echo '<p class="card-text"><small class="text-muted"> <i class="bi bi-activity"></i>' . $movie['vote_average'] . '</small></p>';

    echo '<div class="collapse" id="release_'.$movie['id'].'">';
    echo '<p class="card-text">' . $movie['description'] . '</p>';
    echo '<p class="card-text"><small class="text-muted"> Watch on ' . $movie['date'] . '</small></p>';
    echo '<a href="https://www.youtube.com/watch?v=' . $movie['trailer'] .'" class="btn btn-primary">Trailer</a>';
    echo '  <a href="release.php?id=' . $movie['id'] .'" class="btn btn-primary">More Info</a>';
    echo '</div>';

    echo '</div></div></div></div>';
}

function noNewReleases($db_link){
    $countQuery = "SELECT * FROM wf_releases";
    $result = @mysqli_query ( $db_link, $countQuery ) ;
    return mysqli_num_rows($result);
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

function getFullUrl(){
    return 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function createBadge($movie){
    $watch = $movie['watch_link'];
    if(empty($watch)){
        echo '<span class="badge badge-pill badge-primary new">Coming soon</span>';
    } else {
        echo '<span class="badge badge-pill badge-danger new">New</span>';
    }
}

?>