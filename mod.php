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
	echo '<h1 class="glob">Site modification</h1>';

if (empty($_POST['vpnsite']) && empty($site))
{
        echo '<form action="mod.php" method="post">';
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Select the site you want to modify:</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="mod.php" title="Back to modify VPN menu">&nbsp;&nbsp;Back to modify VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
        echo '<td align="center">
        <select name="vpnsite">';
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

if (empty($_POST['vpnname']) && !empty($_POST['vpnsite']))
{
        $site=$_POST['vpnsite'];
	echo '<form action="mod.php" method="post">';
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Select the site you want to modify:</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="mod.php" title="Back to modify VPN menu">&nbsp;&nbsp;Back to modify VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
        echo '<td align="center">
        <select name="vpnname">';
        $sql="select * from site_conf where siteconf='$site'";
        $req=mysql_query($sql);
        while($data=mysql_fetch_assoc($req))
        {
                echo "<option>".$data['vpnname'];
        }
        echo '</select></td>';
	echo '<input type="hidden" name="vpnsite" value="'.$_POST['vpnsite'].'"/>';
        echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></tr>';

        echo '</tbody></table>';
}
if ((!empty($_POST['vpnname']) && !empty($_POST['vpnsite'])) || (!empty($site) && !empty($vpn)))
{
	if (empty($site) && empty($vpn))
	{
	        $vpnname=$_POST['vpnname'];
		$site=$_POST['vpnsite'];
	}
	else
	{
		$vpnname="$vpn";
		$site="$site";
	}
        echo '<form action="mod.php?site='.$site.'&vpn='.$vpn.'" method="post">';
//       	echo '<table>';
        $sql="select * from site_conf where vpnname='$vpnname'";
       	$req=mysql_query($sql);
	while($res=mysql_fetch_assoc($req))
  	{
		$vpnname=$res['vpnname'];
		echo '
		<form action="mod.php" method="post">';
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company"><div align="left"> Modify VPN <b>'.$vpn.'</b> on site <b>'.$site.'</b> </br>If you change secrets key, right public IP or right ID modification will be apply to all VPN on this site </div></th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="mod.php" title="Back to modify VPN menu">&nbsp;&nbsp;Back to modify VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
	       	echo '</br>
		<form action="mod.php" method="post">
       		<tr>
	        <td>Site:</td>
	        <td>
		<input type="hidden" name="confkey" value="'.$res['key'].'"/>
	        <input type="text" name="siteconf" value="'.$site.'" readonly/>
	        </td>
       		</tr>
	        <tr>
        	<td>VPN name</td>
	        <td>
        	<input type="text" name="vpnname2" value="'.$vpnname.'" readonly/>
	        </td>
       		</tr>
	        <tr>
	        <td>Left public IP:</td>
        	<td><input type="text" name="left" value="'.$res['left'].'"readonly/></td>
	        </tr>
        	<tr>
	        <td>Left ID:</td>
       		<td><input type="text" name="leftid" value="'.$res['leftid'].'"readonly/></td>
	        </tr>
	        <tr>
        	<td>Left Subnet (like x.x.x.x/xx):</td>
	        <td><input type="text" name="leftsubnet" value="'.$res['leftsubnet'].'"/></td>
       		</tr>
        	<tr>
        	<td>Right public IP:</td>
	        <td><input type="text" name="right"  value="'.$res['right'].'"/></td>
       		</tr>
	        <tr>
       		<td>Right ID:</td>
        	<td><input type="text" name="rightid"  value="'.$res['rightid'].'"/></td>
        	</tr>
	        <tr>
       		<td>Right Subnet (like x.x.x.x/xx):</td>
	        <td><input type="text" name="rightsubnet" value="'.$res['rightsubnet'].'"/></td>
       		</tr>
        	<tr>
        	<td>Secret Key:</td>
	        <td><input type="text" name="secretkey" value="'.$res['secretkey'].'"/><input type="hidden" name="usedtemplate" value="'.$res['templateid'].'"/></td>
       		</tr>
	        <tr><td align="right" colspan="2"><input type="submit" name="Send" value="send"></tr>
       		</form>
		</tbody></table>';
	}
}

if (!empty($_POST['confkey']))
{

		$confkey=$_POST['confkey'];
                $siteconf=$_POST['siteconf'];
                $left=$_POST['left'];
                $leftid=$_POST['leftid'];
                $leftsubnet=$_POST['leftsubnet'];
                $right=$_POST['right'];
                $rightid=$_POST['rightid'];
                $secretkey=$_POST['secretkey'];
                $rightsubnet=$_POST['rightsubnet'];
                $usedtemplate=$_POST['usedtemplate'];
                $vpnname=$_POST['vpnname2'];

//echo "$confkey - $siteconf - $left - $leftid - $leftsubnet - $right - $rightid - $secretkey - $rightsubnet - $usedtemplate - $vpnname";
        	$command=("./scriptvpn.sh vpnupdate $siteconf $vpnname $left $leftid $leftsubnet $right $rightid $rightsubnet $secretkey");
//	        echo exec($command);
             	$sql="update site_conf set
		`siteconf`='$siteconf',
		`vpnname`='$vpnname',
		`left`='$left',
		`leftid`='$leftid',
		`leftsubnet`='$leftsubnet',
		`right`='$right',
		`rightid`='$rightid',
		`secretkey`='$secretkey',
		`rightsubnet`='$rightsubnet',
		`templateid`='$usedtemplate' where `key`='$confkey'";
              	mysql_query($sql);

                        $day=date("d");
                        $week=date("W");
                        $month=date("m");
                        $year=date("o");
                        $hour=date("H:i");
                        $user=$_SESSION['userid'];
                        $userlvl=$_SESSION['user_level'];
                        $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','vpnmod','$user','$userlvl','$vpnname')";
                        mysql_query($sql);

	        $sql2="select * from site_conf where siteconf='$siteconf'";
        	$req2=mysql_query($sql2);
	        while($data2=mysql_fetch_assoc($req2))
        	{
	                $sql="update site_conf set
        	        `siteconf`='$data2[siteconf]',
	                `vpnname`='$data2[vpnname]',
                	`left`='$data2[left]',
        	        `leftid`='$data2[leftid]',
                	`leftsubnet`='$data2[leftsubnet]',
	                `right`='$right',
        	        `rightid`='$rightid',
                	`secretkey`='$secretkey',
	                `rightsubnet`='$data2[rightsubnet]',
        	        `templateid`='$data2[templateid]' where `key`='$data2[key]'";
                	mysql_query($sql);
        	}
//	$command=("./scriptvpn.sh vpnupdate $siteconf $vpnname $left $leftid $leftsubnet $right $rightid $rightsubnet $secretkey");
	echo exec($command);
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">'.$vpnname.' VPN\'s configuration of site '.$siteconf.' updated</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="mod.php" title="Back to modify VPN menu">&nbsp;&nbsp;Back to modify VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
echo '</tbody></table>';
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
