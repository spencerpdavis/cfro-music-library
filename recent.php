<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
	$recent_query = 'SELECT * FROM Songs GROUP BY Album COLLATE NOCASE ORDER BY DateAdded DESC';
?>
<body>
	<h2>Recently Added</h2>
    <article>
	<?php
		paginate($db, $recent_query)
	?>
    </article>
</body>
