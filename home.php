<?php
session_start();
include_once('../../db.php');
?>
          <div class="pagination pagination-centered"><ul></ul></div>
<table class="table table-striped paginated" style="background:#FFFFFF">
            <?php
             $con=mysql_connect("$server","$uname","$pwd") or die('could not connect to the server');
            mysql_selectdb("$db",$con);
            $query="Select * from trips where expire='0' order by `date`";
            $result=mysql_query($query);
            $temp=1;
            while($row=mysql_fetch_row($result))
            {
              if($temp==1)
              {
              echo "<thead>";
                echo "<th>Date</th><th>From</th><th>To</th><th>Creator</th><th>Details</th>";
              echo "</thead>";
                echo "<tbody>";
                $temp=0;
              }
                $dateObj= new Datetime($row[3]);
		$row[3]=$dateObj->format('d-M-Y');

              $query2="select * from users where fbId=".$row[4];
              $result2=mysql_query($query2);
              $creatorDetails=mysql_fetch_array($result2);
              echo "<tr><td>".$row[3]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$creatorDetails[1]."<a href='$creatorDetails[4]' target='_blank'><img src='./img/small-facebook-icon.png' style='margin-left:15px;' /></a></td><td><a data-trigger='hover' data-placement='left' class='tripDetails' data-toggle='popover' data-html='true' data-content=\"<p class=text-success><img src='$creatorDetails[5]'/>$creatorDetails[1]</p><p class='text-info'><i class='icon-bullhorn'></i>$row[8]<br/>$row[5]&nbspSeats available<br/>$row[7]</p>\" ><i class='icon-comment'></i></td></tr>";
            }
            if($temp==1)
            {
              echo "<p class='text-center'><span class='label label-important'>There are no trips to show!</span></p>";
            }
            else
            {
            echo "</tbody>";
            }
            mysql_close();
            ?>
          </table>
          <script>  $('.tripDetails').popover();
          </script>
<script>
$("table.paginated").each(function(){var e=0;var t=10;var n=$(this);n.bind("repaginate",function(){n.find("tbody tr").hide().slice(e*t,(e+1)*t).show()});n.trigger("repaginate");var r=n.find("tbody tr").length;var i=Math.ceil(r/t);var s=$(".pagination ul")[0];for(var o=0;o<i;o++){$('<li class="page-number"></li>').html('<a href="#">'+(o+1)+"</a>").bind("click",{newPage:o},function(t){e=t.data["newPage"];n.trigger("repaginate");$(this).addClass("active").siblings().removeClass("active")}).appendTo(s).addClass("clickable")}s.insertBefore(n).find("li.page-number:first").addClass("active")})
</script>