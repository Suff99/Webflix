<?php

$langListFinal = loadLangs();

if (empty($langListFinal)) {
    loadLangs();
}

// Load languages from file
function loadLangs()
{
    $list = [];
    $json = file_get_contents("data/langs.json");
    $data  = json_decode($json);
    foreach ($data as &$value) {
        array_push($list, $value);
    }
    return $list;
}

function validateGet($value, $error_msg, $errors_array, $sql)
{
    if (empty($_POST[$value])) {
        $errors_array[] = $error_msg;
    } else {
        return mysqli_real_escape_string($sql, trim($_POST[$value]));
    }
    return false;
}

function addCategory($db, $category_name, $category_description){
    $addCategoryQuery = "INSERT INTO `wf_categories` (`name`, `description`) VALUES ('$category_name', '$category_description');";
    $result = @mysqli_query($db, $addCategoryQuery);
    if (!$result) {
        echo "ERROR!</br>";
        echo "$addCategoryQuery</br></br>";
    }
    return $db->insert_id;
}

// Registers a new release 
function addRelease($db, $date, $release_title, $information_json, $trailer_id, $image_json, $release_type, $watch_link, $categories)
{
    $addMovieQuery = "INSERT INTO wf_releases(`title`, `information`, `trailer`, `watch_link`, `date`, `images`, `release_type`, `categories`) VALUES ('$release_title','$information_json','$trailer_id','$watch_link','$date','$image_json','$release_type','$categories')";
    $result = @mysqli_query($db, $addMovieQuery);
    if (!$result) {
        echo "ERROR!</br>";
        echo "$addMovieQuery</br></br>";
    }
    return $db->insert_id;
}

// Should be called within the header of the page, locks out any users that do not have the role "admin"
function lockPageFromUser()
{
    if ($_SESSION['role'] != "admin") {
        header('Location: ' . '403.php');
    }
}

// Get the time between the given date and the current date, returns a int saying how many years have passed
function calculateAge($datetime)
{
    return date_diff(date_create($datetime), date_create('now'))->y;
}

// Creates a session with the name "webflix_session" with a given lifetime 
function session()
{
    if (@session_id() == "") {
        session_name("webflix_session");
        $lifetime = 600;
        session_start();
        setcookie(session_name(), session_id(), time() + $lifetime, "/");
    }
}

// Creates Meta tags based on the given parameters
function createMetaTags($title, $description, $thumbnail)
{
    if(empty($thumbnail)){
        $thumbnail = "https://craig.software/webflix/img/logo.png";
    }
    echo '<title>' . $title . '</title>';
    echo '<meta name="title" content="' . $title . '"/>';
    echo '<meta name="description" content="' . $description . '"/>';
    echo '<meta property="og:locale" content="en_GB" />';
    echo '<meta property="og:image" content="' . $thumbnail . '"/>';
    echo '<link rel="icon" type="image/png" href="' . $thumbnail . '"/>';
}

function addComment($link, $r_comment, $r_rating, $r_userid, $r_release_id)
{
    $addCommentQ = "INSERT INTO `wf_comments` (`message`, `rating`, `release_id`, `user_id`, `date`) VALUES ('$r_comment', '$r_rating', '$r_release_id', '$r_userid', now());";
    $result = @mysqli_query($link, $addCommentQ);
}

function createMovieCard($movie)
{


    echo '
    <div class="col zoom"> <div class="col-sm"> 
    <div class="card" style="width: 20rem;">';
    echo '<a data-toggle="collapse" href="#release_' . $movie['id'] . '" role="button" aria-expanded="false" aria-controls="collapse">';
    echo '<img class="card-img-top" src="' . json_decode($movie['images'])->poster . '" alt="' . $movie['title'] . ' logo"></a>';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $movie['title'] . ' ' . '<i class="' . ($movie['release_type'] == "movie" ? "bi bi-film" : "bi bi-tv-fill") . '" >' . '</i>'  . createMovieBadge($movie) . '</h5>';
    echo '<div class="collapse" id="release_' . $movie['id'] . '">';
    echo '<p class="card-text">' . json_decode($movie['information'])->tagline . '</p>';
    echo '<button type="button" class="btn btn-primary video-btn" data-toggle="modal" data-src="https://www.youtube.com/embed/' . $movie['trailer'] . '" data-target="#v_modal">
    Trailer
  </button>';
    echo '  <a href="release.php?id=' . $movie['id'] . '" class="btn btn-primary">More Info</a>';
    echo '</div>';

    echo '</div></div></div></div> </br></br></br></br></br></br>';
}

function noNewReleases($db_link)
{
    $countQuery = "SELECT * FROM wf_releases";
    $result = @mysqli_query($db_link, $countQuery);
    return mysqli_num_rows($result);
}

function getYtIdFromURl($url)
{
    if (strpos($url, 'https://youtu.be/')) {
        return str_replace("https://youtu.be/", "", $url);
    } else {
        parse_str(parse_url($url, PHP_URL_QUERY), $gets);
        return $gets['v'];
    }
}

function createMovieBadge($movie)
{
    $watch = $movie['watch_link'];
    if (empty($watch)) {
        echo '<span class="badge badge-pill badge-primary new">Coming soon</span>';
    } else {
        echo '<span class="badge badge-pill badge-danger new">New</span>';
    }
}

function deleteComment($link, $comm, $user){
    $deleteQuery = "DELETE FROM wf_comments WHERE `comment_id` = $comm AND `user_id` = $user";
    $result = @mysqli_query ( $link, $deleteQuery ) ;
}

function deleteCategory($link, $id){
    $deleteQuery = "DELETE FROM `webflix_db`.`wf_categories` WHERE  `id`=$id";
    $result = @mysqli_query ( $link, $deleteQuery ) ;
}
