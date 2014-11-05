<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';

echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
echo '<h1 class="glob">Delete a VPN</h1>';

/*
if (!empty($_GET['vpn']))
{
	$_POST['vpnname']=$_GET['vpn']);
}
*/
if (empty($_POST['siteconf']) )
{
        echo '<form action="delvpn.php" method="post">';

echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Select the site where you want to delete a VPN:</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="delvpn" title="Delete a othe VPN">&nbsp;&nbsp;Delete a othe VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to VPN\'s Status">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                
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

if (!empty($_POST['siteconf']) && empty($_POST['vpnname']))
{
        echo '<form action="delvpn.php" method="post">';

echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Select the VPN where you want to delete on the site <b>"'.$_POST['siteconf'].'</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="delvpn" title="Delete a other VPN">&nbsp;&nbsp;Delete a other VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to VPN\'s Status">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
                 echo '<tbody>';

        echo '<td align="center">
        <select name="vpnname">';
	$siteconf=$_POST['siteconf'];
        $sql="select * from site_conf where siteconf='$siteconf' order by vpnname asc";
        $req=mysql_query($sql);
        while($data=mysql_fetch_assoc($req))
        {
                echo "<option>".$data['vpnname'];
        }
        echo '</select><input type="hidden" name="siteconf" value="'.$_POST['siteconf'].'">
</td>';
        echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></tr>';
        echo '</table>';

}
if (!empty($_POST['vpnname']))
{
        if (empty($_POST['confirme']))
        {
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Do you confirme <b>"'.$_POST['vpnname'].' deletion ?</b></th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="delvpn" title="Delete a other VPN">&nbsp;&nbsp;Delete a other VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to VPN\'s Status">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
                 echo '<tbody>';
echo '
                <tr><td align="center">
                <form action="delvpn.php" method="post" name="form2" id="form2">
                <input type="hidden" name="confirme" value="1">
                <input type="hidden" name="siteconf" value="'.$_POST['siteconf'].'">
		<input type="hidden" name="vpnname" value="'.$_POST['vpnname'].'">
                <input type="submit" name="Confirme" value="Confirme">
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
                $command=("./scriptvpn.sh vpndelete $siteconf $vpnname $rightid");
                echo exec($command);
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">VPN '.$vpnname.' on site '.$siteconf.' deleted</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
		<a class="table" href="delvpn" title="Delete a other VPN">&nbsp;&nbsp;Delete a other VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
