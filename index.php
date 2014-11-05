<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
include 'login.php';
echo '<div id="center">
      <div id="content">';
exec("./scriptvpn.sh updatevpnstat");
$sitesql="select distinct siteconf from site_conf order by siteconf asc";
$sitereq=mysql_query($sitesql) or die(mysql_error());
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
		echo '<tr><th scope="col" class="rounded-company">VPN</th><th scope="col" class="rounded-q1">Ping</th><th scope="col" class="rounded-q3">Status</th><th scope="col" class="rounded-q4">Port test</th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
		echo '<td colspan="3" class="rounded-foot-left"></td>
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
		echo "<tr class='site'><td>&nbsp;&nbsp;&nbsp;&nbsp;$site&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;$pubip - $network</td><td class='center'>";
                if ($testping == "alive")
                        {
                                echo '<img src="img/green.png" width=15 height=15 alt="Alive" title="Alive">';
                        }
                else
                        {
                                echo '<img src="img/red.png" width=15 height=15 alt="Unreachable" title="Unreachable">';
                        }
		echo "</td>";
		$vpnstatus=0;
		$vpnsql="select vpnname from site_conf where siteconf = '$site'";
		$vpnreq=mysql_query($vpnsql) or die(mysql_error());
		while($vpns=mysql_fetch_assoc($vpnreq))
			{
				$vpn=$vpns["vpnname"];
		                $vpnstatsql="select vpn from statusvpn where vpn = '$vpn'";
		                $vpnstatreq=mysql_query($vpnstatsql) or die(mysql_error());
				$vpns=mysql_fetch_assoc($vpnstatreq);
//				if((empty($vpns)) || ($testping == "unreachable"))
				if(empty($vpns))
					{
					}
				else
					{
						$vpnstatus++;
					}
			}
		if ( $vpnstatus >= 2 )
		{
			echo '<td class="center"><a href="#" onClick="window.open(\'reloadsite.php?site='.$site.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=300, height=100\');return(false)"><img src="img/green.png" width=15 height=15 alt="Site VPN started" title="Site VPN started"></a></td>';
		}
		else
		{
			echo '<td class="center"><a href="#" onClick="window.open(\'reloadsite.php?site='.$site.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=300, height=100\');return(false)"><img src="img/cycle.png" width=15 height=15 alt="Site VPN restart" title="Site VPN restart"></a></td>';
		}
		if (file_exists("log/port-$site.log"))
		{
			echo '<td class="center"><a href="#" onClick="window.open(\'porttest.php?site='.$site.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=650, height=300\');return(false)"><img src="img/done.png" width=15 height=15 alt="Check port" title="Check port"></a></td></tr>';
		}
		else
		{
			echo '<td class="center"><a href="#" onClick="window.open(\'porttest.php?site='.$site.'\',\'_blank\',\'left, toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=650, height=300\');return(false)"><img src="img/Config-Tools.png" width=15 height=15 alt="Check port" title="Check port"></a></td></tr>';
		}
	}
echo '</tbody>';
echo "</table>";
echo '</div><!-- #content -->';

echo '<div id="sidebar">';

	include 'menu.php';

echo '</div><!-- #sidebar -->
      </div><!-- #center -->';

include 'footer.php';
?>
