<!doctype html>
<html lang="en">
  <head>
<?php
require('includes/header.php');
?>
 </head>

  <body class="d-flex flex-column min-vh-100">
  <div class="header">
    
<?php
$identifier = "home";
require ('includes/database.php');
require('includes/nav.php');


createMeta("Home", "Home", "");
?>
</div>
<h1>Welcome to Webflix</h1>

<style>
.card {display:inline-block;}
 </style>

<div class="container-fluid">

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> Under Construction </p>
</div>

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
    <p> Got your popcorn ready?  </p>
</div>

<?php
 //$moviesQuery = "SELECT * FROM wf_releases order by RAND() LIMIT 4;";
 //$result = mysqli_query($link, $moviesQuery);
 // while ($movie = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
 //     createMovieCard($movie);
 // }
?>

<div id="release_carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">

        <div class="container">
  <?php echo '<h1>' . "test" . '</h1>'; ?>
  <div class="card mb-3" style="width: 90%; height: 70%;">
  <div class="row text-left justify-content-left align-items-left mx-0 px-0 text-black ">
    <div class="col-md-4">
<?php
    $moviesQuery = "SELECT * FROM wf_releases order by RAND() LIMIT 5;";
    $result = mysqli_query($link, $moviesQuery);
    $counter = 1;
    while($row = mysqli_fetch_array($result)){
?>
            <div class="carousel-item <?php if($counter <= 1){echo 'active'; } ?>">
                <a href="release.php?id=<?php echo $row['id']; ?>">
                    <img style="width:100%" alt="<?php echo $row['title']; ?>" src="<?php echo getPoster($row['api_id'], $row['release_type']); ?>"/>
                </a>
            </div>
<?php
    $counter++;
    }
?>
        </div>
    </div>
    </div> 
  

</div>






<?php
require('includes/footer.php');
?>
