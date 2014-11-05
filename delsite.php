<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';

echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
$site=$_GET['site'];
$usrlvl=$_SESSION['user_level'];
echo '<h1 class="glob">Delete a site</h1>';
if (empty($_POST['siteconf']) && empty($site) )
{
        echo '<form action="delsite.php" method="post">';

echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Select the site you want to delete:</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="delsite.php" title="Back to del site menu">&nbsp;&nbsp;Back to del site menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';

	echo '<td align="center">
        <select name="siteconf">';
        $sql="select distinct(siteconf) from site_conf order by siteconf asc";
        $req=mysql_query($sql);
        while($data=mysql_fetch_assoc($req))
        {
                echo "<option>".$data['siteconf'];
        }
        echo '</select></td>';
        echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></tr>';
	echo '</tbody></table>';
}
if (!empty($_POST['siteconf']) || !empty($site))
{
        if (empty($_POST['confirme']))
        {
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Do you confirme to delete '.$site.''.$_POST['siteconf'].' ?</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
		<a class="table" href="delsite.php" title="Back to del site menu">&nbsp;&nbsp;Back to del site menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
                echo '
                <tr><td align=center>
                <form action="delsite.php?site='.$site.'" method="post" name="form2" id="form2">
                <input type="hidden" name="confirme" value="1">
                <input type="hidden" name="siteconf" value="'.$_POST['siteconf'].'">
                <br><input type="submit" name="Confirme" value="Confirme">
                </form></td><td></td></tr></tbody></table></br>';
        }

        if (!empty($_POST['confirme']))
        {
		if(!empty($_POST['siteconf']))
		{
	                $siteconf=$_POST['siteconf'];
		}
		else
		{
			$siteconf="$site";
		}
	        $sql="select distinct(rightid) from site_conf where `siteconf`='$siteconf'";
	        $req=mysql_query($sql);
		while($data=mysql_fetch_assoc($req))
  		{
			$rightid=$data["rightid"];
		}
	
                $sql="delete from  site_conf where `siteconf`='$siteconf'";
                mysql_query($sql) or die(mysql_error()) ;

                $command=("./scriptvpn.sh sitedelete $siteconf $rightid $usrlvl");
                echo exec($command);
                        $day=date("d");
                        $week=date("W");
                        $month=date("m");
                        $year=date("o");
                        $hour=date("H:i");
                        $user=$_SESSION['userid'];
                        $userlvl=$_SESSION['user_level'];
                        $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','sitedel','$user','$userlvl','$siteconf')";
                        mysql_query($sql);

echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Site <b>'.$siteconf.'</b> deleted.</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="delsite.php" title="Delete a other site">&nbsp;&nbsp;Delete a other site&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
                 echo '<tbody>';
                 echo '</tbody></table></br>';

        }	
}

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
