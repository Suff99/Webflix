<!doctype html>
<html lang="en">

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


    foreach ($_POST as $key => $value) {
      echo "Field " . htmlspecialchars($key) . " is " . json_encode($value) . "<br>";
    }

    require('includes/database.php');
    $potentialErrors = array();
    $title = addslashes(confirmGetExistence('title', $link));
    $tagline = addslashes(confirmGetExistence('tagline', $link));
    $description = addslashes(confirmGetExistence('description', $link));
    $poster_url = confirmGetExistence('poster_url', $link);
    $backdrop_url = confirmGetExistence('backdrop_url', $link);
    $release_type = confirmGetExistence('release_type', $link);
    $trailer = confirmGetExistence('trailer_id', $link);
    $watch_link = confirmGetExistence('watch_link', $link);
    $release_date = confirmGetExistence('release_date', $link);
    $runtime = confirmGetExistence('runtime', $link);


    if (!$title) {
      array_push($potentialErrors, "Missing title");
    }

    if (!$tagline) {
      array_push($potentialErrors, "Missing Tagline");
    }

    if (!$description) {
      array_push($potentialErrors, "Missing Description");
    }

    if (!$poster_url) {
      array_push($potentialErrors, "Missing Poster URL");
    }

    if (!$backdrop_url) {
      array_push($potentialErrors, "Missing Backdrop URL");
    }

    if (!$release_type) {
      array_push($potentialErrors, "Missing Release Type");
    }

    if (!$release_date) {
      array_push($potentialErrors, "Missing Release Date");
    }
    
    if (!$runrtime) {
      array_push($potentialErrors, "Missing Runtime");
    }

    $trailer_id = getYtIdFromURl($trailer);
    if (empty($trailer_id)) {
      array_push($potentialErrors, "Invalid YouTube link.");
    }


    if (empty($potentialErrors)) {

      $categories = json_encode($_POST['select_categories']);
      $languages = json_encode($_POST['select_lang']);

      $addtional_info = array(
        "tagline" => $tagline,
        "description" => $description,
        "languages" => json_decode($languages),
        "runtime" => $runtime
      );

      $images = array(
        "poster" => $poster_url,
        "backdrop" => $backdrop_url,
      );

      $releaseAdd = addRelease($link, $release_date, $title, json_encode($addtional_info), $trailer_id, json_encode($images), $release_type, $watch_link, $categories);
       header('Location: ' . 'release.php?id=' . $releaseAdd . "&dialog=" . json_encode($releaseAdd));
    } else {
      header('Location: ' . 'admin.php?error=true&dialog=' . json_encode($potentialErrors));
      echo 'Please try again.</p></div>';
      mysqli_close($link);
    }
  }


  ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

  <script>
    createDatePicker("#release_date");
    jQuery(document).ready(function($) {
      //   $('#select_lang').selectpicker();
    })
  </script>

</div>


<body class="d-flex flex-column min-vh-100">

  <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <img class="card-img" src="img/logo.png" alt="Logo" style="width:20%">
  </div>

  <div class="container">
    <div class="col-sm">
      <form action="addtitle.php" method="post" class="alert-dismissible fade show" role="alert">
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
              <input id="poster_url" name="poster_url" title="Please supply a valid image URL (Ending in GIF/PNG/JPEG/JPG)" type="text" aria-describedby="poster_urlHelpBlock" required="required" class="form-control" pattern="(http(s?):)([/|.|\w|\s|-])*\.(?:jpg|gif|png|jpeg)">
            </div>
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
              <input id="backdrop_url" name="backdrop_url" type="text" title="Please supply a valid image URL (Ending in GIF/PNG/JPEG/JPG)" pattern="(http(s?):)([/|.|\w|\s|-])*\.(?:jpg|gif|png|jpeg)" aria-describedby="backdrop_urlHelpBlock" required="required" class="form-control">
            </div>
          </div>
        </div>

        <script>
          (function($) {
            $(document).ready(function() {

              var languagesButton = new Choices('#select_lang', {
                removeItemButton: true
              });

              var languagesButton = new Choices('#select_categories', {
                removeItemButton: true
              });

            });
          })(jQuery);
        </script>

        <div class="form-group row">
          <label for="release_type" class="col-4 col-form-label">Runtime</label>
          <div class="col-8">
            <input id="runtime" name="runtime" type="text" required="required" class="form-control">
          </div>
        </div>

        <div class="form-group row">
          <label for="release_type" class="col-4 col-form-label">Release Type</label>
          <div class="col-8">
            <select id="release_type" name="release_type" class="custom-select" required="required">
              <option value="series">TV Series</option>
              <option value="ovie">Movie</option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-4">Categories</label>
          <div class="col-8">
            <select class="custom-select" name="select_categories[]" id="select_categories" required="required" placeholder="Please select the relevant categories" multiple>
              <?php

              $queryCategories = "SELECT * FROM `wf_categories`;";
              $result = @mysqli_query($link, $queryCategories);
              while ($category = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-4">Languages</label>
          <div class="col-8">

            <select class="custom-select" name="select_lang[]" id="select_lang" required="required" placeholder="Please select the relevant languages" multiple>
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
              <input id="trailer_id" name="trailer_id" pattern="(?:https?:\/\/)?(?:www\.|m\.)?youtu(?:\.be\/|be.com\/\S*(?:watch|embed)(?:(?:(?=\/[^&\s\?]+(?!\S))\/)|(?:\S*v=|v\/)))([^&\s\?]+)" title="Please supply a valid Youtube Video Link" type="text" class="form-control" required="required" aria-describedby="trailer_idHelpBlock">
            </div>
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
              <input id="release_date" name="release_date" type="text" class="form-control" aria-describedby="dobBlock" readonly="readonly" required>
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