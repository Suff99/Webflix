<?php

include('includes/util.php');
include('includes/database.php');
checkForAdmin();

addMovies($link, 10);
addTVShows($link, 10);

// Parameters: The Movie Database API release ID, Release type (tv/movie)
// Used to return the key of a YouTube Video that is the trailer of the given release. 
// Returns empty string value if no valid video is found. 
function getYouTubeTrailer($releaseId, $release_type)
{
    $youtube_id = "";
    $youtubeData = file_get_contents("https://api.themoviedb.org/3/$release_type/" . $releaseId . "/videos?api_key=5cd5948d48817e54d6fb43905f56a80f");
    $videos = json_decode($youtubeData);
    foreach ($videos->results as $vkey => $vid) {
        if ($vid->site == "YouTube") {
            $youtube_id = $vid->key;
        }
    }
    return $youtube_id;
}


// Parameters: link (Database connection variable), the amount of pages to pull TV Shows from
// Purpose of the method is to cycle through the given number of pages from The Movie DB and retrieve data 
// of the TV Show releases from these pages 
function addTVShows($link, $pages)
{
    for ($x = 1; $x <= $pages; $x++) {

        $url = file_get_contents("https://api.themoviedb.org/3/tv/popular?api_key=5cd5948d48817e54d6fb43905f56a80f&page=" . $x);
        $data = json_decode($url);


        // Loop through all releases on the given page
        foreach ($data->results as $key => $value) {
            $tvShowData = file_get_contents("https://api.themoviedb.org/3/tv/" . $value->id . "?api_key=5cd5948d48817e54d6fb43905f56a80f");
            $tvShow = json_decode($tvShowData);

            // ===== Spoken Languages =====
            $spoken_languages = [];
            foreach ($tvShow->spoken_languages as $lkey => $lang) {
                if (!in_array($lang->english_name, $spoken_languages)) {
                    array_push($spoken_languages, $lang->english_name);
                }
            }


            // Retrieve runtime of the tv show, and format it. If missing, we state that it is unknown due to the title not being released
            $seasons = $tvShow->number_of_seasons;
            if (empty($seasons) || strcmp($seasons, "0") === 0) {
                $seasons = "N/A";
            } else {
                $seasons .= " Seasons";
            }

            // Save all spoken languages and runtime into a array, for later conversion into a JSON
            $additional_info = array(
                "languages" => $spoken_languages,
                "runtime" => $seasons
            );


            // ===== Images =====
            // Retrieve poster & backdrop for the tv show
            $poster = getPoster($value->id, "tv");
            $backdrop = getBackDrop($value->id, "tv");
            // Save to array for later conversion to JSON
            $images = array(
                "poster" => $poster,
                "backdrop" => $backdrop,
            );

            // ===== Categories =====

            $categoryIds = [];

            // Loop through category names provided by The Movie Database and compare names to internal database
            // If comparison is true, add id of SQL row to array for later JSON conversion
            foreach ($tvShow->genres as $category) {
                $sql = "SELECT * FROM `wf_categories` WHERE `name`= '$category->name';";
                $result = @mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    array_push($categoryIds, $row['id']);
                }
            }

            // ===== Youtube Trailer =====
            $youtube_id = getYouTubeTrailer($value->id, "tv");

            // Only add TV Show if there is a possible trailer for it
            if (!empty($youtube_id)) {
                // Add generated the TV show via addExternalRelease
                $showIdInternal = addExternalRelease($link, $value->first_air_date, $value->name, mysqli_real_escape_string($link, $tvShow->overview), mysqli_real_escape_string($link, $tvShow->overview), $youtube_id, json_encode($images), "series", $tvShow->homepage, json_encode($categoryIds), json_encode($additional_info));

                // Validate the TV show was added, and proceed to generate possible comments from The Movie Database
                if ($showIdInternal > 0) {
                    generateComments($link, $value->id, "tv", $showIdInternal); // Generate Comments
                    printf("<p>Successfully added TV Show $value->name</p>"); // On Successful
                } else {
                    printf("<p>Failed to add TV Show $value->name</p>"); // On fail
                }
            }
        }
    }
}

// Parameters: link (Database connection variable), the amount of pages to pull Movies from
// Purpose of the method is to cycle through the given number of pages from The Movie DB and retrieve data 
// of the movie releases from these pages 
function addMovies($link, $pages)
{
    for ($x = 1; $x <= $pages; $x++) {

        $url = file_get_contents("https://api.themoviedb.org/3/movie/popular?api_key=5cd5948d48817e54d6fb43905f56a80f&page=" . $x);
        $data = json_decode($url);


        // Loop through all releases on the given page
        foreach ($data->results as $key => $value) {
            $movieData = file_get_contents("https://api.themoviedb.org/3/movie/" . $value->id . "?api_key=5cd5948d48817e54d6fb43905f56a80f");
            $movie = json_decode($movieData);

            // ===== Spoken Languages =====
            $spoken_languages = [];
            foreach ($movie->spoken_languages as $lkey => $lang) {
                if (!in_array($lang->english_name, $spoken_languages)) {
                    array_push($spoken_languages, $lang->english_name);
                }
            }


            // Retrieve runtime of movie, and format it. If missing, we state that it is unknown due to the title not being released
            $runtime = $movie->runtime;
            if (empty($runtime) || strcmp($runtime, "0") === 0) {
                $runtime = "N/A";
            } else {
                $runtime .= " Minutes";
            }

            // Save all spoken languages and runtime into a array, for later conversion into a JSON
            $additional_info = array(
                "languages" => $spoken_languages,
                "runtime" => $runtime
            );


            // ===== Images =====
            // Retrieve poster & backdrop for movie
            $poster = getPoster($value->id, "movie");
            $backdrop = getBackDrop($value->id, "movie");
            // Save to array for later conversion to JSON
            $images = array(
                "poster" => $poster,
                "backdrop" => $backdrop,
            );

            // ===== Categories =====

            $categoryIds = [];

            // Loop through category names provided by The Movie Database and compare names to internal database
            // If comparison is true, add id of SQL row to array for later JSON conversion
            foreach ($movie->genres as $category) {
                $sql = "SELECT * FROM `wf_categories` WHERE `name`= '$category->name';";
                $result = @mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    ;
                    array_push($categoryIds, $row['id']);
                }
            }

            // ===== Youtube Trailer =====
            $youtube_id = getYouTubeTrailer($value->id, "movie");

            // Only add Movie if there is a possible trailer for it
            if (!empty($youtube_id)) {
                // Add generated movie via addExternalRelease
                $movieIdInternal = addExternalRelease($link, $value->release_date, $value->original_title, mysqli_real_escape_string($link, $movie->tagline), mysqli_real_escape_string($link, $movie->overview), $youtube_id, json_encode($images), "movie", $movie->homepage, json_encode($categoryIds), json_encode($additional_info));

                // Validate the movie was added, and proceed to generate possible comments from The Movie Database
                if ($movieIdInternal > 0) {
                    generateComments($link, $value->id, "movie", $movieIdInternal); // Generate Comments
                    printf("<p>Successfully added $value->title</p>"); // On Successful
                } else {
                    printf("<p>Failed to add $value->title</p>"); // On fail
                }
            }
        }
    }
}


// Patemeters: link (Database connection variable), int release id from The Movie Database API, Release type (tv/movie), Internal Database ID
// Used to create comments sourced from real people from The Movie Database API,  only the first comment is used and attributed to a dummy user.
function generateComments($link, $releaseId, $releaseType, $localDbId)
{
    $url = file_get_contents("https://api.themoviedb.org/3/" . $releaseType . "/" . $releaseId . "/reviews?api_key=5cd5948d48817e54d6fb43905f56a80f");
    $data = json_decode($url);
    if (!empty($data->results)) {
        $content = $data->results[0];
        if (!empty($content->content)) {
            addComment($link, $content->content, rand(1, 5), 5, $localDbId);
        }
    }
}


// Parameters: API ID, type
// API ID refers to the ID of the release from The Movie Database API, while type refers to whether the 
// release is a tv or a movie, accepted inputs are "tv" and "movie"
// Returns image link to the releases poster image 
function getPoster($releaseId, $releaseType)
{
    $url = file_get_contents("https://api.themoviedb.org/3/$releaseType/$releaseId?api_key=5cd5948d48817e54d6fb43905f56a80f");
    $data = json_decode($url);
    return "https://image.tmdb.org/t/p/original" . $data->poster_path;
}

// Parameters: API ID, type
// API ID refers to the ID of the release from The Movie Database API, while type refers to whether the 
// release is a tv or a movie, accepted inputs are "tv" and "movie"
// Returns image link to the releases backdrop image 
function getBackDrop($releaseId, $releaseType)
{
    $url = file_get_contents("https://api.themoviedb.org/3/$releaseType/$releaseId?api_key=5cd5948d48817e54d6fb43905f56a80f");
    $data = json_decode($url);
    return "https://image.tmdb.org/t/p/original" . $data->backdrop_path;
}

// Direct copy from Util::addRelease, with date correction removed. 
// addRelease takes DD/MM/YYYY while this method takes YYYY/DD/MM to match The Movie DB API
// Returns ID of inserted title on success, returns false on fail
function addExternalRelease($db, $date, $release_title, $tagline, $desc, $trailer_id, $image_json, $release_type, $watch_link, $categories, $additional_info)
{
    $titleLookup = "SELECT title FROM wf_releases WHERE title='$release_title'";
    $results = @mysqli_query($db, $titleLookup);

    if (!$results) {
        echo "$titleLookup";
        return false;
    }


    if (mysqli_num_rows($results) == 0) {
        $addMovieQuery = "INSERT INTO wf_releases(`title`, `tagline`, `description`, `trailer`,`watch_link`, `date`, `images`, `release_type`, `categories`, `additional_info`) VALUES ('$release_title','$tagline','$desc','$trailer_id','$watch_link','$date','$image_json','$release_type','$categories','$additional_info')";
        $result = @mysqli_query($db, $addMovieQuery);
        if (!$result) {
            echo "$addMovieQuery";
            return false;
        }
        return $db->insert_id;
    }
}
