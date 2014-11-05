<?php
echo '<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="../style/index.css" />
        <title>Ipsec VPN Manager</title>
    </head>
        <div id="header">
<table width=90% height="20%">
<tr>
<td width=30%>
<div align="right">';
/*    if(file_exists('tmp/status.log'))
   {
            $lignes = file('tmp/status.log');
            $n       = 0; 
			$bb = "";
			$ss = '<br />';
 
 
            $lignes = array_slice($lignes, $n, $n+10); 
 
            foreach($lignes AS $valeur)
           {
				$bb = $bb.$ss.$valeur;
           }
   }*/
//echo '<iframe src="tchat.php"></iframe>';
//include 'tchat.php';
echo'<!--<marquee behavior="scroll" direction="left"
scrollamount="2" scrolldelay="80" onmouseover="this.stop()"
onmouseout="this.start()" height="50" width=100% ">
<div align="center">';
echo '</div>
</marquee>-->
</div>
</td>
<td style="background : url(../img/logo-header.png) no-repeat; background-size: 100%" align="center" valign="bottom">
<b class="titre">VPN Management</b>
</td>
</tr>
</table>
</div>';
?>
