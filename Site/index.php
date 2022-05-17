<!doctype html>
<html lang="en">


<head>
    <?php
    $pageIdentifier = "home";
    require('includes/database.php');
    require('includes/nav.php');
    createMetaTags("Home", "Home", "");
    ?>
</head>

<body class="d-flex flex-column min-vh-100">

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <img class='card-img' src='https://craig.software/webflix/img/logo.png' alt='Logo' style='width:20%'>
</div>

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> Home to over <?php echo noNewReleases($link) ?> releases! <br>
        Anytime - Anywhere - Any Device </p>
</div>

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <?php
        $releases = mysqli_query($link, "SELECT * FROM wf_releases ORDER BY RAND() LIMIT 7");

        while ($row = mysqli_fetch_array($releases)) {
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


<?php
require('includes/footer.php');
?>