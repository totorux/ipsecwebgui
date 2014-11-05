<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
include 'login.php';
echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
	exec("./scriptvpn.sh updatevpnstat");
	$sitesql="select distinct siteconf from site_conf order by siteconf asc";
	$sitereq=mysql_query($sitesql) or die(mysql_error());
	echo '<!-- Table markup-->';
        echo '<table id="rounded-corner">';
	echo '<!-- Table header-->';
	echo '<thead>';
        echo '<tr><th scope="col" class="rounded-company">VPN</th><th scope="col" class="rounded-q1">Ping</th><th scope="col" class="rounded-q2">Status</th><th scope="col" class="rounded-q3">Port test</th><th scope="col" class="rounded-q4">Action&nbsp;<a href="addsite.php?site=""" title="Add a new site"><img src="img/add.png" width=15 height=15 alt="Add new site" title="Add new site"></a></th></tr>';
	echo '</thead>';
	echo '<!-- Table footer-->';
	echo '<tfoot>';
        echo '<td colspan="4" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>';
	echo '</tfoot>';
	echo '<!-- Table body -->';
	echo '<tbody>';
	while($site=mysql_fetch_assoc($sitereq))
	{
		$site=$site["siteconf"];
		$vpnsql="select * from site_conf where siteconf = '$site'";
		$vpnreq=mysql_query($vpnsql) or die(mysql_error());
		while($vpn=mysql_fetch_assoc($vpnreq))
	        {
        	        $pubip=$vpn["right"];
                	$network=$vpn["rightsubnet"];
	        }
                $testping=pingsite($site);
		echo "<tr class='site'><td>&nbsp;&nbsp;&nbsp;&nbsp;$site&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;$pubip - $network</td><td class='center'> ";
                if ($testping == "alive")
                        {
                                echo '<img src="img/green.png" width=15 height=15 alt="Alive" title="Alive">';
                        }
                else
                        {
                                echo '<img src="img/red.png" width=15 height=15 alt="Unreachable" title="Unreachable">';
                        }
		echo "</td>";
		echo '<td class="center"><a href="#" onClick="window.open(\'reloadsite.php?site='.$site.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=300, height=100\');return(false)"><img src="img/cycle-green.png" width=15 height=15 alt="Site VPN restart" title="Site VPN restart"></a></td>';
                if (file_exists("log/port-$site.log"))
                {
                        echo '<td class="center"><a href="#" onClick="window.open(\'porttest.php?site='.$site.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=650, height=300\');return(false)"><img src="img/done.png" width=15 height=15 alt="Check port" title="Check port"></a></td>';
                }
                else
                {
                        echo '<td class="center"><a href="#" onClick="window.open(\'porttest.php?site='.$site.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=650, height=300\');return(false)"><img src="img/Config-Tools.png" width=15 height=15 alt="Check port" title="Check port"></a></td>';
                }
                        echo '<td class="center">
<a href="addvpn.php?site='.$site.'" title="Add new vpn site '.$site.'"><img src="img/add.png" width=15 height=15 alt="Add new vpn site '.$site.'" title="Add new vpn site '.$site.'"></a>
&nbsp;
<a href="delsite.php?site='.$site.'" title="Delete site '.$site.'"><img src="img/del.png" width=15 height=15 alt="Delete site '.$site.'" title="Delete site '.$site.'"></a>
</td></tr>';
		$vpnsql="select * from site_conf where siteconf = '$site' order by `vpnname` asc";
		$vpnreq=mysql_query($vpnsql) or die(mysql_error());
		while($vpns=mysql_fetch_assoc($vpnreq))
			{
				echo "<tr class='left'>";
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$vpns["vpnname"].' - '.$vpns["leftsubnet"].'</td>';
				$vpn=$vpns["vpnname"];
		                $vpnstatsql="select vpn from statusvpn where vpn = '$vpn'";
		                $vpnstatreq=mysql_query($vpnstatsql) or die(mysql_error());
				$vpns=mysql_fetch_assoc($vpnstatreq);
//				if((empty($vpns)) || ($testping == "unreachable"))
				if(empty($vpns))
				{
					echo "<td></td>";
					echo '<td class="center"><a href="#" onClick="window.open(\'reloadvpn.php?vpn='.$vpn.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=300, height=100\');return(false)"><img src="img/red.png" width=15 height=15 alt="Site VPN started" title="Site VPN started"></a></td><td></td>';
				}
				else
				{
                        		echo "<td></td>";
#					$vpnup++;
					echo '<td class="center"><a href="#" onClick="window.open(\'reloadvpn.php?vpn='.$vpn.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=300, height=100\');return(false)"><img src="img/green.png" width=15 height=15 alt="Site VPN started" title="Site VPN started"></a></td><td></td>';
				}
				echo '<td class="center">
				<a href="modvpnreseach.php?site='.$site.'&vpn='.$vpn.'" title="Edit vpn '.$vpn.'- site '.$site.'"><img src="img/edit.png" width=15 height=15 alt="Edit vpn '.$vpn.'- site '.$site.'" title="Edit vpn '.$vpn.'- site '.$site.'"></a>
				&nbsp;
				<a href="delvpnreseach.php?vpn='.$vpn.'&site='.$site.'" title="Delete vpn '.$vpn.'- site '.$site.'"><img src="img/del.png" width=15 height=15 alt="Delete vpn '.$vpn.'- site '.$site.'" title="Delete vpn '.$vpn.'- site '.$site.'"></a>
</td>';
				echo "</tr>";
			}
	}
	echo '</tbody>';
	echo "</table>";
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
