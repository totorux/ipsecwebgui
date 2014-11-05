<?php
  /*
  * Exemple de tchat en ajax
  * Codé par gnieark http://blog-du-grouik.tinad.fr aout 2011 - février 2012
  * Distribué sans aucune garantie dans les conditions établies là http://blog-du-grouik.tinad.fr/pages/Mentions-l%C3%A9gales
  *
  * Les smileys distribués avec la version sur le blog proviennent de phpBB3  
  * En license: 	Licence Publique Générale GNU v2
  */

  //**********Options à parametrer**************
  $nombreDeMessagesGardes=50;
  $enableSmileys=false;
  $smileysPath="smilies/";
  $mysqlparam=array(
      'username'	 => 'vpngui',
      'password'	=> 'vpngui',
      'host'	=> 'localhost',
      'database'	=> 'vpngui'
    );
  //********** Fin des options ************
session_start();
$usrlvl=$_SESSION['user_level'];

    //connexion mysql
    if (!mysql_connect($mysqlparam['host'], $mysqlparam['username'], $mysqlparam['password'])) {
	erreur('Impossible de se connecter à MySQL');
	die;
    }
    mysql_query("USE ".$mysqlparam['database']);
  //Nettoyer les variables en entrée
  foreach($_POST as $keyname =>$value){
    $$keyname=mysql_real_escape_string(htmlentities(utf8_decode($value)));
  }
  if (!isset($act)){
    $act="";
  }
  switch($act)
  {
      case "refresh":
	if(!isset($lasttime)){
	  die;
	}
//	$rs=mysql_query("SELECT time,pseudo,message FROM tchat WHERE time > '".$lasttime."' ORDER BY time DESC LIMIT 0".$nombreDeMessagesGardes);
//	$rs=mysql_query("SELECT time,pseudo,message FROM tchat WHERE time > '".$lasttime."' AND user_level <= '$usrlvl' ORDER BY time DESC LIMIT 0".$nombreDeMessagesGardes);
	$rs=mysql_query("SELECT time,pseudo,message FROM tchat WHERE user_level <= '$usrlvl' ORDER BY `key` DESC LIMIT 1".$nombreDeMessagesGardes);
	while($r=mysql_fetch_row($rs)){
	  $messages[]=array($r[0],$r[1],$r[2]);
	}
	$messages=array_reverse($messages);
	echo(json_encode($messages));
//	echo(json_encode($id));
	die;
      break;
      case "add":
	if((!isset($pseudo)) OR ($pseudo =="") OR (!isset($message)) OR ($message=="")){
	  echo "variable vide";
	  die; //il y a un probleme, on kill le script
	}
	if($enableSmileys AND file_exists($smileysPath."smileys.php")){
	  //les smilays
	  include $smileysPath."smileys.php";
	  foreach($phpbb_smilies as $smile){
	    $message=str_replace($smile['code'],'<img src="'.$smileysPath.$smile['smiley_url'].'" alt="'.$smile['smiley_url'].'"/>',$message);
	  }
	}
	mysql_query("INSERT INTO tchat (time,pseudo,message) VALUES (NOW(),'".$pseudo."','".$message."')");
	die;
      break;

      case "":
	break;
      default:
	//aucun des cas prévu, on stoppe le script.
	die;
	break;
  }
?>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="copyright" content="Distribué sans aucune garantie. Faites ce que vous voulez du code, à condtion d'y laisser la paternité." />
  <meta name="author" content="Gnieark http://blog-du-grouik.tinad.fr 2011-2012" />
  <title>tchat</title>
-->
<style type="text/css">
    #tchat{width: 100%; height: 100%; overflow-y: auto; font-family:Arial;}
    .pseudo{padding-right: 3px; font-style: bold; color: #046380; font-family:Arial;}
    .date{padding-right: 3px; font-family:Arial;}
/*    fieldset{padding: 1em; font:80%/1 sans-serif;}
    fieldset legend{font-size: 12px;color: #20326C;}
    fieldset textarea, fieldset input{width:40%;}
    fieldset label {float:left; width:40%; margin-right:0.5em; padding-top:0.2em; text-align:right;}*/
  </style>
  <script type="text/javascript">

  function onKeyEnter(key)
  {      
    if (key == 13) {
      sendmessage(document.getElementById('pseudo').value,document.getElementById('message').value);
      return true;
    }
    return false;
  }
  function Ajx() 
  {
    var request = false;
        try {request = new ActiveXObject('Msxml2.XMLHTTP');}
        catch (err2) {try {request = new ActiveXObject('Microsoft.XMLHTTP');}
          	catch (err3) {try {request = new XMLHttpRequest();}
			catch (err1) {request = false;}
           	}
        }
    return request;
  }
  function refreshchat(time)
  {
    var xhr = Ajx(); 
    xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
      if(xhr.status  == 200) {
	var messages= new Array();
	eval ("messages = " + xhr.responseText);
	var yaDesNouveauxMessages=false;
	for (var l in messages){
	  yaDesNouveauxMessages=true;
	  var pmessage=document.createElement("p");
	  pmessage.innerHTML='<em class="date">'+ messages[l][0] + '</em><em class="pseudo"></br>' + messages[l][1] + ':</em>' + messages[l][2];
	  document.getElementById("tchat").appendChild(pmessage);
	  lasttime=messages[l][0];
	}
	if (yaDesNouveauxMessages){
	  //on redescend la scrollbar
	  document.getElementById("tchat").scrollTop=document.getElementById("tchat").scrollHeight;
	}
	t=setTimeout("refreshchat(lasttime)",3000);
      }else{
	alert("Error code " + xhr.status);
      }
    }};
    xhr.open("POST", "tchat.php",  true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('act=refresh&lasttime=' + time);
  }
  function sendmessage(pseudo,message)
  {
    pseudo = pseudo.replace(/&/g,"%26");
    message= message.replace(/&/g,"%26");
    pseudo = pseudo.replace(/\+/g,"%2B");
    message= message.replace(/\+/g,"%2B");
    if ((message=='') || (pseudo=='')){
      alert('Veuillez mettre un pseudo et un message');
    }
    var xhr = Ajx(); 
    xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
      if(xhr.status  == 200) {
	document.getElementById("message").value="";
      }else{
	alert("Error code " + xhr.status);
      }
    }};
    xhr.open("POST", "tchat.php",  true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('act=add&pseudo=' + pseudo +  '&message=' + message );
  }
</script>
<!--</head>
<body>
<div id="page">-->
<div id="tchat">
  <script type="text/javascript">
	<!--
		lasttime='0';
		refreshchat(lasttime);
	//-->
  </script>
</div>
<!--<div id="reponse">
  <fieldset><legend>Envoyer un message</legend>
    <label for="pseudo">Pseudonyme:</label><input type="text" id="pseudo"/>
    <label for="message">Message</label><textarea onkeypress="onKeyEnter(event.keyCode);" id="message"></textarea>
    <input type="button" value="Envoyer" onClick="sendmessage(document.getElementById('pseudo').value,document.getElementById('message').value);"/>
  </fieldset>
</div>-->
<!--</div>
</body>-->
<!--</html>-->
