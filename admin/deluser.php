<?php
include 'header.php';
include '../connect.php';
include '../fonctions.php';
include '../login.php';
echo '<div id="center">
      <div id="content">';
if ( !empty($_POST['delconf']) )
{
	if ($_POST['delconf']="1")
	{

	        $idusrsql="select * from users where `id`='$_GET[userid]'";
       	 	$idusrreq=mysql_query($idusrsql) or die(mysql_error());
        	while($res=mysql_fetch_assoc($idusrreq))
       		{
			$user_name=$res['user_name'];
		}
//		echo "$_GET[userid]";
                $sql="delete from users where `id`=".$_GET['userid']."";
                mysql_query($sql) or die(mysql_error());

                $day=date("d");
                $week=date("W");
                $month=date("m");
                $year=date("o");
                $hour=date("H:i");
                $user=$_SESSION['userid'];
                $userlvl=$_SESSION['user_level'];
                $ip=$_SERVER['REMOTE_ADDR'];
                $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`,`ip`) values('$day','$week','$month','$year','$hour','userdel','$user','$userlvl','$user_name','$ip')";
                mysql_query($sql);

		header("Location: index.php");
	}
}
if (!empty($_GET['userid']) && empty($_POST['delconf']))
{
        $idusrsql="select * from users where `id`='$_GET[userid]'";
        $idusrreq=mysql_query($idusrsql) or die(mysql_error());
        while($res=mysql_fetch_assoc($idusrreq))
        {
	        $allow_pwd_change=$res['allow_pwd_change'];
        	$approuved=$res['approuved'];
		$full_name=$res['full_name'];
		$user_name=$res['user_name'];
		$user_level=$res['user_level'];
		$allow_pwd_change=$res['allow_pwd_change'];
	echo '<form action="deluser.php?userid='.$_GET['userid'].'" method="post">';
        echo '<!-- Table markup-->';
        echo '<table id="rounded-corner">';
        echo '<!-- Table header-->';
        echo '<thead>';
        echo '<tr><th scope="col" class="rounded-company">Delete user '.$full_name.'</th><th scope="col" class="rounded-q4"></th></tr>';
        echo '</thead>';
        echo '<!-- Table footer-->';
        echo '<tfoot>';
        echo '<td class="rounded-foot-left">
        </td>
        <td class="rounded-foot-right" align="right"><input type="submit" name="Send" value="send"></td>';
        echo '</tfoot>';
        echo '<!-- Table body -->';
        echo '<tbody>';
	echo '<tr>
        <td>Full name</td>
        <td>
        <input type="hidden" name="userid" value="'.$_GET['userid'].'"/>
        <input type="text" name="full_name" value="'.$full_name.'" disabled/>
        </td>
        </tr>';
        echo '<tr>
        <td>User name</td>
        <td>
        <input type="text" name="user_name" value="'.$user_name.'" disabled/>
        </td>
        </tr>';
        echo '<tr>
        <td>Check to confirm you want to delet user: '.$full_name.'</td>
        <td>
        <input type="checkbox" name="delconf" value="1"/>
        </td>
        </tr>';
        echo '</tbody></table>';
	}
echo '</tbody>';
echo "</table>";
}
echo '</div><!-- #content -->';

echo '<div id="sidebar">';

        include 'menu.php';

echo '</div><!-- #sidebar -->
      </div><!-- #center -->';

include '../footer.php';
?>
