<?php
function register()
{
	global $server;
	global $uname;
	global $pwd;
	global $db;
	if($_SESSION['isLoggedIn']!=1)
	{
	header('Location: ./index.php');
	exit;
	}
	$con=mysql_connect("$server","$uname","$pwd") or die('could not connect to the server');
	mysql_selectdb("$db",$con);
	$query="select * from users where fbId=".$_SESSION['userData']['id'];
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	if(!$row[0])
	{
	    $_SESSION['userData']['dp']="https://graph.facebook.com/".$_SESSION['userData']['id']."/picture";
	    $query="INSERT INTO users(`name`,`email`,`fbId`,`link`,`dp`) values('".$_SESSION['userData']['name']."','".$_SESSION['userData']['email']."','".$_SESSION['userData']['id']."','".$_SESSION['userData']['link']."','".$_SESSION['userData']['dp']."')";
	    mysql_query($query);
	    //echo $server.$uname.$pwd;
	}
mysql_close();
}
?>