<!doctype html>
<html lang="en">
  <head>
<?php
require('includes/header.php');
?>
 </head>


 <div class="header">
      <?php
$identifier = "admin";
require ('includes/database.php');
require('includes/nav.php');

if (@session_id() == "") @session_start();
createMeta("Admin Panel", "Create and Edit", "");
lockPageFromUser();
?>


</div>
<body class="d-flex flex-column min-vh-100">  
<h1>Admin Panel</h1>
  <div class="container">
    <div class="col-sm">
<form action="login.php" method="post" class="alert-dismissible fade show" role="alert" >
  <div class="form-group row">
    <label for="title" class="col-4 col-form-label">Title</label> 
    <div class="col-8">
      <input id="title" name="title" placeholder="Star Wars" type="text" class="form-control" required="required">
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
     if (!$result) {
      printf("Error: %s\n", mysqli_error($link));
      exit();
  }
       while ($category = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          echo '<div class="custom-control custom-checkbox custom-control-inline">
          <input name="categories" id="'.$category['name'].'" type="checkbox" checked="checked" class="custom-control-input" value="'.$category['name'].'" required="required"> 
          <label for="'.$category['name'].'" class="custom-control-label">'.$category['name'].'</label>
        </div>';
     }
    ?>
    </div>
  </div>
  <div class="form-group row">
    <label for="trailer_id" class="col-4 col-form-label">Trailer ID</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-youtube-play"></i>
          </div>
        </div> 
        <input id="trailer_id" name="trailer_id" type="text" class="form-control" required="required" aria-describedby="trailer_idHelpBlock">
      </div> 
      <span id="trailer_idHelpBlock" class="form-text text-muted">Please supply valid Youtube ID</span>
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
      <span id="watch_linkHelpBlock" class="form-text text-muted">Please give a watch URL</span>
    </div>
  </div> 

  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
</div>    </div>




<?php
require('includes/footer.php');
?>
