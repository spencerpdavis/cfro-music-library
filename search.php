<body>
<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");	

    $simple_search = $artist_name = $album_name = $genre = $year = $song_title = '';

	if(isset($_GET["simple_search"])) {
        $simple_search = $_GET["simple_search"];
		$search = "%" . $simple_search . "%";
		$query = "SELECT * FROM Songs WHERE 
					Artist LIKE '$search' OR 
					Album LIKE '$search' OR
                    Genre LIKE '$search' OR
					SongTitle LIKE '$search' OR
					Year LIKE '$search'";
	} else if (isset($_GET["advanced_search"])) {
		$query = "SELECT * FROM Songs WHERE ";
		if(!($artist_name = $_GET["artist_name"]) == ''){
			$query = $query . "Artist LIKE '%$artist_name%' AND ";
		}
		if(!($album_name = $_GET["album_name"] == '')){
			$query = $query . "Album LIKE '%$album_name%' AND ";
		}
		if(!($genre = $_GET["genre"] == '')){
			$query = $query . "Genre LIKE '%$genre%' AND ";
		}
		if(!($year = $_GET["year"]) == ''){
			$query = $query . "Year LIKE '%$year%' AND ";
		}
		if(!($song_title = $_GET["song_title"] == '')){
			$query = $query . "SongTitle LIKE '%$song_title%' AND ";
		}
		
		$query = substr($query, 0, -4);
	}
		
?>
    <aside class="sidebar">
        <h2>Simple Search</h2>
        <table border=0><tr><td align=right nowrap>
        <form method='GET' action='' name='simple_search'>
            Search term <input type=text name='simple_search' value='<?echo $simple_search;?>'><br>
            <input type=submit value='Simple Search' action='search.php'>
        </form>
        </table>
        <hr>
        <h2>Advanced Search</h2>
        <table border=0><tr><td align=right nowrap>
        <form method='GET' action='' name='advanced_search'>
            <input type=hidden name='advanced_search' value='true'>
            Artist Name <input type=text name='artist_name' value="<?echo $artist_name;?>"><br>
            Album Name <input type=text name='album_name' value="<?echo $album_name;?>"><br>
            Genre <input type=text name='genre' value="<?echo $genre;?>"><br>
            Year <input type=text name='year' value="<?echo $year;?>"><br>
            Song Title <input type=text name='song_title'><br>
            <input type=submit value='Advanced Search' action='search.php'>
        </form>
        </table>
    </aside>
    <article>
        <?php	paginate($db, $query);?>
    </article>
</body>
</html>
