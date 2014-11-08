<body>
<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");	
	if(isset($_GET["simple_search"])) {
		$search = "%" . $_GET["simple_search"] . "%";
		$query = "SELECT * FROM Songs WHERE 
					Artist LIKE '$search' OR 
					Album LIKE '$search' OR
					SongTitle LIKE '$search' OR
					Year LIKE '$search'";
		echo $query;
		paginate($db, $query);
	} else if (isset($_GET["advanced_search"])) {
		$query = "SELECT * FROM Songs WHERE ";
		if(!($_GET["artist_name"]) == ''){
			$term = $_GET["artist_name"];
			$query = $query . "Artist LIKE '%$term%' AND ";
		}
		if(!($_GET["album_name"] == '')){
			echo "Here<br>";
			$term = $_GET["album_name"];
			$query = $query . "Album LIKE '%$term%' AND ";
		}
		if(!($_GET["genre"] == '')){
			$term = $_GET["genre"];
			$query = $query . "Genre LIKE '%$term%' AND ";
		}
		if(!($_GET["year"]) == ''){
			$term = $_GET["year"];
			$query = $query . "Year LIKE '%$term%' AND ";
		}
		if(!($_GET["song_title"] == '')){
			$term = $_GET["song_title"];
			$query = $query . "SongTitle LIKE '%$term%' AND ";
		}
		
		$query = substr($query, 0, -4);
		paginate($db, $query);
	}
		
?>
    <hr>
    <h2>Simple Search</h2>
    <form method='GET' action='' name='simple_search'>
        <input type=text name='simple_search'>
        <input type=submit value='Simple Search' action='search.php'>
    </form>
    <hr>
    <h2>Advanced Search</h2>
    <table border=0><tr><td align=right nowrap>
	<form method='GET' action='' name='advanced_search'>
		<input type=hidden name='advanced_search' value='true'>
		Artist Name <input type=text name='artist_name'><br>
		Album Name <input type=text name='album_name'><br>
		Genre <input type=text name='genre'><br>
		Year <input type=text name='year'><br>
		Song Title <input type=text name='song_title'><br>
		<input type=submit value='Advanced Search' action='search.php'>
	</form>
	</table>
<?php

?>

</body>
</html>