<?php
include 'header.php';
include 'connect.php';
include 'fonctions.php';
if ($_SESSION['user_level'] >= 4)
{
echo '<div id="center">
      <div id="content">';

	$dossier = 'upload/';
        $fichier = $_FILES['filename']['name'];
	$extension = strrchr($_FILES['filename']['name'], '.');
	if ( "$extension" == ".gz" )
	{
		move_uploaded_file($_FILES["filename"]["tmp_name"],
		"$dossier/" . $fichier);
			echo "File uploaded ...</br>";
	        $command=("./scriptvpn.sh chekbackup $fichier");
		$finaltest = exec($command);
		if ( $finaltest == "ok" )
		{
			echo "File test passed...</br>";
			echo '<a href="restorbackup.php" title="Backup restore">Click here to apply backup !</a>';
		}
		else
		{
			echo "Restor abored";
		}

	}	
	else
	{
		echo "Not correct type of file (.tar.gz)";
	}

if ( $action = "ok" )
{

}

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
