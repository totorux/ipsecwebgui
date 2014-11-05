<?php

# Read site status in database

function pingsite($site)
{
	$sitestatsql="select status from ping where sites = '$site'";
	$sitestatreq=mysql_query($sitestatsql) or die(mysql_error());
        $stat=mysql_fetch_assoc($sitestatreq);
	return $stat['status'];
}

# Clear all input special character

function no_special_character($str, $charset='utf-8')
{
	$str=trim($str);
	$str = htmlentities($str, ENT_NOQUOTES, $charset);
	$str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
	$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
	return $str;
}

# Reaserch function

function reasearchparam($research, $param)
{
	$sql="select * from site_conf where `".$param."` like '%$research%'";
	$req=mysql_query($sql) or die(mysql_error());
	$i=0;
	while($tmp_data=mysql_fetch_assoc($req))
	{
		$data[$i]=$tmp_data;
		$i++;  
	}
	
	return array($data, $i);
}

function vpnnumber($siteconf)
{
        $result=mysql_query("select siteconf from site_conf where siteconf='$siteconf'");
        $vpnnumber=mysql_num_rows($result);
        $vpnnumber++;
	$vpnname=$siteconf.$vpnnumber;
	$result=mysql_query("select siteconf from site_conf where vpnname='$vpnname'");
	$nametest=mysql_num_rows($result);
	if ($nametest > 0)
	{
		for($i=1;$i<=$vpnnumber;$i++)
		{
			$vpnname=$siteconf.$i;
	                $result=mysql_query("select siteconf from site_conf where vpnname='$vpnname'");
        	        $nametest=mysql_num_rows($result);
			if ($nametest == 0)
			{
				$vpnnumber=$i;
			}
		}
	}
        return $vpnnumber;
}

function genenrate_salt()
{
	$rndstring = "";
	$length = 64;
	$a = "";
	$b = "";
	$template = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	settype($length, "integer");
	settype($rndstring, "string");
	settype($a, "integer");
	settype($b, "integer");
	for ($a = 0; $a <= $length; $a++) {
	$b = rand(0, strlen($template) - 1);
	$rndstring .= $template[$b];
	}

	return $rndstring;
}

function genenrate_password($salt,$pass)
{
	$password_hash = '';

	$mysalt = $salt;
	$password_hash= hash('SHA256', "-".$mysalt."-".$pass."-");

	return $password_hash;
}

function no_special_character_and_space($str, $charset='utf-8')
{

	$str=trim($str);
	$str = htmlentities($str, ENT_NOQUOTES, $charset);
	$str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
	$str = preg_replace('#&[^;]+;#', '', $str);
	$str = preg_replace('/\s+/', '', $str);

	return $str;
}

?>
