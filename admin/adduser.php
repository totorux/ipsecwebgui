<?php
include 'header.php';
include '../connect.php';
include '../fonctions.php';
include '../login.php';
echo '<div id="center">
      <div id="content">';
//if (!empty($_POST['user_name']) && !empty($_POST['pwd']) && !empty($_POST['pwd2']))
if (!empty($_POST['user_name']))
{
// Save in database
	$full_name=$_POST['full_name'];
	$user_name=$_POST['user_name'];
	$user_name=no_special_character_and_space($user_name);
	$pwd=$_POST['pwd'];
	$user_level=$_POST['user_level'];
	$allow_pwd_change=$_POST['allow_pwd_change'];
	if ( empty($_GET['userid']) )
	{
		$sql="insert into users(`full_name`,`user_name`,`user_level`,`pwd`,`approuved`,`allow_pwd_change`,`salt`) values('$full_name','$user_name','$user_level','','0','$allow_pwd_change','lasteuser')";
		mysql_query($sql) or die(mysql_error()) ;
	}
	else
	{
	        $idusrsql="select * from users where `id`='$_GET[userid]'";
        	$idusrreq=mysql_query($idusrsql) or die(mysql_error());
	        while($res=mysql_fetch_assoc($idusrreq))
        	{
                	$allow_pwd_change=$res['allow_pwd_change'];
			$approuved=$res['approuved'];
	        }
		$sql="update users set full_name='$_POST[full_name]',user_name='$_POST[user_name]',user_level='$_POST[user_level]',pwd='',approuved='$approuved',allow_pwd_change='$allow_pwd_change',salt='lasteuser' where id='$_GET[userid]'";
                mysql_query($sql) or die(mysql_error()) ;
	}
        $idusrsql="select * from users where `salt`='lasteuser'";
        $idusrreq=mysql_query($idusrsql) or die(mysql_error());
	while($res=mysql_fetch_assoc($idusrreq))
        {
		$userid=$res['id'];
	}
// Check other user name
	$i=0;
        $usrsql="select * from users where `user_name`='$user_name'";
        $usrreq=mysql_query($usrsql) or die(mysql_error());
        while($res=mysql_fetch_assoc($usrreq))
        {
        	$i++;   
        }
	if ( $i > 1 )
	{
		$chkuser="ko";
	}
	else
	{
		$chkuser="ok";
	}

// Check password and save it

	$salt = genenrate_salt();
	$savpwd1 = genenrate_password($salt , $_POST['pwd']);
	$savpwd2 = genenrate_password($salt , $_POST['pwd2']);
	if ( $savpwd1 == $savpwd2 )
	{
		$chkpwd="ok";
	}
	else
	{
		$chkpwd="ko";
	}
	if ( $chkpwd=="ok" && $chkuser=="ok")
	{
		$sql="update users set user_name='$user_name', pwd='$savpwd1', salt='$salt' where id='$userid'";
	        mysql_query($sql) or die(mysql_error());

                $day=date("d");
                $week=date("W");
                $month=date("m");
                $year=date("o");
                $hour=date("H:i");
                $user=$_SESSION['userid'];
                $userlvl=$_SESSION['user_level'];
                $ip=$_SERVER['REMOTE_ADDR'];
                $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`,`ip`) values('$day','$week','$month','$year','$hour','useradd','$user','$userlvl','$user_name','$ip')";
                mysql_query($sql);

		header("Location: index.php");
	}
	else
	{
		$usrsql="select * from users where `id`='$userid'";
        	$usrreq=mysql_query($usrsql) or die(mysql_error());
	        while($res=mysql_fetch_assoc($usrreq))
		{
                        echo '<form action="adduser.php?userid='.$res['id'].'" method="post">';
		        echo '<!-- Table markup-->';
		        echo '<table id="rounded-corner">';
		        echo '<!-- Table header-->';
		        echo '<thead>';
		        echo '<tr><th scope="col" class="rounded-company">Edit new user</th><th scope="col" class="rounded-q4"></th></tr>';
		        echo '</thead>';
		        echo '<!-- Table footer-->';
		        echo '<tfoot>';
		        echo '<td class="rounded-foot-left">
		        </td>';
			if ( $chkuser=="ko" && $chkpwd=="ok" )
			{
	                        echo '<td class="rounded-foot-right" align="right">User already used !!<input type="submit" name="Send" value="send"></td>';
			}
			if ( $chkpwd=="ko" &&  $chkuser=="ok")
                        {
	                        echo '<td class="rounded-foot-right" align="right">Password no corresponding !!<input type="submit" name="Send" value="send"></td>';
                        }
                        if ( $chkpwd=="ko" &&  $chkuser=="ko")
                        {
	                        echo '<td class="rounded-foot-right" align="right">User already used and password no corresponding !!<input type="submit" name="Send" value="send"></td>';
                        }
		        echo '</tfoot>';
		        echo '<!-- Table body -->';
		        echo '<tbody>';
		        echo '<tr>
		        <td>Full name</td>
		        <td>
		        <input type="hidden" name="id" value="'.$res['id'].'"/>
		        <input type="text" name="full_name" value="'.$res['full_name'].'"/>
		        </td>
		        </tr>';
		        echo '<tr>
		        <td>User name</td>
		        <td>
		        <input type="text" name="user_name" value="'.$res['user_name'].'"/>
		        </td>
		        </tr>';
                        if ( $chkpwd=="ko")
                        {
                                echo '<tr>
                                <td>Password</td>
                                <td>
                                <input type="password" name="pwd"/>
                                </td>
                                </tr>';
                                echo '<tr>
                                <td>Password check</td>
                                <td>
                                <input type="password" name="pwd2"/>
                                </td>
                                </tr>';

                        }
			else
			{
			        echo '<tr>
			        <td>Password</td>
			        <td>
		        	<input type="password" name="pwd" value="'.$_POST['pwd'].'"/>
			        </td>
			        </tr>';
			        echo '<tr>
		        	<td>Password check</td>
			        <td>
			        <input type="password" name="pwd2" value="'.$_POST['pwd'].'"/>
			        </td>
		        	</tr>';
			}
			if ( $res['user_level'] == "1")
			{
				$levelname="User";
			}
			else
			{
				$levelname="Administrator";
			}
		        echo '<tr>
		        <td>User lever</td>
		        <td>
		        <select name="user_level">
				<option value="'.$res['user_level'].'" selected>'.$levelname.'</option>
				<option value="1">----</option>
		                <option value="1">User</option>
                		<option value="5">Administrator</option>
		        </select>
		        </td>
		        </tr>';
		       echo '<tr>
		        <td>Allow user password change</td>
		        <td>';
                        if ( $res['allow_pwd_change'] == "1")
                        {
                                echo '<input type="checkbox" name="allow_pwd_change" checked value="1"/>';
                        }
                        else
                        {
                                echo '<input type="checkbox" name="allow_pwd_change" value="1"/>';
                        }
		        echo '</td>
		        </tr>';
		        echo '</tbody></table>';
			echo '</tbody>';
			echo "</table>";
        	}
	}
}
if (empty($_GET['userid']) && empty($_POST['user_name']))
{
	echo '<form action="adduser.php" method="post">';
        echo '<!-- Table markup-->';
        echo '<table id="rounded-corner">';
        echo '<!-- Table header-->';
        echo '<thead>';
        echo '<tr><th scope="col" class="rounded-company">Add a new user</th><th scope="col" class="rounded-q4"></th></tr>';
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
        <input type="text" name="full_name"/>
        </td>
        </tr>';
        echo '<tr>
        <td>User name</td>
        <td>
        <input type="text" name="user_name"/>
        </td>
        </tr>';
        echo '<tr>
        <td>Password</td>
        <td>
        <input type="password" name="pwd"/>
        </td>
        </tr>';
        echo '<tr>
        <td>Password check</td>
        <td>
        <input type="password" name="pwd2"/>
        </td>
        </tr>';
        echo '<tr>
        <td>User lever</td>
        <td>
	<select name="user_level">
		<option value="1">User</option>
		<option value="5">Administrator</option>
	</select>
        </td>
        </tr>';
       echo '<tr>
        <td>Allow user password change</td>
        <td>
        <input type="checkbox" name="allow_pwd_change" value="1"/>
        </td>
        </tr>';
        echo '</tbody></table>';
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
