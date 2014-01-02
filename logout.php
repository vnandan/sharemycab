<?php
 session_start();

 if(!(isset($_SESSION['isLoggedIn'])&&$_SESSION['isLoggedIn']==1))
 {
 	header('Location:./index.php');
 }

 session_destroy();
 header('Location:./index.php');
 ?>