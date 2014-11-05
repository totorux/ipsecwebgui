<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
include 'login.php';
echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
        echo "</br>&nbsp;&nbsp;&nbsp;&nbsp;";
        $time = $_GET['time'];
        if (!isset($time))
        {
                $time="today";
        }

        foreach (array('today', 'week', 'month', 'year') as $logdate)
        {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                if ($logdate == "today")
                {
                        echo '<a class="bodytop" href="usrlog.php?time='.$logdate.'" title="Today">&nbsp;&nbsp;Today&nbsp;&nbsp;</a>';
                }
                if ($logdate == "week")
                {
                        echo '<a class="bodytop" href="usrlog.php?time='.$logdate.'" title="This Week">&nbsp;&nbsp;This Week&nbsp;&nbsp;</a>';
                }
                if ($logdate == "month")
                {
                        echo '<a class="bodytop" href="usrlog.php?time='.$logdate.'" title="This Month">&nbsp;&nbsp;This Month&nbsp;&nbsp;</a>';
                }
                if ($logdate == "year")
                {
                        echo '<a class="bodytop" href="usrlog.php?time='.$logdate.'" title="This Month">&nbsp;&nbsp;This Year&nbsp;&nbsp;</a>';
                }

        }
		$userlvl=$_SESSION['user_level'];
                $day=date("d");
                $week=date("W");
                $month=date("m");
                $year=date("o");

		if ($time == "today")
		{
			echo '<h2 class="log">Today log:</h2>';
			$logsql="select * from vpnlog where day='$day' and month='$month' and year='$year' and user_level <= '$userlvl' order by day desc, week desc,month desc, year desc, hour desc";
		}
        	if ($time == "week")
	        {
        	        echo '<h2 class="log">This Week log:</h2>';
			$logsql="select * from vpnlog where week='$week' and month='$month' and year='$year' and user_level <= '$userlvl' order by day desc, week desc,month desc, year desc, hour desc";
	        }
        	if ($time == "month")
	        {
        	        echo '<h2 class="log">This Month log:</h2>';
			$logsql="select * from vpnlog where month='$month' and year='$year' and user_level <= '$userlvl' order by day desc, week desc,month desc, year desc, hour desc";
	        }
                if ($time == "year")
                {
                        echo '<h2 class="log">This Year log:</h2>';
			$logsql="select * from vpnlog where year='$year' and user_level <= '$userlvl' order by day desc, week desc,month desc, year desc, hour desc";
                }
		$logreq=mysql_query($logsql) or die(mysql_error());
			echo '<!-- Table markup-->';
			echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
			echo '<th scope="col" class="rounded-company">&nbsp;User&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;Date&nbsp;</th><th scope="col" class="rounded-q4">&nbsp;Action&nbsp;</th>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
			echo '<td colspan="2" class="rounded-foot-left"></td>
			<td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
                        echo '<!-- Table body -->';
                        echo '<tbody>';
                while($logres=mysql_fetch_assoc($logreq))
                {
			$lastday=$logres['day'];
			$lastmonth=$logres['month'];
			$lastweek=$logres['week'];
			$lastyear=$logres['year'];
			$hour=$logres['hour'];
			$ip=$logres['IP'];
                        $usrsql="select user_name from users where id='".$logres['user']."'";
	                $usrreq=mysql_query($usrsql) or die(mysql_error());
                        while($usrres=mysql_fetch_assoc($usrreq))
                        {
				$lastuser=$usrres['user_name'];
			}
			echo "<tr class='site'><td class='left'>&nbsp;".$lastuser."&nbsp;</td>";
			echo "<td class='center'>&nbsp;".$lastday."-".$lastmonth."-".$lastyear." at ".$hour."&nbsp;</td>";
			if( $logres['type'] == "sitereload" )
			{
				echo "<td>&nbsp; Reload site: ".$logres['sitename']."&nbsp;</td>";	
			}
			elseif( $logres['type'] == "login" )
			{
				echo "<td>&nbsp;User login: ".$ip."&nbsp;</td>";
			}
			elseif( $logres['type'] == "logout" )
                        {
                                echo "<td>&nbsp;User logout&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "portread" )
                        {
                                echo "<td>&nbsp;Port scan read ".$logres['sitename']."&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "porttest" )
                        {
                                echo "<td>&nbsp;Port scan test ".$logres['sitename']."&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "vpnadd" )
                        {
                                echo "<td>&nbsp;VPN add: ".$logres['sitename']."&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "vpnadel" )
                        {
                                echo "<td>&nbsp;VPN del: ".$logres['sitename']."&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "vpnmod" )
                        {
                                echo "<td>&nbsp;VPN modify: ".$logres['sitename']."&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "siteadd" )
                        {
                                echo "<td>&nbsp;New site add: ".$logres['sitename']."&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "sitedel" )
                        {
                                echo "<td>&nbsp;Site del: ".$logres['sitename']."&nbsp;</td>";
                        }
//
                        elseif( $logres['type'] == "useradd" )
                        {
                                echo "<td>&nbsp;Add user: ".$logres['sitename']."&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "userdel" )
                        {
                                echo "<td>&nbsp;Del user: ".$logres['sitename']."&nbsp;</td>";
                        }
                        elseif( $logres['type'] == "usermod" )
                        {
                                echo "<td>&nbsp;Modify user: ".$logres['sitename']."&nbsp;</td>";
                        }

		}
		echo '</tbody>';
                echo '</table>';
}
else
{
        header("Location: index.php");
}
echo '</div><!-- #content -->';

echo '<div id="sidebar">';

	include 'menu.php';

echo '</div><!-- #sidebar -->
      </div><!-- #center -->';

include 'footer.php';
?>
