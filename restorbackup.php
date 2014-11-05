<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';

echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{

        if (empty($_POST['confirme']))
        {
                echo '<table">
                <tr><td align=center><br> Do you confirme to restore all VPN configuration ?<b></b><br>
                <form action="restorbackup.php" method="post" name="form2" id="form2">
                <input type="hidden" name="confirme" value="1">
                <br><input type="submit" name="Confirme" value="Confirme">
                </form></td></tr></table></br>';
        }

        if (!empty($_POST['confirme']))
        {
		echo "Restore in progress ...";
		$command=("echo restore >> working/todo.task");
                echo exec($command);
        }
}
else
{
        header("Location: index.php");
}
echo '</div>';

echo '<div id="sidebar">';

	include 'menu.php';

echo '</div><!-- #sidebar -->
      </div><!-- #center -->';

include 'footer.php';
?>
