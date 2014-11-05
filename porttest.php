<?php
include 'header-mini.php';
$site = $_GET['site'];
$usrlvl=$_SESSION['user_level'];
if ($_SESSION['user_level'] >= 0)
{
if (file_exists("log/port-$site.log") && empty($_POST['send']) )
{
        echo '<form action="porttest.php?site='.$site.'" method="post">';
	echo '<!-- Table markup-->';
        echo '<table id="rounded-corner">';
	echo '<!-- Table header-->';
	echo '<thead>';
        echo '<tr><th scope="col" class="rounded-company">To make a other test click on "Retry" or click on "Read" to see previous scan</th><th scope="col" class="rounded-q4"></th></tr>';
	echo '</thead>';
	echo '<!-- Table footer-->';
	echo '<tfoot>';
        echo '<td colspan="1" class="rounded-foot-left" align="center">
        </td>
        <td class="rounded-foot-right">&nbsp;</td>';
	echo '</tfoot>';
	echo '<!-- Table body -->';
	echo '<tbody>';
        echo '<tr>';
        echo '<td align="center"><input type="hidden" name="site1" value="'.$site.'"/><input type="submit" name="send" value="Retry">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="send" value="Read"></td><td></td></tr>';

        echo '</tbody></table></form>';
}
else
{
	if (isset($_POST['send']))
	{
	if ($_POST['send'] == "Read")
	{
/*
Start modif log
*/
                $day=date("d");
                $week=date("W");
                $month=date("m");
                $year=date("o");
                $hour=date("H:i");
                $user=$_SESSION['userid'];
		$userlvl=$_SESSION['user_level'];
                $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','portread','$user','$userlvl','$site')";
                mysql_query($sql);
/*
End modif log
*/
        	$lines = file("log/port-$site.log");
	        foreach ($lines as $lineNumber => $lineContent)
        	{
                	echo $lineContent;
	                echo "</br>";
        	}
	}

	if ($_POST['send'] == "Retry" || !file_exists("log/port-$site.log"))
	{
		$command=("./scriptvpn.sh siteporttest $site $usrlvl");
/*
Start modif log
*/
                $day=date("d");
                $week=date("W");
                $month=date("m");
                $year=date("o");
                $hour=date("H:i");
                $user=$_SESSION['userid'];
		$userlvl=$_SESSION['user_level'];
		$sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','portread','$user','$userlvl','$site')";
                mysql_query($sql);
/*
End modif log
*/
		echo exec($command);
		echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
		echo '<!-- Table header-->';
		echo '<thead>';
        	echo '<tr><th scope="col" class="rounded-company">Port scan will be done in one minute</th><th scope="col" class="rounded-q4"></th></tr>';
		echo '</thead>';
		echo '<!-- Table footer-->';
		echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
		echo '</tfoot>';
		echo '<!-- Table body -->';
		echo '<tbody>';
		echo '<tr><td align="center">You will can see result by coming back later</td><td></td></tr>';
		echo '<tr><td align="center"><img src="img/charge.gif" width=50 height=50 alt="Job in progress..." title="Job in progress..."></td></td><td></tr>';
	        echo '</tbody></table>';
	}
	}
	else
	{
                $command=("./scriptvpn.sh siteporttest $site $usrlvl");
/*
Start modif log
*/
                $day=date("d");
                $week=date("W");
                $month=date("m");
                $year=date("o");
                $hour=date("H:i");
                $user=$_SESSION['userid'];
                $userlvl=$_SESSION['user_level'];
                $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','portread','$user','$userlvl','$site')";
                mysql_query($sql);
/*
End modif log
*/
                echo exec($command);
                echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
                echo '<!-- Table header-->';
                echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Port scan will be done in one minute</th><th scope="col" class="rounded-q4"></th></tr>';
                echo '</thead>';
                echo '<!-- Table footer-->';
                echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
                echo '</tfoot>';
                echo '<!-- Table body -->';
                echo '<tbody>';
                echo '<tr><td align="center">You will can see result by coming back later</td><td></td></tr>';
                echo '<tr><td align="center"><img src="img/charge.gif" width=50 height=50 alt="Job in progress..." title="Job in progress..."></td></td><td></tr>';
                echo '</tbody></table>';	
	}
}
}
else
{
	header("Location: login.php");
}
?>
