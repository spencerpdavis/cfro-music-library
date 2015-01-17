<body>
<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");	

    $simple_search = SQLite3::escapeString($_GET['simple_search']);
    $advanced_search = SQLite3::escapeString($_GET['advanced_search']);
    $artist_name = SQLite3::escapeString($_GET['artist_name']);
    $album_name =SQLite3::escapeString($_GET['album_name']);
    $genre = SQLite3::escapeString($_GET["genre"]);
    $year = SQLite3::escapeString($_GET["year"]);
	$song_title = SQLite3::escapeString($_GET["song_title"]);
    $cancon = SQLite3::escapeString($_GET['cancon']);
    $crtc = SQLite3::escapeString($_GET['crtc']);
    
	if(!empty($simple_search)) {
		$search = "%" . $simple_search . "%";
		$query = "SELECT * FROM Songs WHERE 
					Artist LIKE '$search' OR 
					Album LIKE '$search' OR
                    Genre LIKE '$search' OR
					SongTitle LIKE '$search' OR
					Year LIKE '$search'";
	} else if (!empty($advanced_search)) {
		$query = "SELECT * FROM Songs WHERE ";
		if(!empty($artist_name)){
			$query = $query . "Artist LIKE '%$artist_name%' AND ";
		}
		if(!empty($album_name)){
			$query = $query . "Album LIKE '%$album_name%' AND ";
		}
		if(!empty($genre)){
			$query = $query . "Genre LIKE '%$genre%' AND ";
		}
		if(!empty($year)){
			$query = $query . "Year LIKE '%$year%' AND ";
		}
		if(!empty($song_title)){
			$query = $query . "SongTitle LIKE '%$song_title%' AND ";
		}
        if(!empty($cancon)){
            $query = $query . "Custom1 LIKE '$cancon' AND ";
		}
        if(!empty($crtc)){
            $query = $query . "Custom2 LIKE '$crtc' AND ";
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
            CRTC Category <select name="crtc">
                <?foreach($CRTCcat as $get => $cat) {
                    echo "<option value='$get'>$cat</option>";
                }?></select><br>
            Canadian Content Only <input type="checkbox" name="cancon" value="yes"><br>
            <input type=submit value='Advanced Search' action='search.php'>
        </form>
        </table>
    </aside>
    <article>
        <?php	if(isset($query)){paginate($db, $query);}?>
    </article>
</body>
</html>
