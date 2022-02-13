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

session_status() === PHP_SESSION_ACTIVE ?: session_start();


$id = htmlspecialchars($_GET["id"]);
$movieQuery = "SELECT * FROM wf_releases WHERE id = '$id';";
$movieResult = mysqli_query($link, $movieQuery);

$movie = null;
if (@mysqli_num_rows($movieResult) == 1) {
  $movie = mysqli_fetch_array($movieResult, MYSQLI_ASSOC);
}


$identifier = 'release';
require('includes/nav.php');
createMeta($movie['title'], json_decode($movie['information'])->tagline, json_decode($movie['images'])->poster);

?>
</div>
<br><br><br><br><br>

<h1 style="<?php echo (empty($movie) ? '' : 'display: none') ?>">Aw Snap!</h1>
<div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black" style="<?php echo (empty($movie) ? '' : 'display: none') ?>">
    <p> The title you are looking for could not be found... </p>
</div>
    

<?php 
  $_POST['release_id'] = $id;
  $_POST['user_id'] = $_SESSION['user_id'];
?>

<h1><?php echo $movie['title']?></h1>

<div class="row text-left justify-content-center align-items-left mx-0 px-0 text-black" style="<?php echo (!empty($movie) ? '' : 'display: none') ?>">
  <div class="jumbotron" style="width: 80%">

<div class="container">
  <div class="row">

    <div class="col">      
      <img src="<?php echo json_decode($movie['images'])->backdrop;?>" class="card-img" alt="<?php echo $movie['title']?>" style="width:100; height:100;">
    </div>


    <div class="col">
      <p class="card-text"><?php echo json_decode($movie['information'])->description ?></p>
      <div class="yt_container"><iframe class="responsive-iframe" width="560" height="315" src="https://www.youtube.com/embed/<?php echo $movie['trailer'];?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div> 
   
<br>
    <table class="table table-striped">
  <thead class="thead-dark">
    <p> Title Information </p>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Supported Languages</th>
      <?php 
      $langString = "";
      $langs = json_decode($movie['information'])->languages;
      foreach ($langs as $lang) {
        $langString .= $lang . " ";
    }

    echo "<td>$langString</td>";
      ?>
    </tr>
    <tr>
      <th scope="row">Released</th>
      <td> 
        <?php 
      echo $movie['date']?></td>
    </tr>
    <tr>
      <th scope="row">Categories</th>
      <td>TBC</td>
    </tr>
    <tr>
      <th scope="row">Watch</th>
      <td> <?php echo '<a style="color: #2c0a0a;" href="' .empty($movie['watch_link']) ? "Coming soon" : $movie['watch_link'] . '">' .$movie['watch_link'] . '</a>'?></td>
    </tr>
  </tbody>
</table>
    
    
      </div>
    
</div>  
</div> 
</div> 

<div class="container text-center justify-content-center align-items-center text-black">

  <div class="jumbotron" style="width: 100%">
    <form action="includes/comment.php?release_id=<?php echo $id;?>"  method="post" class="alert-dismissible fade show" role="alert" style="<?php echo (!isset($_SESSION['user_id']) ? 'display: none' : ' ') ?>">
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

</div> 


<div class="container text-left justify-content-center align-items-center text-black" id="comments">
<?php  

$currentPage = 0; 

if (!isset ($_GET['page']) ) {  
  $currentPage = 1;  
} else {  
  $currentPage = $_GET['page'];  
}  

  $commentsQ = "SELECT * FROM wf_users JOIN wf_comments ON wf_comments.user_id = wf_users.user_id WHERE wf_comments.release_id=$id;";
  $comments = mysqli_query($link, $commentsQ);

  $numOfComments = mysqli_num_rows($comments); 
  $number_of_page = ceil ($numOfComments / 3); 
  $page_first_result = ($currentPage-1) * 3;  
  $query = "SELECT * FROM wf_users JOIN wf_comments ON wf_comments.user_id = wf_users.user_id WHERE wf_comments.release_id=$id" . " LIMIT " . $page_first_result . ',' . 3;  
  $comments = mysqli_query($link, $query);   



  if (mysqli_num_rows($comments) > 0) {
    while ($comment = mysqli_fetch_array($comments, MYSQLI_ASSOC)) {
       
      $rating = "";
      for ($x = 1; $x <= $comment['rating']; $x++) {
        $rating = $rating . '<i class="bi bi-star-fill" style="rating"></i>';        
      }
      $rating = $rating . "<br>";
      
      

      echo '<div class="row" style="width:105%"><div class="card col-sm">
      <div class="card-header">'.
      $rating
      .'</div>
      <div class="card-body">
        <blockquote class="blockquote mb-0">
          <p>'.$comment['message'].'</p>
          <footer class="blockquote-footer">'.$comment['username'].'</footer>';
          if($comment['user_id'] == $_SESSION['user_id']) {
            echo '<a class="nav-link text-black" href="includes/delete_comment.php?release='.$id.'&comment='.$comment['comment_id'] .'"><i class="bi bi-trash-fill"></i> Delete</a>';
          }
          echo '</blockquote>
      </div>
      </div></div><br>';
    }
  }
?>

<nav class="row g-0 text-center justify-content-center align-items-center mx-0 px-0">
  <ul class="pagination">
<?php
 


for($page = 1; $page<= $number_of_page; $page++) {  
  echo '<li class="page-item' . (($page== $currentPage)?' active':"") . '"><a class="page-link" href="release.php?id='.$id.'&page=' .$page.'#comments">' .$page.'</a></li>';
} 
?>
 </ul>
</nav>

      </div>
    </div>
  </div>


<br><br><br><br><br>
</div>
<?php
require('includes/footer.php');
?>
