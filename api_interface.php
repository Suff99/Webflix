<?php

include('includes/util.php');
include('includes/database.php');
$query = "SELECT * FROM `wf_releases`";  
$result = mysqli_query($link, $query);   
while ($row = mysqli_fetch_array($result)) {  
  fakeReviews($link, $row['api_id'], $row['release_type'], $row['id']);
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




?>