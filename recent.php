<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
	$recent_query = "SELECT * FROM Songs GROUP BY Album COLLATE NOCASE";
?>
<body>
	<h2>Recent Additions</h2>
	<?php
		paginate($db, $recent_query)
	?>
</body>