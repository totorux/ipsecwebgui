<?php
/*$syspath=str_replace('/var/www/', '',str_replace('index.php', '', realpath ('index.php')));
echo "$syspath";
*/
$idusrsql="select * from users where `id`='$_SESSION[userid]'";
$idusrreq=mysql_query($idusrsql) or die(mysql_error());
while($res=mysql_fetch_assoc($idusrreq))
{
	$full_name=$res['full_name'];
}

echo "<p class='menu'><font color='white'>Hello $full_name</font></p></br>";

echo'
		<p class="menu"><a class="menu" href="../index.php" style="text-decoration: none;" title="reload VPN\'s status">&nbsp;Reaload VPN\'s Status&nbsp;</a></p>';
if ($_SESSION['user_level'] >= 4)
{
echo '
		<p class="menu"><a class="menu" href="../detail.php" style="text-decoration: none;" title="VPN\'s status / management">&nbsp;VPN\'s status  / management&nbsp;</a></p>';
//		<p class="menu"><a class="menu" href="add.php" style="text-decoration: none;" title="VPN\'s add">&nbsp;VPN\'s add&nbsp;</a></p>
//		<p class="menu"><a class="menu" href="del.php" style="text-decoration: none;" title="VPN\'s deletion">&nbsp;VPN\'s deletion&nbsp;</a></p>
//		<p class="menu"><a class="menu" href="mod.php" style="text-decoration: none;" title="VPN\'s edit">&nbsp;VPN\'s edit&nbsp;</a></p>
echo '
		<p class="menu"><a class="menu" href="../template.php" style="text-decoration: none;" title="VPN\'s template management">&nbsp;VPN\'s template&nbsp;</a></p>
		<p class="menu"><a class="menu" href="../research.php" style="text-decoration: none;" title="Research">&nbsp;Research&nbsp;</a></p>
		<p class="menu"><a class="menu" href="../confmanagement.php" style="text-decoration: none;" title="Configuration management">&nbsp;Configuration management&nbsp;</a></p>
		<p class="menu"><a class="menu" href="../usrlog.php" style="text-decoration: none;" title="User Log">&nbsp;User Log&nbsp;</a></p>
		<p class="menu"><a class="menu" href="../applog.php" style="text-decoration: none;" title="VPN Log">&nbsp;VPN Log&nbsp;</a></p>
		<p class="menu"><a class="menu" href="index.php" style="text-decoration: none;" title="Settings" >&nbsp;Admin&nbsp;</a></p>
';
}
       echo '
		<iframe src="../tchat.php"></iframe>
</br>
		<p class="menu"><a class="menu" href="../mysettings.php?userid='.$_SESSION['userid'].'" style="text-decoration: none;" title="Settings" >&nbsp;Settings&nbsp;</a></p>
		<p class="menu"><a class="menu" href="../logout.php?id='.$_SESSION['userid'].'" style="text-decoration: none;" title="Logout" >&nbsp;Logout&nbsp;</a></p>
                ';
?>
