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
require('includes/database.php');

session_start();


$id = htmlspecialchars($_GET["id"]);
$movieQuery = "SELECT * FROM wf_releases WHERE id = '$id';";
$movieResult = mysqli_query($link, $movieQuery);

$movie = null;
if (@mysqli_num_rows($movieResult) == 1) {
  $movie = mysqli_fetch_array($movieResult, MYSQLI_ASSOC);
}


$identifier = 'release';
require('includes/nav.php');
createMeta($movie['title'], $movie['description'], getPoster($movie['api_id'], $movie['release_type']));

?>
</div>
<br><br><br><br><br>

<h1 style="<?php echo (empty($movie) ? '' : 'display: none') ?>">Aw Snap!</h1>
<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black" style="<?php echo (empty($movie) ? '' : 'display: none') ?>">
    <p> The movie you are looking for could not be found... </p>
</div>
    

<?php 
  $_POST['release_id'] = $id;
  $_POST['user_id'] = $_SESSION['user_id'];
?>

<h1><?php echo $movie['title']?></h1>

<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black" style="<?php echo (!empty($movie) ? '' : 'display: none') ?>">
<div class="jumbotron" style="width: 80%">
<div class="container">
  <div class="row">

    <div class="col">      
      <img src="<?php echo getBackDrop($movie['api_id'], $movie['release_type']);?>" class="card-img" alt="<?php echo $movie['title']?>" style="width:100; height:100;">
    </div>

    <div class="col">
      <p class="card-text"><?php echo $movie['description']?></p>
      <p class="card-text"><small class="text-muted"><?php echo $movie['date']?></small></p>
      <div class="yt_container"><iframe class="responsive-iframe" width="560" height="315" src="https://www.youtube.com/embed/<?php echo $movie['trailer'];?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div> </div>
    
  </div>
</div>  
</div> </div> 

<br><br>
<div class="container text-center justify-content-center align-items-center text-black">

  <div class="jumbotron" style="width: 80%">
    <form action="includes/comment.php?release_id=<?php echo $id;?>"  method="post" class="alert-dismissible fade show" role="alert" style="<?php echo (!isset($_SESSION['user_id']) ? 'display: none' : '') ?>">
    <div class="form-group row text-left justify-content-left align-items-left mx-0 px-0 text-black">
    <label for="review" class="col-4 col-form-label" value="<?php echo $_POST['release_id']?>">Review</label> 
    <div class="col-8">
      <textarea id="comment" name="comment" cols="40" rows="5" class="form-control" aria-describedby="reviewHelpBlock" required="required"  value="<?php if (isset($_POST['comment'])) echo $_POST['comment']; ?>"></textarea> 
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4" input="">Rating</label> 
    <div class="col-8">
    <div class="rating"> 
      <input type="radio" name="rating" value="5" id="rating_5" required="required">
      <label for="rating_5">☆</label> <input type="radio" name="rating" value="4" id="rating_4" required="required">
      <label for="rating_4">☆</label> <input type="radio" name="rating" value="3" id="rating_3" required="required">
      <label for="rating_3">☆</label> <input type="radio" name="rating" value="2" id="rating_2" required="required">
      <label for="rating_2">☆</label> <input type="radio" name="rating" value="1" id="rating_1" required="required">
      <label for="rating_1">☆</label> </div>
      </div> 
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="user_id" type="submit" class="btn btn-primary" value="<?php echo $_POST['user_id']?>">Submit</button>
    </div>
  </div>
</form>

<div class="jumbotron" style=" <?php echo (isset($_SESSION['user_id']) ? 'display: none' : '') ?>">
<center> <a type="submit" href="login.php"><h5>Please login to leave a comment</h5></a></center>
</div> 

  </div>  
</div> 


<?php  



//$query = "SELECT * FROM `wf_releases`";  
//$result = mysqli_query($link, $query);   
//while ($row = mysqli_fetch_array($result)) {  
//  reviews($link, $row['api_id'], $row['release_type'], $row['id']);
//}

function reviews($link, $mv_id, $mv_t, $mv_local_id){  
    $url = file_get_contents("https://api.themoviedb.org/3/".$mv_t."/". $mv_id."/reviews?api_key=5cd5948d48817e54d6fb43905f56a80f");
    $data  = json_decode($url);
    if(!empty($data->results)){
    $content =  $data->results[0];
    if(!empty($content->content)){
    addComment($link, $content->content, rand(1,5), 5, $mv_local_id);
  }
}
}    


  $commentsQ = "SELECT * FROM wf_users JOIN wf_comments ON wf_comments.user_id = wf_users.user_id WHERE wf_comments.release_id=$id;";
  $comments = mysqli_query($link, $commentsQ);

  if (mysqli_num_rows($comments) > 0) {
    while ($comment = mysqli_fetch_array($comments, MYSQLI_ASSOC)) {
       
      $rating = "";
      for ($x = 1; $x <= $comment['rating']; $x++) {
        $rating = $rating . '<i class="bi bi-star-fill" style="rating"></i>';        
      }
      $rating = $rating . "<br>";
      
        echo '<div class="jumbotron" style="width: 50%;">
        <blockquote class="blockquote">
        <p class="mb-0">' . $rating . " " . $comment['message'].'</p>
        <footer class="blockquote-footer">@'.$comment['username'].'</footer>
      </blockquote></div>';
    }
  }
?>
    
  
  </div>




    <br><br><br><br><br>
</div>
<?php
require('includes/footer.php');
?>
