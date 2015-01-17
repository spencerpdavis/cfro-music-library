<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
	$album = SQLite3::escapeString($_GET['album']);
	
	$album_name = $db->query("SELECT Album FROM Songs WHERE IDAlbum COLLATE NOCASE = '$album' GROUP BY Album COLLATE NOCASE");
	$album_name = $album_name->fetchArray();
	$album_name = end($album_name);

    $artist_name = $db->query("SELECT Artist FROM Songs WHERE IDAlbum COLLATE NOCASE = '$album' GROUP BY Artist COLLATE NOCASE");
    $artist_name = $artist_name->fetchArray();
    $artist_name = end($artist_name);

	printf("<h2>%s - %s</h2>", $artist_name, $album_name);
	
	$query = "SELECT * FROM Songs WHERE IDAlbum = '$album' COLLATE NOCASE";
?>
<body>
    <article>
    <?php
    	paginate($db, $query);
    ?>
    </article>
</body>
