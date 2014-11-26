<?php
	require("headers/menu_header.php");
	require("headers/main_header.php");
	require("headers/function_header.php");
	
    // Find out what is being browsed
    $browse_by = $_GET["by"];

    // Browse by genre
    if($browse_by == "Genre" && is_null($_GET["genre"])) {
        echo "<h3>Genre</h3>";
        $genre_query = "SELECT GenreName FROM Genres";
        $genres = $db->query($genre_query);
        while ($row = $genres->fetchArray()) {
            printf("<a href='browse.php?by=genre&genre=%s'>%s</a><br>", $row["GenreName"], $row["GenreName"]);
        }
        $CRTCcat = [
            "pop, rock, dance" => "Pop, Rock, Dance",
            "country" => "Country",
            "acoustic" => "Acoustic",
            "concert" => "Concert",
            "folk" => "Folk, Folk-Oriented",
            "world beat and international" => "World Beat and International",
            "jazz/blues" => "Jazz/Blues",
            "gospel" => "Gospel",
            "experimental" => "Experimental",
        ];
        echo "<h3>CRTC Categories</h3>";
        foreach($CRTCcat as $get => $cat) {
            printf("<a href='browse.php?by=genre&CRTCcategory=%s'>%s</a><br>", $get, $cat);
        }
    } elseif($browse_by=="genre" && !is_null($_GET["genre"])){
        $genre = $_GET["genre"];
        $query = "SELECT * FROM Songs WHERE Genre LIKE '$genre' GROUP BY Album COLLATE NOCASE"; 
    
    } elseif($browse_by=="genre" && !is_null($_GET["CRTCcategory"])) {
        $cat = $_GET["CRTCcategory"];
        $query = "SELECT * FROM Songs WHERE Custom2 LIKE '$cat' GROUP BY Album COLLATE NOCASE";
 
    } elseif($browse_by=="Artist" || $browse_by=="Album") {
                   
        printf("<h3>");
        $alpha = range('A', 'Z');
    	foreach($alpha as $let) {
	    	printf("<a href='browse.php?by=$browse_by&letter=$let'> $let</a> ");
    	}
    	printf("</h3>");
        	
    	if(isset($_GET['letter'])) {
	    	$letter = $_GET['letter'];
    		$query = "SELECT * FROM Songs WHERE $browse_by LIKE '$letter%' GROUP BY Album COLLATE NOCASE";
    	}
    }
?>

<body>
    <article>
    <?php
		paginate($db, $query);
    ?>
    </article>
</body>
