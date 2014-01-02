<?php
require_once('../../db.php');
$gracePeriod=24*60*60;
$con=mysql_connect("$sever","$uname","$pwd");
mysql_select_db($db,$con);
$query="SELECT id,date from trips where expire=0";
$result=mysql_query($query);
date_default_timezone_set('Asia/Kolkata');
while($row=mysql_fetch_row($result))
{
		$t=strtotime($row[1]);
		$now=mktime();
		if($now-$t>$gracePeriod)
		{
		$query="update trips set `expire`='1' where id=".$row[0];
		mysql_query($query);
		$query="update users set noOfTrips=noOfTrips-1 where fbId=".$row[4];
		mysql_query($query);
		}
}
?>