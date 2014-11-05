<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';

echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{

$vpn = $_GET['vpn'];
$site = $_GET['site'];
$usrlvl=$_SESSION['user_level'];
//echo "$vpn - $site";

if (!empty($vpn))
{
        if (empty($_POST['confirme']))
        {
                echo '<form action="delvpnreseach.php?vpn='.$vpn.'" method="post">';
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Do you realy want to delete vpn '.$vpn.' on the site '.$site.' ?</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="del.php" title="Back to delete menu">&nbsp;&nbsp;Back to delete menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
                echo '
                <tr><td align=center>
                <form action="delvpn.php" method="post" name="form2" id="form2">
                <input type="hidden" name="confirme" value="1">
                <input type="hidden" name="siteconf" value="'.$site.'">
                <input type="hidden" name="vpnname" value="'.$vpn.'">
                <br><input type="submit" name="Confirme" value="Confirme">
                </form></td><td></td></tr></tbody></table></br>';
        }

        if (!empty($_POST['confirme']))
        {
                $siteconf=$_POST['siteconf'];
                $vpnname=$_POST['vpnname'];
                while($data=mysql_fetch_assoc($req))
                {
                        $rightid=$data["rightid"];
                }
                $sql="delete from site_conf where `vpnname`='$vpnname'";
                mysql_query($sql) or die(mysql_error()) ;
                $command=("./scriptvpn.sh vpndelete $siteconf $vpnname $rightid $usrlvl");
                echo exec($command);
                        $day=date("d");
                        $week=date("W");
                        $month=date("m");
                        $year=date("o");
                        $hour=date("H:i");
                        $user=$_SESSION['userid'];
                        $userlvl=$_SESSION['user_level'];
                        $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','vpnadel','$user','$userlvl','$vpnname')";
                        mysql_query($sql);

echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">VPN <b>'.$vpnname.'</b> on site <b>'.$siteconf.'</b> delete</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="delvpn.php" title="To delete a other VPN">&nbsp;&nbsp;To delete a other VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
echo '</div><!-- #content -->';

echo '<div id="sidebar">';

	include 'menu.php';

echo '</div><!-- #sidebar -->
      </div><!-- #center -->';

include 'footer.php';
?>
