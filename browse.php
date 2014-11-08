<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
	printf("<h3>");
	$alpha = range('A', 'Z');
	foreach($alpha as $let) {
		printf("<a href='browse.php?letter=$let'> $let</a> ");
	}
	printf("</h3>");
	
	if(isset($_GET['letter'])) {
		$letter = $_GET['letter'];
		$query = "SELECT * FROM Songs WHERE Artist LIKE '$letter%' GROUP BY Album COLLATE NOCASE";
		paginate($db, $query);
	}
?>