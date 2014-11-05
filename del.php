<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';

echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{

echo '<div align="center"></br></br></br></br><font size=5>
<a class="bodytop" href="delsite.php" title="Delete a all site">&nbsp;&nbsp;Delete a all site&nbsp;&nbsp;</a>
</br></br></br>';

echo '<a class="bodytop" href="delvpn.php" title="Delete a VPN">&nbsp;&nbsp;Delete a VPN&nbsp;&nbsp;</a>
</font>
</div>';
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
