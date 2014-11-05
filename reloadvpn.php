<?php
include 'header-mini.php';
include 'connect.php';
if ($_SESSION['user_level'] >= 0)
{
$usrlvl=$_SESSION['user_level'];
echo '<img src="img/charge.gif" width=15 height=15 alt="VPN reload in progress..." title="VPN reload in progress...">';
$vpn = $_GET['vpn'];
//$command=("./scriptvpn.sh reloadvpn $vpn");
$command=("./scriptvpn.sh restartvpn $vpn $usrlvl");
echo exec($command);
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
