<?php
include 'connect.php';
include 'login.php';
$userid=$_GET['id'];
echo "$userid";
$sql="update users set `key`='1' where id='$userid'";
mysql_query($sql) or die(mysql_error()) ;
$_SESSION['key']="";
                $day=date("d");
                $week=date("W");
                $month=date("m");
                $year=date("o");
                $hour=date("H:i");
                $user=$_SESSION['userid'];
                $userlvl=$_SESSION['user_level'];
                $ip=$_SERVER['REMOTE_ADDR'];
                $sql="insert into vpnlog(`day`,`week`,`month`,`year`,`hour`,`type`,`user`,`user_level`,`sitename`,`ip`) values('$day','$week','$month','$year','$hour','logout','$user','$userlvl','','$ip')";
                mysql_query($sql);
session_unset ();
session_destroy ();
header("Location: index.php");
exit();
?>
