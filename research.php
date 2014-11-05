<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
include 'login.php';

echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
if (empty($_POST['research']))
{
	echo '<h1 class="glob">Make a reseach</h1>';
        echo '<form action="research.php" method="post">';
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Enter your research (IP, name...)</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
        echo '<td align="center"><input type="text" name="research"/>';
        echo '</select></td>';
        echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></td></tr>';
        echo '</tbody></table>';
}

if (!empty($_POST['research']))
{
	echo '<h1 class="glob">Reseach result for: ';
	$_POST['research'] = no_special_character($_POST['research']);
        echo $_POST['research'];
        echo '</h1>';	
	foreach ( array("siteconf", "vpnname", "left", "leftid", "leftsubnet", "right", "rightid", "secretkey", "rightsubnet", "templateid") as $type)
	{
		$data=reasearchparam($_POST['research'], $type);
		$result=$data[0];
		$nbval=$data[1];
		if (!empty($result))
		{
			echo '<h2 class="log">In '.$type.' we have '.$nbval.' result</h2>';
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
echo '<th scope="col" class="rounded-company">&nbsp;Site&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;VPN&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;Left&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;Leftid&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;Leftsubnet&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;Right&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;Rightid&nbsp;</th><th scope="col" class="rounded-q1">&nbsp;Rightsubnet&nbsp;</th>';
if ($_SESSION['user_level'] >= 4)
{
echo '<th  scope="col" class="rounded-q1">&nbsp;Secretkey&nbsp;</th><th  scope="col" class="rounded-q1">&nbsp;Templateid&nbsp;</th><th  scope="col" class="rounded-q4">&nbsp;Actions&nbsp;</th>';
}
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="10" class="rounded-foot-left"></td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
			$i=0;
			while(!empty($result[$i]['key']))
			{
				echo "<tr class='site'><td class='left'>&nbsp;".$result[$i]['siteconf']."&nbsp;</td>";
				echo "<td class='left'>&nbsp;".$result[$i]['vpnname']."&nbsp;</td>";
				echo "<td class='center'>&nbsp;".$result[$i]['left']."&nbsp;</td>";
                                echo "<td class='center'>&nbsp;".$result[$i]['leftid']."&nbsp;</td>";
                                echo "<td class='center'>&nbsp;".$result[$i]['leftsubnet']."&nbsp;</td>";
                                echo "<td class='center'>&nbsp;".$result[$i]['right']."&nbsp;</td>";
                                echo "<td class='center'>&nbsp;".$result[$i]['rightid']."&nbsp;</td>";
                                echo "<td class='center'>&nbsp;".$result[$i]['rightsubnet']."&nbsp;</td>";
if ($_SESSION['user_level'] >= 4)
{
				echo "<td class='center'>&nbsp;".$result[$i]['secretkey']."&nbsp;</td>";
                                echo "<td class='center'>&nbsp;".$result[$i]['templateid']."&nbsp;</td>";
				echo '<td class=\'center\'>&nbsp;
				<a href="modvpnreseach.php?vpn='.$result[$i]['vpnname'].'&site='.$result[$i]['siteconf'].'"><img src="img/Config-Tools.png" width=15 height=15 alt="Site VPN started" title="Edit VPN configuration"></a>
				&nbsp;
				<a href="delvpnreseach.php?vpn='.$result[$i]['vpnname'].'&site='.$result[$i]['siteconf'].'"><img src="img/DeleteRed.png" width=15 height=15 alt="Site VPN started" title="Deleted VPN"></a>
				&nbsp;</td></tr>';
}
				$i++;
			}
echo '</tbody>';
echo '</table>';
			
		}
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
