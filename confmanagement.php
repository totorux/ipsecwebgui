<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
include 'login.php';
echo '<div id="center">
      <div id="content"></br>';
$usrlvl=$_SESSION['user_level'];
if ($_SESSION['user_level'] >= 4)
{
echo '<form action="confmanagement.php" method="post">';
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">What you want to do ?</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                <a class="table" href="detail.php" title="Back to add menu">&nbsp;&nbsp;Back VPN\'s Status&nbsp;&nbsp;</a>
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
echo '<!-- Table body -->';
echo '<tbody>';
echo '<td align="center">
<select name="action">';
echo '<option value="basetofile">Sync from base to file
<option value="filetobase">Sync from file to base
<option value="dwlbackup">Download backup file
<option value="rstbackup">Restore backup file';
echo '</select></td>';
echo '<td align="center" colspan="2"><input type="submit" name="Send" value="send"></td></tr>';
echo '</tbody></table>';

if (!empty($_POST['action']))
{

	if ($_POST['action'] == "basetofile")
	{

                $command=("./scriptvpn.sh mysqlupdate base $usrlvl");
                echo exec($command);
                echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
                echo '<!-- Table header-->';
                echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Base to file sync in progress</th><th scope="col" class="rounded-q4"></th></tr>';
                echo '</thead>';
                echo '<!-- Table footer-->';
                echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
                echo '</tfoot>';
                echo '<!-- Table body -->';
                echo '<tbody>';
                echo '<tr><td align="center"></td><td></td></tr>';
                echo '<tr><td align="center"><img src="img/charge.gif" width=50 height=50 alt="Job in progress..." title="Job in progress..."></td></td><td></tr>';
                echo '</tbody></table>';
	}

        if ($_POST['action'] == "filetobase")
        {

                $command=("./scriptvpn.sh mysqlupdate file $usrlvl");
                echo exec($command);
                echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
                echo '<!-- Table header-->';
                echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">File to base sync in progress</th><th scope="col" class="rounded-q4"></th></tr>';
                echo '</thead>';
                echo '<!-- Table footer-->';
                echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
                echo '</tfoot>';
                echo '<!-- Table body -->';
                echo '<tbody>';
                echo '<tr><td align="center"></td><td></td></tr>';
                echo '<tr><td align="center"><img src="img/charge.gif" width=50 height=50 alt="Job in progress..." title="Job in progress..."></td></td><td></tr>';
                echo '</tbody></table>';
        }

        if ($_POST['action'] == "dwlbackup")
        {

		$nb_fichier = 0;
echo '<!-- Table markup-->';
                echo '<table id="rounded-corner">';
echo '<!-- Table header-->';
echo '<thead>';
                echo '<tr><th scope="col" class="rounded-company">Select the backup to download:</th><th scope="col" class="rounded-q4"></th></tr>';
echo '</thead>';
echo '<!-- Table footer-->';
echo '<!-- Table body -->';
echo '<tbody>';
		if($dossier = opendir('./backup'))
		{
			while(false !== ($fichier = readdir($dossier)))
			{
				if($fichier != '.' && $fichier != '..' && $fichier != 'index.php')
				{
					$nb_fichier++;
					echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./backup/' . $fichier . '">' . $fichier . '</a></td><td/></tr>';
				}
 
			}

echo '<tfoot>';
                echo '<td colspan="1" class="rounded-foot-left" align="center">
		They have <strong>' . $nb_fichier .'</strong> backup
                </td>
                <td class="rounded-foot-right">&nbsp;</td>';
echo '</tfoot>';
 
			closedir($dossier);
 		}
		else
			echo 'Folder can\'t be open';
echo '</tbody></table>';
        }

        if ($_POST['action'] == "rstbackup")
        {
		header('Location: upload-select.php');

        }

}
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
