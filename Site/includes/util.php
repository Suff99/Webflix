<?php

$langListFinal = loadLangs();

if (empty($langListFinal)) {
    loadLangs();
}

// Load languages from file
function loadLangs()
{
    $list = array();
    $json = file_get_contents("https://craig.software/webflix/data/langs.json");
    $data = json_decode($json);
    foreach ($data as &$value) {
        array_push($list, $value);
    }
    return $list;
}

function backToReadable($content)
{
    return json_encode($content, JSON_UNESCAPED_SLASHES);
}


function confirmGetExistence($value, $sql)
{
    if (!empty($_POST[$value])) {
        return mysqli_real_escape_string($sql, trim($_POST[$value]));
    }

    if (!empty($_GET[$value])) {
        return mysqli_real_escape_string($sql, trim($_GET[$value]));
    }

    return false;
}


function addComment($link, $r_comment, $r_rating, $r_userid, $r_release_id)
{
    $addCommentQ = "INSERT INTO `wf_comments` (`message`, `rating`, `release_id`, `user_id`, `date`) VALUES ('$r_comment', '$r_rating', '$r_release_id', '$r_userid', now());";
    $result = @mysqli_query($link, $addCommentQ);
}


function addCategory($db, $category_name, $category_description)
{
    $addCategoryQuery = "INSERT INTO `wf_categories` (`name`, `description`) VALUES ('$category_name', '$category_description');";
    $result = @mysqli_query($db, $addCategoryQuery);
    if (!$result) {
        echo "ERROR!<br>";
        echo "$addCategoryQuery<br><br>";
    }
    return $db->insert_id;
}

// Registers a new release 
function addRelease($db, $date, $release_title, $addtional_info_json, $trailer_id, $image_json, $release_type, $watch_link, $categories)
{
        $addMovieQuery = "INSERT INTO wf_releases(`title`, `additional_info`, `trailer`, `watch_link`, `date`, `images`, `release_type`, `categories`) VALUES ('$release_title','$addtional_info_json','$trailer_id','$watch_link', STR_TO_DATE('$date','%d-%m-%Y'),'$image_json','$release_type','$categories')";
        $result = @mysqli_query($db, $addMovieQuery);
        if (!$result) {
            echo "ERROR!<br>";
            echo "$addMovieQuery<br><br>";
        }
        return $db->insert_id;
}


// Should be called within the header of the page, locks out any users that do not have the role "admin"
function checkForAdmin()
{
    if (strcmp($_SESSION['role'], "admin") != 0) {
        $error403 = array("You do not have permission to access the intended page.");
        header('Location: ' . 'index.php?error=true&dialog=' . json_encode($error403));
    }
}

function blockDisabledAccounts(){
    if (strcmp($_SESSION['status'], "banned") == 0) {
        $error403 = array("This account is banned and is not permitted to use this service.");
        header('Location: ' . 'logout.php?error=true&dialog=' . json_encode($error403));
    }
}

function getUrl()
{
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $validURL = str_replace("&", "&amp;", $url);
    return $validURL;
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
    if (empty($thumbnail)) {
        $thumbnail = "https://craig.software/webflix/img/logo.png";
    }
    echo '<title>' . $title . '</title>' . PHP_EOL;
    echo '<meta name="title" content="' . $title . '"/>' . PHP_EOL;
    echo '<meta name="description" content="' . $description . '"/>' . PHP_EOL;
    echo '<meta property="og:locale" content="en_GB" />' . PHP_EOL;
    echo '<meta property="og:image" content="' . $thumbnail . '"/>' . PHP_EOL;
    echo '<link rel="icon" type="image/png" href="' . $thumbnail . '"/>' . PHP_EOL;
}

// Change User Role (user/admin)
function changeRole($link, $user, $role)
{
    $updateRoleQuerty = "UPDATE `webflix_db`.`wf_users` SET `role`='$role' WHERE  `user_id`=$user;";
    printf($updateRoleQuerty);
    $result = @mysqli_query($link, $updateRoleQuerty);
}

function createReleaseCard($movie)
{
    echo '
    <div class="col-sm"> 
    <div class="card" style="width: 20rem; margin-bottom: 25px;">';

    echo '<a data-toggle="collapse" class="zoom" href="#release_' . $movie['id'] . '" role="button" aria-expanded="false" aria-controls="collapse"><div class="card bg-dark text-white">
    <img class="card-img title_image" src="' . str_replace("https://image.tmdb.org/t/p/original/","https://image.tmdb.org/t/p/w300_and_h450_bestv2/", json_decode($movie['images'])->poster) . '" alt="' . $movie['title'] . ' logo">
    <div class="card-img-overlay">';
    echo '<h1 class="card-title">' . createMovieBadge($movie) . '</h1>';
    echo '<i class="' . ($movie['release_type'] == "movie" ? "bi bi-film" : "bi bi-tv-fill") . '" >' . '</i>
    </div>
  </div></a>';

    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $movie['title'] . '</h5>';
    echo '<div class="collapse" id="release_' . $movie['id'] . '">';
    echo '<p class="card-text">' . $movie['tagline'] . '</p>';
    echo '<button type="button" class="btn btn-primary video-btn" data-toggle="modal" data-src="https://www.youtube.com/embed/' . $movie['trailer'] . '" data-target="#v_modal">
    Trailer
  </button>';
    echo '  <a name="info" href="release.php?id=' . $movie['id'] . '" class="btn btn-primary">More Info</a>';
    echo '</div>';

    echo '</div></div></div> <br><br><br><br><br><br>';
}

function makeReadable($text)
{
    $fixed = str_replace("&quot", "'", $text);
    return htmlspecialchars($fixed);
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

function getAllUsers($db_link){
    $getUsersQuery = "SELECT * FROM wf_users";
    $result = @mysqli_query($db_link, $getUsersQuery);
    return $result;
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

function deleteComment($link, $comm, $user)
{
    $deleteQuery = "DELETE FROM wf_comments WHERE `comment_id` = $comm AND `user_id` = $user";
    $result = @mysqli_query($link, $deleteQuery);
}

function deleteCategory($link, $id)
{
    $deleteQuery = "DELETE FROM `webflix_db`.`wf_categories` WHERE  `id`=$id";
    $result = @mysqli_query($link, $deleteQuery);
}


function handleDialog()
{
    $error = "false";
    if (isset($_GET['dialog'])) {
        $messages = json_decode($_GET['dialog']);

        if (isset($_GET['error'])) {
            $error = $_GET['error'];
        }

        echo '<br><br><br><div class="alert ' . (($error == 'true') ? 'alert-danger"' : "alert-success") . ' alert-dismissable">
      <h4 class="alert-heading">' . (($error == 'true') ? 'Failed!' : "Success") . '</h4>
      <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>';
        foreach ($messages as $msg) {
            echo "$msg";
        }
        echo "</div>";
    }
}


function deleteTitle($link, $id)
{
    $deleteQuery = "DELETE FROM `webflix_db`.`wf_releases` WHERE  `id`=$id";
    $result = @mysqli_query($link, $deleteQuery);
    deleteCommentsFromTitle($link, $id);
}

function getTitleFromId($link, $id)
{
    $select = "SELECT * FROM `wf_releases` WHERE `id` =$id";
    $result = @mysqli_query($link, $select);
    return $result->fetch_row();
}

function getCategoryFromId($link, $id)
{
    $select = "SELECT * FROM `wf_categories` WHERE `id` =$id";
    $result = @mysqli_query($link, $select);
    return $result->fetch_row();
}

function deleteCommentsFromTitle($link, $id)
{
    $delComment = "DELETE FROM `webflix_db`.`wf_comments` WHERE  `release_id`=$id";
    $result = @mysqli_query($link, $delComment);
}