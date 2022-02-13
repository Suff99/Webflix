<?php
$link = mysqli_connect('hostname', 'username', 'password', 'database');
if(!$link){
    die('Could not connect to MySQL: ' . mysqli_error());
}
