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

<?php
require('includes/footer.php');
?>