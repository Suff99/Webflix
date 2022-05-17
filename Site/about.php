<!doctype html>
<html lang="en">

<head>
    <?php
    $pageIdentifier = "about";
    require('includes/database.php');
    require('includes/nav.php');

    createMetaTags("About", "About", "");
    ?>
</head>


<body class="d-flex flex-column min-vh-100">

<h1>About Webflix</h1>

<div>
     At Webflix, we want to entertain the world. Whatever your taste, and no matter where you live, we give you access to best-in-class TV series, documentaries and feature films. Our members control what they want to watch, when they want it, with no ads, in one simple subscription. We’re streaming in multiple languages and countries, because great stories can come from anywhere and be loved everywhere. We are the world’s biggest fans of entertainment, and we’re always looking to help you find your next favorite story.
</div>

<?php
require('includes/footer.php');
?>