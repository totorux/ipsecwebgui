<?php
include 'header-mini.php';
include 'connect.php';
if ($_SESSION['user_level'] >= 0)
{
$usrlvl=$_SESSION['user_level'];
echo '<img src="img/charge.gif" width=50 height=50 alt="VPN reload in progress..." title="VPN reload in progress...">';
$site = $_GET['site'];
//$command=("./scriptvpn.sh reloadsite $site");
$command=("./scriptvpn.sh restartsite $site $usrlvl");
echo exec($command);
$day=date("d");
$week=date("W");
$month=date("m");
$year=date("o");
$hour=date("H:i");
$user=$_SESSION['userid'];
$userlvl=$_SESSION['user_level'];

$sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`) values('$day','$week','$month','$year','$hour','sitereload','$user','$userlvl','$site')";
mysql_query($sql);

echo "
<script language=\"JavaScript\">
<!--  
window.close();  
-->  
</script>";
}
else
{
        header("Location: login.php");
}
?>
