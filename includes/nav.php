<?php
require('util.php');
?>

<ul class="nav header">
<li class="nav-item">
              <a class="nav-link <?php if($identifier=='home')echo 'active'; ?>" href="index.php"><i class="bi bi-house-door-fill"></i> Home</a>
      </li>  

    <li class="nav-item">
              <a class="nav-link <?php if($identifier=='whatson')echo 'active'; ?>" href="titles.php"><i class="bi bi-projector-fill"></i> What's on? <span class="badge badge-light"><?php echo noNewReleases($link); ?></span></a>
      </li>  

      <li class="nav-item">
              <a class="nav-link <?php if($identifier=='about')echo 'active'; ?>" href="about.php"><i class="bi bi-info-circle-fill"></i> About</a>
      </li>
      
      <?php 
         session_start();

        if(strcmp($_SESSION['role'], "admin") == 0){
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
