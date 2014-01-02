<?php
session_start();
require_once('../../db.php');
define('TRIPSLIMIT',5);
if($_SESSION['isLoggedIn']!=1)
{
echo "<p class='text-center'><span class='label label-important'>STOP RIGHT THERE! Acting smart,huh? Log in first.</span></p>";
  exit;

}
$err=array();
$contact=$date=$from=$to=$seats=$note='';
if(!isset($_GET['contact']))array_push($err,"contact");else{
if(preg_match("/^[0-9]{10,11}+$|[1]{0}/", trim($_GET['contact'])) === 0)
array_push($err,"contact");
else
{
if(trim($_GET['contact'])=='')$_GET['contact']='Not Provided';
$contact=$_GET['contact'];
}
}
$flag=1; //error by default
if(!isset($_GET['date']))array_push($err,"date");else{
if(!(preg_match("/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/", trim($_GET['date'])) === 0))
{
date_default_timezone_set('Asia/Kolkata');
$d=array();
$d=explode('-',$_GET['date']);
if(checkdate($d[1],$d[0],$d[2]))
{
$_GET['date']=$d[1]."/".$d[0]."/".$d[2];

if((strtotime($_GET['date'])-time()>=0)||(time()-strtotime($_GET['date'])<86400))
{
$flag=0; // DATE IS CORRECT

if(strlen($d[0])==1)
$d[0]="0".$d[0];

if(strlen($d[1])==1)
$d[0]="0".$d[1];


}
}
}
if($flag==1)
array_push($err,"date");
else
$date=$d[0]."-".$d[1]."-".$d[2];
}


if(!isset($_GET['from']))array_push($err,"from");else{
if(preg_match("/^[a-zA-Z0-9 ,.-]{3,35}+$/",trim($_GET['from']))===0)
{
array_push($err,"from");
}
else
$from=$_GET['from'];
}

if(!isset($_GET['to']))array_push($err,"to");else{
if(preg_match("/^[a-zA-Z0-9 ,.-]{3,35}+$/",trim($_GET['to']))===0)
{
array_push($err,"to");
}
else
$to=$_GET['to'];
}


if(!isset($_GET['seats']))array_push($err,"seats");else{
if(preg_match("/^[0-9]{1,2}+$/",trim($_GET['seats']))===0)
{
array_push($err,"seats");
}
else
$seats=$_GET['seats'];
}

if(!isset($_GET['note']))array_push($err,"note");else{
if(preg_match("/^[a-zA-Z0-9 ,.\-_@:!\r\n]{0,75}+$/",trim($_GET['note']))===0)
{
array_push($err,"note");
}
else
{
if(trim($_GET['note'])=='')$_GET['note']='No Details';
$note=$_GET['note'];
}
}

if(sizeof($err)==0)
{
$con=mysql_connect("$server","$uname","$pwd") or die('could not connect to the server');
mysql_selectdb("$db",$con) or die('database could not be selected');
$query="select noOfTrips from users where fbId=".$_SESSION['userData']['id'];
$result=mysql_query($query);
$row=mysql_fetch_array($result);
if($row[0]>=TRIPSLIMIT)
{
	echo 'trips limit reached';
	exit;
}

$query="INSERT INTO `trips` (`A`, `B`, `date`, `owner`,`seats`,`note`,`contact`) VALUES ('$from', '$to', '$date','".$_SESSION['userData']['id']."','$seats','$note','$contact')";
mysql_query($query) or die('not inserted '.mysql_error());
$query="update users set noOfTrips=noOfTrips+1 where fbId=".$_SESSION['userData']['id'];
mysql_query($query);
mysql_close();
echo "success";
}
else
{
$jsonObj=json_encode($err);
//$jsonObj="{\"err\":".$jsonObj."}";
echo $jsonObj;
}
?>