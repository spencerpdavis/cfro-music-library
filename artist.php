<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
	$artist = explode('=', urldecode(parse_url($_SERVER['REQUEST_URI'])["query"]));
	$artist = end($artist);
	printf("<h2>%s</h2>", $artist);
	
	$artist_query = "SELECT * FROM Songs WHERE Artist = '$artist' COLLATE NOCASE GROUP BY Album COLLATE NOCASE";
	paginate($db, $artist_query);
?>