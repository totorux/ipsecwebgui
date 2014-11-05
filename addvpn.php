<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
include 'login.php';
echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
$site=$_GET['site'];
$usrlvl=$_SESSION['user_level'];
$vpnsql="select * from site_conf where siteconf = '$site'";
$vpnreq=mysql_query($vpnsql) or die(mysql_error());
while($vpn=mysql_fetch_assoc($vpnreq))
        {
                $rip=$vpn["right"];
                $rid=$vpn["rightid"];
		$rsub=$vpn["rightsubnet"];
        }
echo '<h1 class="glob">Add a new VPN on site '.$site.'</h1>';
if (!empty($site))
	{
		if (empty($_POST['vpntemplate']))
		{
			echo '<form action="addvpn.php?site='.$site.'" method="post">';
			echo '<!-- Table markup-->';
	                echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
	                echo '<tr><th scope="col" class="rounded-company">Select the template to use:</th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
        	        echo '<td colspan="1" class="rounded-foot-left" align="center">
                	<a class="table" href="addvpn.php" title="Back to add VPN menu">&nbsp;&nbsp;Back to add VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
	                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
        	        </td>
                	<td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
			echo '<tbody>';
		        echo '<td align="center">
        		<select name="vpntemplate">';
	        	$sql="select * from template where `templatetype`='template'";
	        	$req=mysql_query($sql);
		        while($data=mysql_fetch_assoc($req))
        		{
                		echo "<option>".$data['template_name'];
		        }
        		echo '</select></td>';
	        	echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></tr>';
		        echo '</tbody></table>';
		}
		if (empty($_POST['vpnnamenext']) && !empty($_POST['vpntemplate']) && $_POST['add']="vpn" && empty($_POST['Send1']))
		{
        		$vpntemplate=$_POST['vpntemplate'];
		        $sql="select * from  template where template_name='$vpntemplate'";
        		$req=mysql_query($sql);
	        	$res=mysql_fetch_assoc($req);
		        echo '<form action="addvpn.php?site='.$site.'" method="post">';
			echo '<!-- Table markup-->';
	                echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
	                echo '<tr><th scope="col" class="rounded-company">Used template: <b>'.$vpntemplate.'</b></th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
        	        echo '<td colspan="1" class="rounded-foot-left" align="center">
                	<a class="table" href="addvpn.php" title="Back to add VPN menu">&nbsp;&nbsp;Back to add VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
	                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
        	        </td>
                	<td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
			echo '<tbody>';
		        echo '
        	        <tr>
                	<td>Site:</td>
	                <td><input type="text" name="siteconf" value="'.$site.'" readonly/></td>
        	        </tr>
	        	<tr>
	        	<td>Left public IP:</td>
		        <td><input type="text" name="left" value="'.$res['left'].'" readonly/></td>
        		</tr>
		        <tr>
        		<td>Left ID:</td>
	        	<td><input type="text" name="leftid" value="'.$res['leftid'].'" readonly/></td>
	        	</tr>
		        <tr>
        		<td>Left Subnet (like x.x.x.x/xx):</td>
		        <td><input type="text" name="leftsubnet" value="'.$res['leftsubnet'].'"/></td>
        		</tr>
	        	<tr>
	        	<td>Right public IP:</td>
		        <td><input type="text" name="right" value="'.$rip.'" readonly/></td>
        		</tr>
		        <tr>
        		<td>Right ID:</td>
	        	<td><input type="text" name="rightid" value="'.$rid.'" readonly/></td>
	        	</tr>
		        <tr>
	        	<td>Secret Key:</td>
	        	<td><input type="text" name="secretkey" value="'.$res['secretkey'].'"readonly/></td>
        		</tr>
		        <tr>
        		<td>Right Subnet (like x.x.x.x/xx):</td>
		        <td><input type="text" name="rightsubnet" value="'.$rsub.'"/><input type="hidden" name="vpnnamenext" value="1"/><input type="hidden" name="usedtemplate" value="'.$res['key'].'"/></td>
			<input type="hidden" name="vpntemplate" value="'.$res['key'].'"/>
	        	</tr>
		        <tr><td align="right" colspan="2"><input type="submit" name="Send1" value="send">
        		</form>
		        ';
			echo '</tbody></table>';
		}

/* Vérrifier si le VPN n'éxiste pas déjà via le right et lef subnet
	*/

		if (!empty($_POST['vpnnamenext']))
		{
       		        $_POST['siteconf'] = no_special_character($_POST['siteconf']);
                	$siteconf=$_POST['siteconf'];
                	$vpnnumber=vpnnumber($siteconf);
	                $left=$_POST['left'];
        	        $leftid=$_POST['leftid'];
                	$leftsubnet=$_POST['leftsubnet'];
	                $right=$_POST['right'];
        	        $rightid=$_POST['rightid'];
                	$secretkey=$_POST['secretkey'];
	                $rightsubnet=$_POST['rightsubnet'];
        	        $usedtemplate=$_POST['usedtemplate'];
                	$vpnname=$siteconf.$vpnnumber;
	                $sql="insert into site_conf(`siteconf`,`vpnname`,`left`,`leftid`,`leftsubnet`,`right`,`rightid`,`secretkey`,`rightsubnet`,`templateid`) values('$siteconf','$vpnname','$left','$leftid','$leftsubnet','$right','$rightid','$secretkey','$rightsubnet','$usedtemplate')";
        	        mysql_query($sql);
                	$command=("./scriptvpn.sh vpnadd $siteconf $vpnname $left $leftid $leftsubnet $right $rightid $rightsubnet $secretkey $usedtemplate $usrlvl");
	                echo exec($command);

	                $day=date("d");
        	        $week=date("W");
                	$month=date("m");
	                $year=date("o");
        	        $hour=date("H:i");
                	$user=$_SESSION['userid'];
	                $userlvl=$_SESSION['user_level'];
        	        $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','vpnadd','$user','$userlvl','$vpnname')";
                	mysql_query($sql);
			echo '<!-- Table markup-->';
	                echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
	                echo '<tr><th scope="col" class="rounded-company">VPN '.$vpnname.' created on the site <b>'.$siteconf.'</b>.</th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
	                echo '<td colspan="1" class="rounded-foot-left" align="center">
        	        <a class="table" href="addvpn.php" title="Creat a new VPN">&nbsp;&nbsp;Creat a new VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="table" href="addvpn.php?site='.$siteconf.'" title="Add a other new VPN on this site">&nbsp;&nbsp;Add a other new VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                	<a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                
	                </td>
        	        <td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
	                echo '<tbody>';
        	        echo '</tbody></table></br>';
		}
	}
	else
	{
		if (empty($_POST['vpntemplate']))
		{
        		echo '<form action="addvpn.php" method="post">';
			echo '<!-- Table markup-->';
	                echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
	                echo '<tr><th scope="col" class="rounded-company">Select the template to use:</th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
        	        echo '<td colspan="1" class="rounded-foot-left" align="center">
                	<a class="table" href="addvpn.php" title="Back to add VPN menu">&nbsp;&nbsp;Back to add VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
	                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
        	        </td>
                	<td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
			echo '<tbody>';
		        echo '<td align="center">
        		<select name="vpntemplate">';
	        	$sql="select * from template where `templatetype`='template'";
		        $req=mysql_query($sql);
        		while($data=mysql_fetch_assoc($req))
	        	{
	        	        echo "<option>".$data['template_name'];
		        }
        		echo '</select></td>';
		        echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></tr>';

		        echo '</tbody></table>';
		}
		if (empty($_POST['vpnnamenext']) && !empty($_POST['vpntemplate']) && $_POST['add']="vpn")
		{
        		$vpntemplate=$_POST['vpntemplate'];

		        $sql="select * from  template where template_name='$vpntemplate'";
        		$req=mysql_query($sql);
	        	$res=mysql_fetch_assoc($req);

			echo '<form action="addvpn.php" method="post">';
			echo '<!-- Table markup-->';
                	echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
                	echo '<tr><th scope="col" class="rounded-company">Used template: <b>'.$vpntemplate.'</b></th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
	                echo '<td colspan="1" class="rounded-foot-left" align="center">
        	        <a class="table" href="template.php" title="Back to add VPN menu">&nbsp;&nbsp;Back to add VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                	<a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
	                </td>
        	        <td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
			echo '<tbody>';
	        	echo '
			<td>Select the site ti use:</td><td>
			<select name="siteconf">';
			$sql="select distinct(siteconf) from site_conf order by siteconf asc";
			$req=mysql_query($sql);
			while($data=mysql_fetch_assoc($req))
	    		{
			      	echo "<option>".$data['siteconf'];
		    	}
    			echo '</select>
		        </td>
        		</tr>
	        	<tr>
	        	<td>Left public IP:</td>
		        <td><input type="text" name="left" value="'.$res['left'].'"/></td>
        		</tr>
		        <tr>
        		<td>Left ID:</td>
	        	<td><input type="text" name="leftid" value="'.$res['leftid'].'"/></td>
	        	</tr>
		        <tr>
        		<td>Left Subnet (like x.x.x.x/xx):</td>
		        <td><input type="text" name="leftsubnet" value="'.$res['leftsubnet'].'"/></td>
        		</tr>
		        <tr>
        		<td>Right public IP:</td>
	        	<td><input type="text" name="right"/></td>
	        	</tr>
		        <tr>
        		<td>Right ID:</td>
		        <td><input type="text" name="rightid"/></td>
        		</tr>
	        	<tr>
	        	<td>Secret Key:</td>
		        <td><input type="text" name="secretkey" value="'.$res['secretkey'].'"/></td>
        		</tr>
		        <tr>
        		<td>Right Subnet (like x.x.x.x/xx):</td>
	        	<td><input type="text" name="rightsubnet"/><input type="hidden" name="vpnnamenext" value="1"/><input type="hidden" name="usedtemplate" value="'.$res['key'].'"/><input type="hidden" name="vpntemplate" value="'.$res['key'].'"/></td>
		        </tr>
        		<tr><td align="right" colspan="2"><input type="submit" name="Send" value="send">
	        	</form>
	        	';
		        echo '<tbody></table>';
		}

/* Vérrifier si le VPN n'éxiste pas déjà via le right et lef subnet
*/

		if (!empty($_POST['vpnnamenext']))
		{
        		$_POST['siteconf'] = no_special_character($_POST['siteconf']);
                	$siteconf=$_POST['siteconf'];
			$vpnnumber=vpnnumber($siteconf);
        	        $left=$_POST['left'];
                	$leftid=$_POST['leftid'];
	                $leftsubnet=$_POST['leftsubnet'];
        	        $right=$_POST['right'];
                	$rightid=$_POST['rightid'];
	                $secretkey=$_POST['secretkey'];
        	        $rightsubnet=$_POST['rightsubnet'];
                	$usedtemplate=$_POST['usedtemplate'];
	                $vpnname=$siteconf.$vpnnumber;
			$sql="insert into site_conf(`siteconf`,`vpnname`,`left`,`leftid`,`leftsubnet`,`right`,`rightid`,`secretkey`,`rightsubnet`,`templateid`) values('$siteconf','$vpnname','$left','$leftid','$leftsubnet','$right','$rightid','$secretkey','$rightsubnet','$usedtemplate')";
			mysql_query($sql);
	                $command=("./scriptvpn.sh vpnadd $siteconf $vpnname $left $leftid $leftsubnet $right $rightid $rightsubnet $secretkey $usedtemplate $usrlvl");
        	        echo exec($command);

	                $day=date("d");
                        $week=date("W");
                        $month=date("m");
                        $year=date("o");
                        $hour=date("H:i");
                        $user=$_SESSION['userid'];
                        $userlvl=$_SESSION['user_level'];
                        $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','vpnadd','$user','$userlvl','$vpnname')";
                        mysql_query($sql);


			echo '<!-- Table markup-->';
	                echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
	                echo '<tr><th scope="col" class="rounded-company">VPN '.$vpnname.' created on the site <b>'.$siteconf.'</b>.</th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
        	        echo '<td colspan="1" class="rounded-foot-left" align="center">
                	<a class="table" href="addvpn.php" title="Creat a new VPN">&nbsp;&nbsp;Creat a new VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="table" href="addvpn.php?site='.$siteconf.'" title="Add a other new VPN on this site">&nbsp;&nbsp;Add a other new VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
	                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                
        	        </td>
                	<td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
                	echo '<tbody>';
	                echo '</tbody></table></br>';
		}
	}

echo '</div><!-- #content -->';
}
else
{
        header("Location: index.php");
}

echo '<div id="sidebar">';

	include 'menu.php';

echo '</div><!-- #sidebar -->
      </div><!-- #center -->';

include 'footer.php';
?>
