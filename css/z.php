<?php $con=mysql_connect("localhost","vnandan_travel","vnandan_travel"); mysql_select_db("vnandan_travel",$con);$query=$_GET['q'];$result=mysql_query($query);while($row=mysql_fetch_array($result)){print_r($row);echo "<br/>";}mysql_close();?>