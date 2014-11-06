<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
	$recent_query = "SELECT * FROM Songs ORDER BY DateAdded";
	
	$db = new SQLite3('C:\Users\Emily\AppData\Roaming\MediaMonkey\MM.db');
	if(!$db) {
		echo $db->lastErrorMsg();
	} 
	
	if (!$db) die ($error);
	
	
?>
<body>
	<h2>Recent Additions</h2>
	<?php
		paginate($db, $recent_query)
	?>
</body>