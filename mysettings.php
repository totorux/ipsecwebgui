<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
//include 'login.php';
session_start();
echo '<div id="center">
      <div id="content">';

$usrsql="select `key` from users where `id`='$_SESSION[userid]'";
$usrreq=mysql_query($usrsql) or die(mysql_error());
while($res=mysql_fetch_assoc($usrreq))
{
	$datakey=$res['key'];
}
$sessionkey=$_SESSION[key];
if ( $datakey != $sessionkey || is_null($sessionkey) )
{
	header("Location: index.php");
}


if (!empty($_POST['user_name']) && !empty($_GET['userid']))
{
// Save in database
	$userid=$_GET['userid'];
	$full_name=$_POST['full_name'];
	$user_name=$_POST['user_name'];
	$user_name=no_special_character_and_space($user_name);
	$sql="update users set full_name='$_POST[full_name]',user_name='$_POST[user_name]' where id='$_GET[userid]'";
         mysql_query($sql) or die(mysql_error()) ;
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
		if (!empty($_POST['pwd']) && !empty($_POST['pwd2']))
		{
	                $sql="update users set user_name='$user_name', pwd='$savpwd1', salt='$salt' where id='$userid'";
		}
		else
		{
			$sql="update users set user_name='$user_name' where id='$userid'";
		}
	        mysql_query($sql) or die(mysql_error());
		header("Location: index.php");
	}
	else
	{
		$usrsql="select * from users where `id`='$userid'";
        	$usrreq=mysql_query($usrsql) or die(mysql_error());
	        while($res=mysql_fetch_assoc($usrreq))
		{
                        echo '<form action="mysettings.php?userid='.$res['id'].'" method="post">';
		        echo '<!-- Table markup-->';
		        echo '<table id="rounded-corner">';
		        echo '<!-- Table header-->';
		        echo '<thead>';
		        echo '<tr><th scope="col" class="rounded-company">Edit user '.$full_name.'</th><th scope="col" class="rounded-q4"></th></tr>';
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
		        <input type="text" name="user_name" value="'.$res['user_name'].'" disabled/>
			<input type="hidden" name="user_name" value="'.$res['user_name'].'"/>
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
		        echo '</tbody></table>';
			echo '</tbody>';
			echo "</table>";
        	}
	}

// Save password
// Go to admin page
}
if (!empty($_GET['userid']) && empty($_POST['user_name']))
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
		echo '<form action="mysettings.php?userid='.$_GET['userid'].'" method="post">';
	        echo '<!-- Table markup-->';
	       	echo '<table id="rounded-corner">';
        	echo '<!-- Table header-->';
	        echo '<thead>';
        	echo '<tr><th scope="col" class="rounded-company">Edit user '.$full_name.'</th><th scope="col" class="rounded-q4"></th></tr>';
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
        	<input type="text" name="full_name" value="'.$full_name.'"/>
	        </td>
        	</tr>';
	        echo '<tr>
        	<td>User name</td>
	        <td>
        	<input type="text" name="user_name" value="'.$user_name.'" disabled/>
		<input type="hidden" name="user_name" value="'.$user_name.'"/>
	        </td>
        	</tr>';
		if ($allow_pwd_change == 1)
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

include 'footer.php';
?>
