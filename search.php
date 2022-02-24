<!doctype html>
<html lang="en">

<head>
    <?php
    $identifier = "search";
    $title = "Search";
    require('includes/database.php');
    require('includes/nav.php');
    createMetaTags($title, "Look at our large collection of titles! Available for streaming now!", "");
    ?>
</head>

<body>
    <div class="container-fluid">

        <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
            <h1>What's on?</h1>
            </p>
        </div>

        <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
            <form action="search.php" method="post" class="alert-dismissible fade show" role="alert">
                <div class="input-group">
                    <div class="form-outline">
                        <input id="search" name="search" type="text" class="form-control" required="required">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                </div>
            </form> <br>
        </div>

        <div class="row justify-content-center align-items-center mx-0 px-0 text-black">

            <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $potentialErrors = array();
                $search = confirmGetExistence('search', $link);
                $searchResult = "SELECT * FROM wf_releases where title LIKE '%$search%'";
                $result = mysqli_query($link, $searchResult);


                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        createReleaseCard($row);
                    }
                } else {
                    echo 'No results found for ' . $search;
                }
            }
            ?>

        </div>

        <div class="modal fade" id="v_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                        </button>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Mkum7G-0vWg" id="the_trailer" allowscriptaccess="always" allow="autoplay"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {

            var $videoSrc = "";

            $('.video-btn').click(function() {
                $videoSrc = $(this).data("src");
                document.getElementById('the_trailer').src = $videoSrc;
            });

            $('#v_modal').on('hide.bs.modal', function(e) {
                document.getElementById('the_trailer').src = "";
            });


        });
    </script>


    <?php
    require('includes/footer.php');
    ?>