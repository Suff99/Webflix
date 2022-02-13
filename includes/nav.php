<?php
require('util.php');
?>

<ul class="nav header">
<li class="nav-item">
              <a class="nav-link <?php if($identifier=='home')echo 'active'; ?>" href="index.php"><i class="bi bi-house-door-fill"></i> Home</a>
      </li>  

      <li class="nav-item">
              <a class="nav-link <?php if($identifier=='about')echo 'active'; ?>" href="about.php"><i class="bi bi-info-circle-fill"></i> About</a>
      </li>

    <li class="nav-item">
              <a class="nav-link <?php if($identifier=='whatson')echo 'active'; ?>" href="titles.php"><i class="bi bi-projector-fill"></i> What's on?</a>
      </li>  

      <li class="nav-item">
              <a class="nav-link <?php if($identifier=='movies')echo 'active'; ?>" href="titles.php?type=movies"><i class="bi bi-film"></i> Movies</a>
      </li>

      <li class="nav-item">
              <a class="nav-link <?php if($identifier=='tv_shows')echo 'active'; ?>" href="titles.php?type=series"><i class="bi bi-tv-fill"></i> TV Shows</a>
      </li>
      
    
      <?php 
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        
        if(isset($_SESSION[ 'username' ]) && strcmp($_SESSION['role'], "admin") == 0){
          echo '<li class="nav-item"> <a class="nav-link' .  (($identifier=='admin')?' active"':"")   . '" href="admin.php"><i class="bi bi-person-plus-fill"></i> Admin</a></li>';
     }
        ?>

      <li class="nav-item">
      <?php 
    
       if(!isset($_SESSION[ 'username' ])){
       echo ' <a class="nav-link' .  (($identifier=='login')?' active"':"")  . '" href="login.php"><i class="bi bi-person-plus-fill"></i> Login</a>';
        } else {
         echo ' <a class="nav-link' .  (($identifier=='logout')?' active"':"")  . '" href="logout.php"><i class="bi bi-person-x-fill"></i> Logout</a>';
        }
      ?>
      </li>

    </ul>
    <br>
  <br>
  <br>