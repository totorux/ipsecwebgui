<?php
/*
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
*/
?>
