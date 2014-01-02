<?php
session_start();
require_once('../../db.php');
require_once('./register.php');

// Facebook data

if(isset($_GET['noLogin'])&&$_GET['noLogin']==1)
 {$noLogin=1;unset($_GET['noLogin']);}
else
  {$noLogin=0;unset($_GET['noLogin']);}
    
require './facebook.php';


$facebook = new Facebook(array(
  'appId'  => '194958727327528',
  'secret' => '9df2570775f2bf7f7951969a02badf1d',
));

$fuser = $facebook->getUser();

if ($fuser) {
  try {
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $fuser = null;
  }
}

if (!$fuser) {
  $loginUrl = $facebook->getLoginUrl(array(
 'scope' => 'email'
));
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Find friends to travel with!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find trips to travel with friends">
    <meta name="author" content="Vipul Nandan">

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Quicksand|Amatic+SC:700' rel='stylesheet' type='text/css'>
      <link href="./css/styles.css" rel="stylesheet">

<style>
@media (max-width:980px)
{
body{margin-top:-50px;}
.navbar-fixed-top
{
margin-right:-20px;
margin-left:-20px;
}
}
</style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>

 </style>
  </head>

  <body>

<script>
if (window.location.hash == '#_=_') {
    window.location.hash = ''; // for older browsers, leaves a # behind
    history.pushState('', document.title, window.location.pathname); // nice and clean
    e.preventDefault(); // no page reload
}
</script>
<?php


if(!$fuser&&$noLogin==0) //user is not logged in, show login button
{
  $_SESSION['isLoggedIn']=0;
  ?>
   <div class="container-fluid">
	<p class="text-center" style="font-family: 'Quicksand', sans-serif;font-size:8em;">Travel With Friends</h1>
    <div class="span4 offset4" style="background:rgba(0,0,0,0);margin-top:10%;">
      <hr/>
  <a href="<?php echo $loginUrl ?>" style="text-decoration:none;"><button id="facebookBtn" class="btn btn-block"><img id="fbLogo" src="./img/fb.png"/>Sign In with Facebook</button></a>
	
	<hr/>
      <a class="btn btn-large btn-primary btn-block" href="./index.php?noLogin=1">Skip, and continue</a>
    </div>
  </div>
<?php
  //echo '<a href="'.$loginUrl.'">Login with Facebook</a>';
} 
else // user logged in 

  if ($fuser||$noLogin==1) {  // User logged in through facebook 
    if($fuser){$_SESSION['isLoggedIn']=1;$_SESSION['userData']=array();$_SESSION['userData']=$user_profile;register();}
    if(!$fuser&&$noLogin==1)$_SESSION['isLoggedIn']=2;
if($_SESSION['isLoggedIn']==0){$_SESSION['isLoggedIn']=1;header('Location:./index.php');exit;}

?>
<script>
  $(document).ready(function(){
   $tableData=$("#tripTable").html();
    });
</script>
<!--CODE FOR USER PANEL-->
<div id="createForm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="createFormBtn">&times;</button>
    <h3 style="display: inline;">Create trip</h3>
    <div id="createBtnDiv" style="display:inline;"><button class="btn" onclick="submitForm();" id="createBtn">Save trip</button></div>
  </div>
  <div class="modal-body" style="overflow-y:scroll;">
    
    <form class="form-horizontal" id="tripForm" action="" method="post">
<div class="control-group"><label class="control-label" for="name">Name</label><div class="controls"><input type="text" maxlength="12" name="name" disabled id="name"  placeholder="<?php echo $user_profile['name']?>" /></div></div>
<div class="control-group"><label class="control-label" for="contact">Contact</label> <div class="controls"><input type="text" maxlength="10" name="contact" id="contact" placeholder="Contact no." /></div></div>
<div class="control-group"><label class="control-label" for="email">Email</label> <div class="controls"><input type="email" name="email" id="email"  placeholder="<?php echo $user_profile['email']; ?>" disabled/></div></div>
<div class="control-group"><label class="control-label" for="date">Date</label> <div class="controls"><input type="text" name="date" id="date"  placeholder="dd-mm-yyyy"/></div></div>
<div class="control-group"> <label class="control-label" for="from">From</label> <div class="controls"><input type="text" name="from"  id="from"  placeholder="3-35 characters" maxlength="25" /></div></div>
<div class="control-group"> <label class="control-label" for="to">To</label> <div class="controls"><input type="text" name="to" id="to"  placeholder="3-35 characters" maxlength="25" /></div></div>
<div class="control-group"><label class="control-label" for="seats">Vacancy</label> <div class="controls"><input type="text" name="seats" id="seats"  placeholder="1-20" /></div></div>
<div class="control-group"><label class="control-label" for="note">Note</label> <div class="controls"><input type="text" name="note" id="note" maxlength="75" placeholder="Limit: 50 characters"></textarea></div></div>
</form>
    
  </div>
  </div>
     <div class="navbar navbar-fixed-top" id="top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a class="brand" href="#">Travel With Friends</a>          
            
          <div class="nav-collapse collapse">
              <ul class="nav">
              <li id="home" class="active" onClick="showHome()"><a href="#"><i class="icon-home"></i>Home</a></li>
              <li id="myTrips" ><a href="#" onClick="myTrips()"><i class="icon-tasks"></i>My Trips</a></li>  
		<li id="create" ><a <?php if($_SESSION['isLoggedIn']==1){ echo 'href="#createForm" role="button" data-toggle="modal"';}?> ><i class="icon-pencil"></i>Create</a></li>
		<?php if($_SESSION['isLoggedIn']==1){echo '<li id="logout" ><a href="./logout.php"><i class="icon-off"></i>Logout</a></li>';} ?>
            </ul>
                 <iframe style="position:absolute;right:-165px;top:14px;z-index:1040;height:25px;" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FUnstableCode4all&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=193774037451640" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:44px; height:21px;" allowTransparency="true"></iframe>  
             <form class="form-search" style="display:inline;">
              <div class="input-append">
              
              <input type="text" id="searchBox" class="search-query span3" style="margin-right:0px;" placeholder="Find trips by places or dates (dd-mm)">
             
              <button class="btn" id="searchBtn">Search</button>
              </div>
              
           <?php if($_SESSION['isLoggedIn']!=1)
            {
              ?>
              <div class="span3 pull-right">
        <?php
            echo "<div class='span3 pull-right'>";
               echo "<a href=".str_ireplace('noLogin%3D1','noLogin%3D0',$loginUrl)." class='btn btn-danger'>Login you Alien!</a></span>";
          }
          ?>
        </div>

            </form>

          </div><!--/.nav-collapse -->

        </div>

      </div>
    </div>
    <div class="alert alert-error" id="limitReached" style="display:none;position:absolute;top:45%;left:0px;right:0px;margin:auto;width:30%;">
    <p class="text-center"><strong>You have reached the maximtrips you can create. Try deleting a few from My Trips. </strong></p>
    <a class="btn btn-danger btn-small pull-right" href="#" onClick="$('#limitReached').hide();">Ok!</a>
    </div>
    <div class="alert alert-error" id="limitReached" style="display:none;position:absolute;top:45%;left:0px;right:0px;margin:auto;width:30%;">
    <p class="text-center"><strong>You have reached the maximtrips you can create. Try deleting a few from My Trips. </strong></p>
    <a class="btn btn-danger btn-small pull-right" href="#" onClick="$('#limitReached').hide();">Ok!</a>
    </div>

    <div class="container-fluid" id="content">

      <div class="alert alert-success" id="formSuccess" style="display:none;position:absolute;top:45%;left:0px;right:0px;margin:auto;width:30%;">
      <p class="text-center"><strong>Your trip was successfully created !</strong></p>
      <a class="btn btn-success btn-small pull-right" href="#" onClick="$('#formSuccess').hide();">Ok!</a>
      </div>
        <div class="hero-unit" style="background:#FFF;font-family: 'Quicksand', sans-serif;">
        
<!--font-family: ;'Shadows Into Light', cursive;-->
          <button type="button" class="close" onClick="$('.hero-unit').hide();">x</button>
      <div class="row-fluid">
        <div class="span6">
        <h2>Howdy <?php if($_SESSION['isLoggedIn']==1)echo $user_profile['first_name']?>!</h2>
          <p>Here you can find people to travel with.</p>
          <ul>
            <li>Share cabs</li>
            <li>Travel home or back</li>
            <li>Plan a trip</li>
	<hr/>
            Anything you want!
        </div>
      
        <div class="span6">
          <h4>Instructions for dummies</h4>
          <ol class="nav nav-list">
            <li>Login to your Facebook account. </li>
            <li>Click on Create to create a trip.</li>
            <li>Find places or dates you want to travel.</li>
            <li>Now go and contact others for confiming through FB or cell.</li>
            <li class="divider"></li>
          </ol>
        <?php if($_SESSION['isLoggedIn']!=1){?>  <a class="btn btn-primary pull-right" style="font-family:sans-serif;" href="<?php echo str_ireplace('noLogin%3D1','noLogin%3D0',$loginUrl); ?>"><img src="./img/fb.png" /> FB login</a> <?php } 
        else {   ?>    <button class="btn btn-primary pull-right" onClick="$('.hero-unit').hide();">Shut Up!</button> <?php } ?>
    </a>
        </div>
      </div>
    </p>
    </div>
        <div id="tripTable">
         <script>
		$(document).ready(function(){showHome();});
         </script>
        </div>
        
        
        
  </div>


<?php 
} ?>
 <script src="./js/functions.js"></script>

 <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="./js/html5shiv.js"></script>
    <![endif]-->
    
</body></html>