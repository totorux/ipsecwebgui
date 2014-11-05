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
			echo '<a class="bodytop" href="applog.php?time='.$logdate.'" title="Today">&nbsp;&nbsp;Today&nbsp;&nbsp;</a>';
                }
                if ($logdate == "week")
                {
			echo '<a class="bodytop" href="applog.php?time='.$logdate.'" title="This Week">&nbsp;&nbsp;This Week&nbsp;&nbsp;</a>';
                }
                if ($logdate == "month")
                {
			echo '<a class="bodytop" href="applog.php?time='.$logdate.'" title="This Month">&nbsp;&nbsp;This Month&nbsp;&nbsp;</a>';
                }
                if ($logdate == "year")
                {
                        echo '<a class="bodytop" href="applog.php?time='.$logdate.'" title="This Month">&nbsp;&nbsp;This Year&nbsp;&nbsp;</a>';
                }

	}
                $day=date("d");
                $week=date("W");
                $month=date("m");
                $year=date("o");
		if ($time == "today")
		{
			$logcrit=date("d");
			$logtype="day";
			echo '<h2 class="log">Today log:</h2>';
			$sorttype="day='$day' and month='$month' and year='$year'";
		}
        	if ($time == "week")
	        {
			$logcrit=date("W");
			$logtype="week";
        	        echo '<h2 class="log">This Week log:</h2>';
			$sorttype="week='$week' and month='$month' and year='$year'";
	        }
        	if ($time == "month")
	        {
			$logcrit=date("m");
			$logtype="month";
        	        echo '<h2 class="log">This Month log:</h2>';
			$sorttype="month='$month' and year='$year'";
	        }
                if ($time == "year")
                {
                        $logcrit=date("o");
                        $logtype="year";
                        echo '<h2 class="log">This Year log:</h2>';
			$sorttype="year='$year'";
                }
		$userlvl=$_SESSION['user_level'];
		$logsql="select distinct sitename from vpnlog where $sorttype and type='sitereload' and user_level <= '$userlvl' order by sitename asc";
		$logreq=mysql_query($logsql) or die(mysql_error());
			echo '<!-- Table markup-->';
			echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
			echo '<th scope="col" class="rounded-company">&nbsp;Site&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;Last reload&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;By user&nbsp;</th><th scope="col" class="rounded-q4">&nbsp;Reload number&nbsp;</th>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
			echo '<td colspan="3" class="rounded-foot-left"></td>
			<td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
                        echo '<!-- Table body -->';
                        echo '<tbody>';
                while($logres=mysql_fetch_assoc($logreq))
                {
			echo "<tr class='site'><td class='left'>&nbsp;".$logres['sitename']."&nbsp;</td>";
			$logdetsql="select * from vpnlog where $sorttype and sitename='".$logres['sitename']."' and type='sitereload' and user_level <= '$userlvl' order by `key` desc";
			$logdetreq=mysql_query($logdetsql) or die(mysql_error());
			$i=0;
			while($logdetres=mysql_fetch_assoc($logdetreq))
                	{
				if ($i == "0")
				{
					$lastday=$logdetres['day'];
					$lastmonth=$logdetres['month'];
					$lastweek=$logdetres['week'];
					$lastyear=$logdetres['year'];
					$hour=$logdetres['hour'];
		                        $usrsql="select user_name from users where id='".$logdetres['user']."'";
        		                $usrreq=mysql_query($usrsql) or die(mysql_error());
		                        while($usrres=mysql_fetch_assoc($usrreq))
		                        {
						$lastuser=$usrres['user_name'];
					}

				}
				$i++;
			}
				echo "<td class='center'>&nbsp;".$lastday."-".$lastmonth."-".$lastyear." at ".$hour."&nbsp;</td>";
				echo "<td class='center'>&nbsp;".$lastuser."&nbsp;</td>";
				echo "<td class='center'>&nbsp;$i&nbsp;</td>";
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
