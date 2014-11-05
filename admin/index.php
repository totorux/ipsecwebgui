<?php
include 'header.php';
include '../connect.php';
//include '../fonctions.php';
include '../login.php';
echo '<div id="center">
      <div id="content">';
/*foreach ($_SESSION as $k=>$v) {
    echo "$k => $v <br />\n";
}
*/
//if ($_SESSION['user_level'] >= 4)
//{
if (!empty($_GET['userid']))
{
	$userid=$_GET['userid'];
	$act=$_GET['act'];
	$sql="update users set approuved='$act' where id='$userid'";
	mysql_query($sql) or die(mysql_error());
}

	$usrsql="select * from users order by full_name asc";
	$usrreq=mysql_query($usrsql) or die(mysql_error());
	echo '<!-- Table markup-->';
        echo '<table id="rounded-corner">';
	echo '<!-- Table header-->';
	echo '<thead>';
        echo '<tr>
	<th scope="col" class="rounded-company">User ID</th>
	<th scope="col" class="rounded-q1">Full name</th>
	<th scope="col" class="rounded-q2">User name</th>
	<th scope="col" class="rounded-q2">User level</th>
	<th scope="col" class="rounded-q2">Approuved</th>
	<th scope="col" class="rounded-q4">Action<a href="adduser.php?userid=""" title="Add a new user"><img src="../img/add.png" width=15 height=15 alt="Add new user" title="Add new user"></a></th>
	</tr>';
	echo '</thead>';
	echo '<!-- Table footer-->';
	echo '<tfoot>';
        echo '<td colspan="5" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>';
	echo '</tfoot>';
	echo '<!-- Table body -->';
	echo '<tbody>';
	while($res=mysql_fetch_assoc($usrreq))
	{
                echo "<tr class='site'>
		<td class='center'>$res[id]</td>
		<td>$res[full_name]</td>
		<td>$res[user_name]</td>";
		if ("$res[user_level]" == "5")
                {
			echo "<td class='center'>Administrator</td>";
		}
                if ("$res[user_level]" == "1")
                {
			echo "<td class='center'>User</td>";
                }
		if ("$res[approuved]" == "1")
		{
			echo "<td class=center><a href='index.php?userid=$res[id]&act=0' title='Disallow user $res[full_name] access'><img src='../img/green.png' width=15 height=15 alt='Approuved'></a></td>";
		}
		else
		{
			echo "<td class=center><a href='index.php?userid=$res[id]&act=1' title='Allow user $res[full_name] access'><img src='../img/red.png' width=15 height=15 alt='Not approuved'></a></td>";
		}
		echo "
		<td class='center'>
		<a href='moduser.php?userid=$res[id]' title='Edit user $res[full_name]'><img src='../img/edit.png' width=15 height=15 alt='Edit user $res[full_name]'></a>
                &nbsp;
                <a href='deluser.php?userid=$res[id]' title='Delete user $res[full_name]'><img src='../img/del.png' width=15 height=15 alt='Delete user $res[full_name]'></a>
		</td>";
		echo "</tr>";
		
	}
	echo "</table>";
	echo '</div><!-- #content -->';
//}
//else
//{
//	header("Location: index.php");
//}
echo '<div id="sidebar">';

	include 'menu.php';

echo '</div><!-- #sidebar -->
      </div><!-- #center -->';

include '../footer.php';
?>

