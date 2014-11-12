<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
	$album = explode('=', urldecode(parse_url($_SERVER['REQUEST_URI'])["query"]));
	$album = end($album);
	
	$album_name = $db->query("SELECT Album FROM Songs WHERE IDAlbum COLLATE NOCASE = '$album' GROUP BY Album COLLATE NOCASE");
	$album_name = $album_name->fetchArray();
	$album_name = end($album_name);
	printf("<h2>%s</h2>", $album_name);
	
	$query = "SELECT * FROM Songs WHERE IDAlbum = '$album' COLLATE NOCASE";
	paginate($db, $query);
?>