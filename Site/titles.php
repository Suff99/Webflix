<!doctype html>
<html lang="en">

<?php
$currentPage = 0;

if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

$pageIdentifier = "whatson";
$title = "All Titles!";
$moviesQuery = "SELECT * FROM `wf_releases` ORDER BY `wf_releases`.`date` DESC";

if (isset($_GET['type'])) {

    $type = $_GET['type'];
    if (strcmp($type, "movies") == 0) {
        $moviesQuery = "SELECT * FROM wf_releases where release_type = 'movie'";
        $pageIdentifier = "movies";
        $title = "Movies!";
    }

    if (strcmp($type, "series") == 0) {
        $moviesQuery = "SELECT * FROM wf_releases where release_type = 'series'";
        $pageIdentifier = "tv_shows";
        $title = "TV Shows!";
    }
}

require('includes/database.php');
require('includes/nav.php');


$movies = mysqli_query($link, $moviesQuery);
$number_of_result = mysqli_num_rows($movies);
$results_per_page = 24;
$number_of_page = ceil($number_of_result / $results_per_page);
$page_first_result = ($currentPage - 1) * $results_per_page;

$query = $moviesQuery . " LIMIT " . $page_first_result . ',' . $results_per_page;
$result = mysqli_query($link, $query);

createMetaTags($title, "Look at our large collection of titles! Available for streaming now!", "");
?>


<body>
<div class="container-fluid">

    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
        <h1>What's on?</h1>
        </p>
    </div>

    <div class="row text-right justify-content-right align-items-right mx-0 px-0 text-black">
        <form action="search.php" method="post" class="alert-dismissible fade show" role="alert">
            <div class="input-group">
                <div class="form-outline">
                    <input id="search" onchange="lookup()" name="search" type="text" class="form-control"
                           required="required">
                </div>
                <button type="submit" class="btn btn-primary">
                    Search
                </button>
            </div>
        </form>
        <br>
    </div>

    <div class="row justify-content-center align-items-center mx-0 px-0 text-black">

        <?php
        while ($row = mysqli_fetch_array($result)) {
            createReleaseCard($row);
        }
        ?>

    </div>

    <div class="modal fade" id="v_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                    </button>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Mkum7G-0vWg"
                                id="the_trailer" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="release_section"></div>

</div>

<script>
    $(document).ready(function () {

        var $videoSrc = "";

        $('.video-btn').click(function () {
            $videoSrc = $(this).data("src");
            document.getElementById('the_trailer').src = $videoSrc;
        });

        $('#v_modal').on('hide.bs.modal', function (e) {
            document.getElementById('the_trailer').src = "";
        });


        $.getJSON('https://craig.software/webflix/includes/search.php', function (data) {

            for (var i = 0; i < data.length; i++) {
                var obj = data[i];
                var html = obj.title;

            }
        });

    });
</script>


<br><br><br>
<nav class="row g-0 text-center justify-content-center align-items-center mx-0 px-0">
    <ul class="pagination">
        <?php
        for ($page = 1; $page <= $number_of_page; $page++) {
            echo '<li class="page-item' . (($page == $currentPage) ? ' active' : "") . '"><a class="page-link" href="titles.php?page=' . $page . '&type=' . ((isset($_GET['type'])) ? $_GET['type'] : "") . '">' . $page . '</a></li>';
        }
        ?>
    </ul>
</nav>

<?php
require('includes/footer.php');
?>