<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
include 'login.php';
$usrlvl=$_SESSION['user_level'];
echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
	echo '<h1 class="glob">Add a new site</h1>';
	if (empty($_POST['sitetemplate']))
	{
        	echo '<form action="addsite.php" method="post">';
		echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
		echo '<!-- Table header-->';
		echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Select the template to use:</th><th scope="col" class="rounded-q4"></th></tr>';
		echo '</thead>';
		echo '<!-- Table footer-->';
		echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
		<a class="table" href="add.php" title="Back to add menu">&nbsp;&nbsp;Back to add menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
		</td>
                <td class="rounded-foot-right">&nbsp;</td>';
		echo '</tfoot>';
		echo '<!-- Table body -->';
		echo '<tbody>';
		echo '<td align="center">
	        <select name="sitetemplate">';
        	$sql="select * from template where `templatetype`='template'";
	        $req=mysql_query($sql);
        	while($data=mysql_fetch_assoc($req))
	        {

			echo '<option value="'.$data['key'].'">'.$data['template_name'];
	        }
	        echo '</select></td>';
        	echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></td></tr>';
	        echo '</tbody></table>';
	}
	if (empty($_POST['siteconftest']) && !empty($_POST['sitetemplate']) && $_POST['add']="site")
	{
		$sitetemplate=$_POST['sitetemplate'];
	        $sql="select * from template where `key` = '$sitetemplate'";
        	$req=mysql_query($sql);
	        while($data=mysql_fetch_assoc($req))
        	{
        		$sitetemplatename = $data['template_name'];
	        }
		$sql="select * from  template where `key`='$sitetemplate'";
		$req=mysql_query($sql);
  		$res=mysql_fetch_assoc($req);

		echo '<form action="addsite.php" method="post">';
		echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
		echo '<!-- Table header-->';
		echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Used template: <b>'.$sitetemplatename.'</b></th><th scope="col" class="rounded-q4"></th></tr>';
		echo '</thead>';
		echo '<!-- Table footer-->';
		echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
	        <a class="table" href="add.php" title="Back to add menu">&nbsp;&nbsp;Back to add menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
		echo '</tfoot>';
		echo '<!-- Table body -->';
		echo '<tbody>';
		echo '
		<tr>
		<td>Site name:</td>
		<td><input type="text" name="siteconf"/></td>
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
	        <td><input type="text" name="rightsubnet"/><input type="hidden" name="siteconftest" value="1"/><input type="hidden" name="usedtemplate" value="'.$res['key'].'"/><input type="hidden" name="sitetemplate" value="'.$res['key'].'"/></td>
        	</tr>
		<tr><td align="right" colspan="2"><input type="submit" name="send" value="send">
		</form>
		';
		echo '</tbody></table>';
	
	}
	if (!empty($_POST['siteconftest']))
	{
     		$sql="select distinct(siteconf) from site_conf where siteconf='".$_POST['siteconf']."' order by siteconf asc";
	    	$req=mysql_query($sql);
    		$data=mysql_fetch_assoc($req);
		if(empty($data['siteconf']))
      		{
		      	$_POST['siteconf'] = no_special_character($_POST['siteconf']);
      			$siteconf=$_POST['siteconf'];
                	$left=$_POST['left'];
	                $leftid=$_POST['leftid'];
        	        $leftsubnet=$_POST['leftsubnet'];
      			$right=$_POST['right'];
	      		$rightid=$_POST['rightid'];
      			$secretkey=$_POST['secretkey'];
                	$rightsubnet=$_POST['rightsubnet'];
			$usedtemplate=$_POST['usedtemplate'];
			$vpnname=$siteconf.'1';
			$sql="insert into site_conf(`siteconf`,`vpnname`,`left`,`leftid`,`leftsubnet`,`right`,`rightid`,`secretkey`,`rightsubnet`,`templateid`) values('$siteconf','$vpnname','$left','$leftid','$leftsubnet','$right','$rightid','$secretkey','$rightsubnet','$usedtemplate')";
	      		mysql_query($sql);
			$command=("./scriptvpn.sh siteadd $siteconf $vpnname $left $leftid $leftsubnet $right $rightid $rightsubnet $secretkey $usedtemplate $usrlvl");
			echo exec($command);

			$day=date("d");
			$week=date("W");
			$month=date("m");
			$year=date("o");
			$hour=date("H:i");
			$user=$_SESSION['userid'];
			$userlvl=$_SESSION['user_level'];
			$sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','siteadd','$user','$userlvl','$siteconf')";
			mysql_query($sql);

			echo '<!-- Table markup-->';
		        echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
		        echo '<tr><th scope="col" class="rounded-company">Site: <b>'.$siteconf.'</b> created with first VPN called: <b>'.$vpnname.'</b></th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
	                echo '<td colspan="1" class="rounded-foot-left" align="center">
			<a class="table" href="addsite.php" title="Creat a new site">&nbsp;&nbsp;Creat a new site&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="table" href="addvpn.php?site='.$siteconf.'" title="Add a other new VPN on this site">&nbsp;&nbsp;Add a other new VPN&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
	
	                </td>
        	        <td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
			echo '<tbody>';
			echo '</tbody></table></br>';

		}
		else
		{
			echo '<!-- Table markup-->';
	                echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
	                echo '<tr><th scope="col" class="rounded-company">Site <b>'.$siteconf.'</b> allready created please use a other site name.</th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
	                echo '<td colspan="1" class="rounded-foot-left" align="center">
        	        <a class="table" href="addsite.php" title="Creat a new site">&nbsp;&nbsp;Creat a new site&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
