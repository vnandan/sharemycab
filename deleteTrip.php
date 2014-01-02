<?php
session_start();
include_once('../../db.php');
if($_SESSION['isLoggedIn']!=1||!is_numeric($_POST['id']))
{
  finish();
}

$con=mysql_connect("$server","$uname","$pwd");
mysql_select_db("$db",$con);
$query="Select owner from trips where id=".$_POST['id'];
$result=mysql_query($query);
$row=mysql_fetch_array($result);
if($row[0]==$_SESSION['userData']['id'])
{
	$query="update trips set `expire`='1' where `id`=$_POST[id]  AND `owner`=".$_SESSION['userData']['id'];
	mysql_query($query);
	$query="update users set noOfTrips=noOfTrips-1 where fbId=".$_SESSION['userData']['id'];
	mysql_query($query);
		echo 'deleted';
		mysql_close();
}

function finish()
{
	echo 'not deleted';
	exit;
}
?>