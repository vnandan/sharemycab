<?php
session_start();
include_once('../../db.php');
if($_SESSION['isLoggedIn']!=1)
{
echo "<p class='text-center'><span class='label label-important'>Its right there! The login button. Login to use this page.</span></p>";
  exit;
}
?>

<table class="table table-striped" style="background:#FFFFFF">
            <?php
            $con=mysql_connect("$server","$uname","$pwd") or die('could not connect to the server');
            mysql_selectdb("$db",$con);
            $query="Select * from trips where expire='0' AND `owner` =".$_SESSION['userData']['id'];
            $result=mysql_query($query);
            $temp=1;
            while($row=mysql_fetch_row($result))
            {
              if($temp==1)
              {
                echo "<th>Date</th><th>From</th><th>To</th><th>Seats</th><th>Contact</th><th>Details</th><th>Delete</th>";
                $temp=0;
              }
              echo "<tr><td>".$row[3]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[5]."</td><td>".$row[8]."</td><td>".$row[7]."</td><td id='trash$row[0]'><i style='cursor:pointer;' class='icon-trash' onClick='deleteTrip(\"$row[0]\")'></i></td>";
            }
            if($temp==1)
            {
              echo "<p class='text-center'><span class='label label-important'>You have not created any trips!</span></p>";
            }
            mysql_close();
            ?>
          </table>