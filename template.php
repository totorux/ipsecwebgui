<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
include 'login.php';

echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
	echo '<h1 class="glob">Template modification</h1>';
	if (empty($_POST['template_name']) && empty($_POST['templatekey']))
	{
        	echo '<form action="template.php" method="post">';
		echo '<!-- Table markup-->';
        	echo '<table id="rounded-corner">';
		echo '<!-- Table header-->';
		echo '<thead>';
	        echo '<tr><th scope="col" class="rounded-company">Select a template to modify or a action:</th><th scope="col" class="rounded-q4"></th></tr>';
		echo '</thead>';
		echo '<!-- Table footer-->';
		echo '<tfoot>';
	        echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="template.php" title="Back to template management page">&nbsp;&nbsp;Back to template management page&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
		echo '</tfoot>';
		echo '<!-- Table body -->';
		echo '<tbody>';
	        echo '<td align="center">
        	<select name="template_name">';
	        $sql="select * from template where `key`>'1' order by `key` asc";
		$req=mysql_query($sql);
	        while($data=mysql_fetch_assoc($req))
        	{
                	echo '<option value="'.$data['template_name'].'">'.$data['template_name'].'</option>';
	        }
        	echo '</select></td>';
		echo '<input type="hidden" name="actionselec" value="yes"/>';
        	echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></tr>';

		echo '</tbody></table>';
	}
	if (!empty($_POST['actionselec']))
	{
        	$template_name=$_POST['template_name'];

		if ($template_name == 'Add new template')
		{
                	$sql="select * from template where template_name='Clear'";
	                $req=mysql_query($sql);
        	        while($res=mysql_fetch_assoc($req))
                	{
				echo '<!-- Table markup-->';
		                echo '<table id="rounded-corner">';
				echo '<!-- Table header-->';
				echo '<thead>';
		                echo '<tr><th scope="col" class="rounded-company">Add new template</th><th scope="col" class="rounded-q4"></th></tr>';
				echo '</thead>';
				echo '<!-- Table footer-->';
				echo '<tfoot>';
		                echo '<td colspan="1" class="rounded-foot-left" align="center">
				<a class="table" href="template.php" title="Back to template management page">&nbsp;&nbsp;Back to template management page&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                		<a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
		                </td>
                		<td class="rounded-foot-right">&nbsp;</td>';
				echo '</tfoot>';
				echo '<!-- Table body -->';
				echo '<tbody>';
	                        echo '
        	                </br>
                	        <form action="template.php" method="post">
                        	<tr>
	                        <td>Template name:</td>
        	                <td>
                	        <input type="text" name="template_name"/>
	                        </td>
        	                </tr>
                	        <tr>
                        	<td>Authentification type:</td>
	                        <td><input type="text" name="auth"/></td>
        	                </tr>
                	        <tr>
                        	<td>Ike:</td>
	                        <td><input type="text" name="ike"/></td>
        	                </tr>
                	        <tr>
                        	<td>Authby:</td>
	                        <td><input type="text" name="authby"/></td>
        		        </tr>
                        	<tr>
	                        <td>Auto:</td>
        	                <td><input type="text" name="auto"/></td>
                	        </tr>
                        	<tr>
	                        <td>Compress:</td>
        	                <td><input type="text" name="compress"/></td>
                	        </tr>
	                        <tr>
        	                <td>Pfs:</td>
                	        <td><input type="text" name="pfs"/></td>
                        	</tr>
	                        <tr>
        	                <td>Type:</td>
                	        <td><input type="text" name="type"/></td>
                        	</tr>
	                        <tr>
        	                <td>Key life:</td>
                	        <td><input type="text" name="keylife"/></td>
                        	</tr>
	                        <tr>
        	                <td>Rekey:</td>
                	        <td><input type="text" name="rekey"/></td>
                        	</tr>
	                        <tr>
        	                <td>Esp:</td>
                	        <td><input type="text" name="esp"/></td>
                        	</tr>
	                        <tr>
        	                <td>Left public IP:</td>
                	        <td><input type="text" name="left"/></td>
	                        </tr>
        	                <tr>
                	        <td>Left ID:</td>
                        	<td><input type="text" name="leftid"/></td>
	                        </tr>
        	                <tr>
                	        <td>Left Subnet (like x.x.x.x/xx):</td>
                        	<td><input type="text" name="leftsubnet"/></td>
	                        </tr>
        	                <tr>
<!--            	        <td>Right public IP:</td>
                        	<td><input type="text" name="right"/></td>
	                        </tr>
        	                <tr>
                	        <td>Right ID:</td>
                        	<td><input type="text" name="rightid"/></td>
	                        </tr>
        	                <tr>
                	        <td>Right Subnet (like x.x.x.x/xx):</td>
                        	<td><input type="text" name="rightsubnet"/></td>
	                        </tr>
--!>    	                <tr>
                	        <td>Secret Key:</td>
	                        <td><input type="text" name="secretkey" value="'.$res['secretkey'].'"/><input type="hidden" name="templatekey" value="'.$res['key'].'"/><input type="hidden" name="add" value="yes"/></td>
        	                </tr>
                	        <tr><td align="right" colspan="2"><input type="submit" name="Send" value="send">
                        	</form>
	                        </tbody></table>';

        	        }
		}

        	if ($template_name == 'Delete a template')
	        {
		        echo '<form action="template.php" method="post">';
			echo '<!-- Table markup-->';
	                echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>';
	                echo '<tr><th scope="col" class="rounded-company">Select the template you want to delete:</th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';
	                echo '<td colspan="1" class="rounded-foot-left" align="center">
	                <a class="table" href="template.php" title="Back to template management page">&nbsp;&nbsp;Back to template management page&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
        	        <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                	</td>
	                <td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
			echo '<tbody>';
		        echo '<td align="center">
		        <select name="templatekey">';
        		$sql="select * from template where `key`>'1' and `templatetype` = 'template' order by `key` asc";
		        $req=mysql_query($sql);
		        while($data=mysql_fetch_assoc($req))
		        {
        		        echo '<option value="'.$data['key'].'">'.$data['template_name'];
	        	}
		        echo '</select></td>';
		        echo '<input type="hidden" name="del" value="yes"/>';
	        	echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></tr>';

		        echo '</tbody></table>';
	        }
		if ($template_name != 'Add new template' && $template_name != 'Delete a template')
		{
	        	echo '<form action="template.php" method="post">';
			echo '<!-- Table markup-->';
	                echo '<table id="rounded-corner">';
			echo '<!-- Table header-->';
			echo '<thead>'; 
	                echo '<tr><th scope="col" class="rounded-company">Template '.$template_name.' edit:</th><th scope="col" class="rounded-q4"></th></tr>';
			echo '</thead>';
			echo '<!-- Table footer-->';
			echo '<tfoot>';         
	                echo '<td colspan="1" class="rounded-foot-left" align="center">
        	        <a class="table" href="template.php" title="Back to template management page">&nbsp;&nbsp;Back to template management page&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                	<a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
	                </td>
        	        <td class="rounded-foot-right">&nbsp;</td>';
			echo '</tfoot>';
			echo '<!-- Table body -->';
			echo '<tbody>';
		        $sql="select * from template where template_name='$template_name'";
       			$req=mysql_query($sql);
			while($res=mysql_fetch_assoc($req))
	  		{
				$template_name=$res['template_name'];
				echo '<form action="template.php" method="post">';
		       		echo '
			        </br>
				<form action="template.php" method="post">
       				<tr>
	        		<td>Template name:</td>
			        <td>
			        <input type="text" name="template_name" value="'.$template_name.'"/>
			        </td>
       				</tr>
	                        <tr>
        	                <td>Authentification type:</td>
                	        <td><input type="text" name="auth" value="'.$res['auth'].'"/></td>
                        	</tr>
	                        <tr>
        	                <td>Ike:</td>
                	        <td><input type="text" name="ike" value="'.$res['ike'].'"/></td>
                        	</tr>
	                        <tr>
        	                <td>Authby:</td>
                	        <td><input type="text" name="authby" value="'.$res['authby'].'"/></td>
                        	</tr>
	                        <tr>
        	                <td>Auto:</td>
                	        <td><input type="text" name="auto" value="'.$res['auto'].'"/></td>
                        	</tr>
	                        <tr>
        	                <td>Compress:</td>
                	        <td><input type="text" name="compress" value="'.$res['compress'].'"/></td>
                        	</tr>
	                        <tr>
        	                <td>Pfs:</td>
                	        <td><input type="text" name="pfs" value="'.$res['pfs'].'"/></td>
	                        </tr>
        	                <tr>
                	        <td>Type:</td>
                        	<td><input type="text" name="type" value="'.$res['type'].'"/></td>
	                        </tr>
        	                <tr>
                	        <td>Key life:</td>
	                        <td><input type="text" name="keylife" value="'.$res['keylife'].'"/></td>
        	                </tr>
                	        <tr>
	                        <td>Rekey:</td>
        	                <td><input type="text" name="rekey" value="'.$res['rekey'].'"/></td>
                	        </tr>
                        	<tr>
	                        <td>Esp:</td>
        	                <td><input type="text" name="esp" value="'.$res['esp'].'"/></td>
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
<!--    	    		<td>Right public IP:</td>
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
--!>
	        		<tr>
        			<td>Secret Key:</td>
			        <td><input type="text" name="secretkey" value="'.$res['secretkey'].'"/><input type="hidden" name="templatekey" value="'.$res['key'].'"/><input type="hidden" name="mod" value="yes"/></td>
       				</tr>
		        	<tr><td align="right" colspan="2"><input type="submit" name="Send" value="send">
		       		</form>
				</tbody></table>';

			}
		}
	}

	if (!empty($_POST['mod']))
	{
                $template_name=$_POST['template_name'];
                $auth=$_POST['auth'];
                $ike=$_POST['ike'];
                $authby=$_POST['authby'];
                $auto=$_POST['auto'];
                $compress=$_POST['compress'];
                $pfs=$_POST['pfs'];
                $type=$_POST['type'];
                $keylife=$_POST['keylife'];
                $rekey=$_POST['rekey'];
                $esp=$_POST['esp'];
                $left=$_POST['left'];
                $leftid=$_POST['leftid'];
                $leftsubnet=$_POST['leftsubnet'];
/*
                $right=$_POST['right'];
                $rightid=$_POST['rightid'];
                $rightsubnet=$_POST['rightsubnet'];
*/
                $secretkey=$_POST['secretkey'];
                $templatekey=$_POST['templatekey'];
//echo "$template_name - $left - $leftid - $leftsubnet - $secretkey - $templatekey </br>";
              	$sql="update template set
		`key`='$templatekey',
		`template_name`='$template_name',
		`left`='$left',
		`leftid`='$leftid',
		`leftsubnet`='$leftsubnet',
		`secretkey`='$secretkey',
                `auth`='$auth',
                `ike`='$ike',
                `authby`='$authby',
                `auto`='$auto',
                `compress`='$compress',
                `pfs`='$pfs',
                `type`='$type',
                `keylife`='$keylife',
                `rekey`='$rekey',
                `esp`='$esp'
		where `key`='$templatekey'";
              	mysql_query($sql) or die(mysql_error()) ;

		$command=("./scriptvpn.sh templateupdate $templatekey $left $leftid $leftsubnet $secretkey $auth $ike $authby $auto $compress $pfs $type $keylife $rekey $esp");
		echo exec($command);

		echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
		echo '<!-- Table header-->';
		echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Template '.$template_name.' configuration updated</th><th scope="col" class="rounded-q4"></th></tr>';
		echo '</thead>';
		echo '<!-- Table footer-->';
		echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="template.php" title="To change a other template">&nbsp;&nbsp;Change a other template&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
		echo '</tfoot>';
		echo '<!-- Table body -->';
                 echo '<tbody>';
                 echo '</tbody></table></br>';
	}

	if (!empty($_POST['add']))
	{
                $template_name=$_POST['template_name'];
		$auth=$_POST['auth'];
		$ike=$_POST['ike'];
		$authby=$_POST['authby'];
		$auto=$_POST['auto'];
		$compress=$_POST['compress'];
		$pfs=$_POST['pfs'];
		$type=$_POST['type'];
		$keylife=$_POST['keylife'];
		$rekey=$_POST['rekey'];
		$esp=$_POST['esp'];
                $left=$_POST['left'];
                $leftid=$_POST['leftid'];
                $leftsubnet=$_POST['leftsubnet'];
/*
                $right=$_POST['right'];
                $rightid=$_POST['rightid'];
                $rightsubnet=$_POST['rightsubnet'];
*/
                $secretkey=$_POST['secretkey'];
                $templatekey=$_POST['templatekey'];
		$templatetype='template';
//echo "$template_name - $left - $leftid - $leftsubnet - $secretkey - $templatekey </br>";
                $sql="insert into template(`templatetype`,`template_name`,`left`,`leftid`,`leftsubnet`,`secretkey`,`auth`,`ike`,`authby`,`auto`,`compress`,`pfs`,`type`,`keylife`,`rekey`,`esp`) values('$templatetype','$template_name','$left','$leftid','$leftsubnet','$secretkey','$auth','$ike','$authby','$auto','$compress','$pfs','$type','$keylife','$rekey','$esp')";
                mysql_query($sql) or die(mysql_error()) ;

                $sql="select * from template where `template_name` = '$template_name'";
                $req=mysql_query($sql);
                while($data=mysql_fetch_assoc($req))
                {
                        $templatekey = $data['key'];
                }
		
		$command=("./scriptvpn.sh templateadd $templatekey $left $leftid $leftsubnet $secretkey $auth $ike $authby $auto $compress $pfs $type $keylife $rekey $esp");
                echo exec($command);

echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Template '.$template_name.' configuration created</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="template.php" title="To change a other template">&nbsp;&nbsp;Change a other template&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
                 echo '<tbody>';
                 echo '</tbody></table></br>';
	}

	if (!empty($_POST['del']))
	{
		if (empty($_POST['confirme']))
		{
			echo '<table">
			<tr><td align=center><br> Do you confirme ?<b></b><br>
			<form action="template.php" method="post" name="form2" id="form2">
			<input type="hidden" name="confirme" value="1">
			<input type="hidden" name="del" value="yes">
	                <input type="hidden" name="templatekey" value="'.$_POST['templatekey'].'">
			<br><input type="submit" name="Confirme" value="Confirme">
			</form></td></tr>';
		}

		if (!empty($_POST['confirme']))
	        {
		        $templatekey=$_POST['templatekey'];
			$sql="delete from template where `key`='$templatekey'";
		        mysql_query($sql) or die(mysql_error()) ;

        	        $command=("./scriptvpn.sh templatedel $templatekey");
                	echo exec($command);
		echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
		echo '<!-- Table header-->';
		echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Template delete</th><th scope="col" class="rounded-q4"></th></tr>';
		echo '</thead>';
		echo '<!-- Table footer-->';
		echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="template.php" title="To delete a other template">&nbsp;&nbsp;Delete a other template&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
