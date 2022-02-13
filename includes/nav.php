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
              <a class="nav-link <?php if($identifier=='whatson')echo 'active'; ?>" href="titles.php"><i class="bi bi-projector-fill"></i> What's on? <span class="badge badge-light"><?php echo noNewReleases($link); ?></span></a>
      </li>  

      <li class="nav-item">
              <a class="nav-link <?php if($identifier=='movies')echo 'active'; ?>" href="titles.php?type=movies"><i class="bi bi-film"></i> Movies</a>
      </li>

      <li class="nav-item">
              <a class="nav-link <?php if($identifier=='tv_shows')echo 'active'; ?>" href="titles.php?type=tv"><i class="bi bi-tv-fill"></i> TV Shows</a>
      </li>
      

      <style>
.navbar {
  overflow: hidden;
  background-color: #333;
  font-family: Arial, Helvetica, sans-serif;
}

.navbar a {
  float: left;
  font-size: 16px;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

.dropdown {
  float: left;
  overflow: hidden;
}

.dropdown .dropbtn {
  cursor: pointer;
  font-size: 16px;  
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit;
  margin: 0;
}

.navbar a:hover, .dropdown:hover .dropbtn, .dropbtn:focus {
  background-color: red;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  float: none;
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.dropdown-content a:hover {
  background-color: #ddd;
}

.show {
  display: block;
}
</style>


      <div class="dropdown">
   <button class="dropbtn" onclick="myFunction()">Dropdown
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-content" id="myDropdown">
    <a href="#">Link 1</a>
    <a href="#">Link 2</a>
    <a href="#">Link 3</a>
  </div>
  </div> 

  <script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(e) {
  if (!e.target.matches('.dropbtn')) {
  var myDropdown = document.getElementById("myDropdown");
    if (myDropdown.classList.contains('show')) {
      myDropdown.classList.remove('show');
    }
  }
}
</script>

      <?php 
         session_start();

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
