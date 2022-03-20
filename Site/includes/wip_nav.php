<?php
require('util.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

?>


    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
        <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
        <script src="https://www.google.com/recaptcha/api.js"></script>


        <script src="js/script.js"></script>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
              crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">


        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
        <link rel="stylesheet" media="screen" href="https://fontlibrary.org//face/recurso-sans" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <div class="nav_banner"></div>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <a class="navbar-brand" href="#">Webflix</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if ($identifier == 'home') echo 'active'; ?>" href="index.php"><i
                                class="bi bi-film"></i> Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($identifier == 'about') echo 'active'; ?>" href="about.php"><i
                                class="bi bi-info-circle-fill"></i> About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($identifier == 'whatson') echo 'active'; ?>" href="titles.php"><i
                                class="bi bi-projector-fill"></i> What's on?</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($identifier == 'movies') echo 'active'; ?>"
                       href="titles.php?type=movies"><i class="bi bi-film"></i> Movies</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($identifier == 'tv_shows') echo 'active'; ?>"
                       href="titles.php?type=series"><i class="bi bi-tv-fill"></i> TV Shows</a>
                </li>

                <?php
                session();

                if (isset($_SESSION['username']) && strcmp($_SESSION['role'], "admin") == 0) {
                    echo '<li class="nav-item"> <a class="nav-link' . (($identifier == 'admin') ? ' active"' : "") . '" href="admin.php"><i class="bi bi-shield-fill-check"></i> Admin</a></li>';
                }
                ?>

                <li class="nav-item">
                    <?php

                    if (!isset($_SESSION['username'])) {
                        echo ' <a class="nav-link' . (($identifier == 'Sign in') ? ' active"' : "") . '" href="login.php"><i class="bi bi-person-plus-fill"></i> Sign in</a>';
                    } else {
                        echo ' <a class="nav-link' . (($identifier == 'logout') ? ' active"' : "") . '" href="logout.php"><i class="bi bi-person-x-fill"></i> Logout</a>';
                    }
                    ?>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Dropdown
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="search.php" method="post" class="alert-dismissible fade show"
                  role="alert">
                <input id="search" name="search" type="text" class="form-control mr-sm-2" required="required">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <style>

    </style>

<?php
handleDialog();
?>