<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
	printf("<h3>");
    $browse_by = $_GET["by"];
    echo $_GET["by"];
	$alpha = range('A', 'Z');
	foreach($alpha as $let) {
		printf("<a href='browse.php?by=$browse_by&letter=$let'> $let</a> ");
	}
	printf("</h3>");
    
    	
	if(isset($_GET['letter'])) {
		$letter = $_GET['letter'];
		$query = "SELECT * FROM Songs WHERE $browse_by LIKE '$letter%' GROUP BY Album COLLATE NOCASE";
        echo "<div>" . $query . "</div>";
		paginate($db, $query);
	}
?>
