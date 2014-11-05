<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
echo '<div id="center">
      <div id="content">';
if ($_SESSION['user_level'] >= 4)
{
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Select the backup file to restor:</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="confmanagement.php" title="Back to configuration management menu">&nbsp;&nbsp;Back to configuration management VPN menu&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
echo '<tr><td align="center"><form method="POST" action="upload.php" enctype="multipart/form-data">
        File : <input type="file" name="filename" id="filename">
	</td><td align="center">
        <input type="submit" name="submint" value="Send">
        </form></td></tr>';
echo '</tbody></table>';

echo '</div>';
echo '<div id="sidebar">';

        include 'menu.php';
echo '</div><!-- #sidebar -->
      </div><!-- #center -->';
}
else
{
        header("Location: index.php");
}
include 'footer.php';
?>
