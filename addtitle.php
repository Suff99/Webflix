<!doctype html>
<html lang="en">

<head>
  <?php
  require('includes/header.php');
  ?>
</head>

<h1>Add Title</h1>

<div class="header">
  <?php
  $identifier = "admin";
  require('includes/database.php');
  require('includes/nav.php');

  session();
  createMetaTags("Admin Panel", "Create and Edit", "");
  lockPageFromUser();

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require('includes/database.php');
    $potentialErrors = array();
    $title = validateGet('title', 'Please give a comment', $potentialErrors, $link);
    $tagline = validateGet('tagline', 'Please give a tagline', $potentialErrors, $link);
    $description = validateGet('description', 'Please give a description', $potentialErrors, $link);
    $poster_url = validateGet('poster_url', 'Please give a poster_url', $potentialErrors, $link);
    $backdrop_url = validateGet('backdrop_url', 'Please give a backdrop_url', $potentialErrors, $link);
    $release_type = validateGet('release_type', 'Please give a release_type', $potentialErrors, $link);
    $trailer = validateGet('trailer_id', 'Please give a trailer_id', $potentialErrors, $link);
    $watch_link = validateGet('watch_link', 'Please give a watch_link', $potentialErrors, $link);
    $release_date = validateGet('release_date', 'Please give a release_date', $potentialErrors, $link);

    $trailer_id = getYtIdFromURl($trailer);
    if (empty($trailer_id)) {
      $potentialErrors[] = "Invalid YouTube link.";
    }

    $categories = json_encode($_POST['categories']);

    $information = array(
      "tagline" => $tagline,
      "description" => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
      "languages" => "English"
    );

    $images = array(
      "poster" => $poster_url,
      "backdrop" => $backdrop_url,
    );

    if (empty($potentialErrors)) {
      $releaseAdd = addRelease($link, $release_date, $title, json_encode($information), $trailer_id, json_encode($images), $release_type, $watch_link, $categories);
      header('Location: ' . 'release.php?id=' . $releaseAdd);
    } else {
      echo '<div class="alert alert-warning" role="alert">
      <h4 class="alert-heading">Error!</h4>';
      foreach ($potentialErrors as $msg) {
        echo "- $msg<br>";
      }
      echo 'Please try again.</p></div>';
      mysqli_close($link);
    }
  }


  ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

  <script>
      createDatePicker("#release_date");
      jQuery(document).ready(function ($) {
        //$('#select_lang').selectpicker();
    })
  </script>

</div>

<body class="d-flex flex-column min-vh-100">
  <div class="container">
    <div class="col-sm">
      <form action="admin.php" method="post" class="alert-dismissible fade show" role="alert">
        <h1>Admin Panel</h1>
        <div class="form-group row">
          <label for="title" class="col-4 col-form-label">Title</label>
          <div class="col-8">
            <input id="title" name="title" type="text" class="form-control" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="tagline" class="col-4 col-form-label">Tagline</label>
          <div class="col-8">
            <input id="tagline" name="tagline" type="text" class="form-control" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="description" class="col-4 col-form-label">Description</label>
          <div class="col-8">
            <textarea id="description" name="description" cols="40" rows="5" class="form-control" required="required"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="poster_url" class="col-4 col-form-label">Poster URL</label>
          <div class="col-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fa fa-image"></i>
                </div>
              </div>
              <input id="poster_url" name="poster_url" type="text" aria-describedby="poster_urlHelpBlock" required="required" class="form-control">
            </div>
            <span id="poster_urlHelpBlock" class="form-text text-muted">Please supply a valid URL ending in PNG/JPG</span>
          </div>
        </div>
        <div class="form-group row">
          <label for="backdrop_url" class="col-4 col-form-label">Backdrop URL</label>
          <div class="col-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fa fa-image"></i>
                </div>
              </div>
              <input id="backdrop_url" name="backdrop_url" type="text" aria-describedby="backdrop_urlHelpBlock" required="required" class="form-control">
            </div>
            <span id="backdrop_urlHelpBlock" class="form-text text-muted">Please supply a valid URL ending in PNG/JPG</span>
          </div>
        </div>

        

        <div class="form-group row">
          <label for="release_type" class="col-4 col-form-label">Release Type</label>
          <div class="col-8">
            <select id="release_type" name="release_type" class="custom-select" required="required">
              <option value="series">TV Series</option>
              <option value="Movie">Movie</option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-4">Categories</label>
          <div class="col-8">

            <?php

            $queryCategories = "SELECT * FROM `wf_categories`;";
            $result = @mysqli_query($link, $queryCategories);
            while ($category = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
              echo '<div class="custom-control custom-checkbox custom-control-inline">
          <input name="categories[]" id="' . $category['name'] . '" type="checkbox" class="custom-control-input" value="' . $category['id'] . '"> 
          <label for="' . $category['name'] . '" class="custom-control-label">' . $category['name'] . '</label>
        </div>';
            }
            ?>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-4">Languages</label>
          <div class="col-8">

            <select class="custom-select" name="select_lang" id="select_lang" required="required">
              <option selected>None Selected</option>

              <?php
              foreach ($langListFinal as &$value) {
                echo '<option value="' . $value . '">' . $value . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label for="trailer_id" class="col-4 col-form-label">Trailer URL</label>
          <div class="col-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fa fa-youtube-play"></i>
                </div>
              </div>
              <input id="trailer_id" name="trailer_id" type="text" class="form-control" required="required" aria-describedby="trailer_idHelpBlock">
            </div>
            <span id="trailer_idHelpBlock" class="form-text text-muted">Please supply a link to the titles trailer</span>
          </div>
        </div>

        <div class="form-group row">
          <label for="release_date" class="col-4 col-form-label">Release Date</label>
          <div class="col-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="bi bi-calendar-event"></i>
                </div>
              </div>
              <input id="release_date" name="release_date" type="text" class="form-control" aria-describedby="release_dateHelpBlock">
            </div>
            <span id="release_dateHelpBlock" class="form-text text-muted">The intial release of the title</span>
          </div>
        </div>

        <div class="form-group row">
          <label for="watch_link" class="col-4 col-form-label">Watch Link</label>
          <div class="col-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="bi bi-link-45deg"></i>
                </div>
              </div>
              <input id="watch_link" name="watch_link" type="text" class="form-control" aria-describedby="watch_linkHelpBlock">
            </div>
            <span id="watch_linkHelpBlock" class="form-text text-muted">(Optional - Will be marked as Coming soon) Please give a watch URL</span>
          </div>
        </div>

        <div class="form-group row">
          <div class="offset-4 col-8">
            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>




  <?php
  require('includes/footer.php');
  ?>