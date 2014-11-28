<?php
	include("headers/main_header.php");
	include("headers/menu_header.php");
	include("headers/function_header.php");
?>
    <?
    // Find out what is being browsed
    try{
        $get_by = $_GET["by"];
        if(is_null($get_by)){$get_by = "Artist";}
    } catch (Exception $e) {
        $get_by = "Artist";
    }
    try{
        $get_letter = $_GET["letter"];
        if($get_letter == "All"){$get_letter='';}
    } catch (Exception $e) {
        $get_letter = '';
    }

   try{
        $get_genre = $_GET["genre"];
    } catch (Exception $e) {
        $get_genre = '';
    }

    try{
        $get_cat = $_GET["CRTCcategory"];
    } catch (Exception $e) {
        $get_cat = '';
    }

    // Get genres and categories
    $genre_query = "SELECT * FROM Genres";
    $genres = $db->query($genre_query);
    $CRTCcat = [
        "all" => "All",
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
    
    // Variable for all letters
    $alpha = range('A', 'Z');
    array_unshift($alpha, "All");

    // ***** Create query. ***** 
    $query = "SELECT * FROM Songs";

    //People can browse by letter and either Genre OR CRTC Catagories, not both. 
    if($get_letter != '')
    {
        $query = $query . " WHERE $get_by LIKE '$get_letter%'";
        if($get_genre != ''){
            if($get_genre != "all"){
                $query = $query . " AND Genre LIKE '$get_genre'";
            }
        } elseif($get_cat != ''){
            if($get_cat != "all") {
                $query = $query . " AND Custom2 LIKE '$get_cat'";
            }
        }
    } else {
        if($get_genre != ''){
            if($get_genre != "all") {
                $query = $query . " WHERE Genre LIKE '$get_genre'";
            }
        } elseif($get_cat != ''){
            if ($get_cat != "all") {
                $query = $query . " WHERE Custom2 LIKE '$get_cat'";
            }
        }
    }

    // People can browse by either Artist or Album
    $query = $query . " GROUP BY $get_by COLLATE NOCASE";
    ?>

    <aside class="letterbar">
        <ul class="letters">
        <?foreach($alpha as $let) {
	    	printf("<li><a href='browse.php?by=$get_by&letter=$let&genre=$get_genre&CRTCcategory=$get_cat'> $let</a></li> ");
    	}?>
    </aside>
    <aside class="sidebar"></span>
        <ul class="side-collapsible">
        <li class="side-header">Browse by</a>
        <li class="side-dropdown"><a class="" href="#">Artist/Album<span class="caret"></span></a>
            <ul>
                <?printf("<li><a href='browse.php?by=Artist&letter=$get_letter&genre=$get_genre&CRTCcategory=$get_cat'>Browse by Artist</a></li>");
                printf("<li><a href='browse.php?by=Album&letter=$get_letter&genre=$get_genre&CRTCcategory=$get_cat'>Browse by Album</a></li>");
                ?>
            </ul>
        </li>
        <li class="side-dropdown"><a class="" href="#">Genres<span class="caret"></span></a>
                <ul>
                <?php
                printf("<li><a href='browse.php?by=$get_by&letter=$get_letter&genre=all&CRTCcategory='>All</a></li>");
                while ($row = $genres->fetchArray()) {
                    if($row["GenreName"] == $get_genre){
                        printf("<li class='selected_genre'><a href='browse.php?by=$get_by&letter=$get_letter&genre=%s&CRTCcategory='>%s</a></li>", $row["GenreName"], $row["GenreName"]);
                    }else{printf("<li><a href='browse.php?by=$get_by&letter=$get_letter&genre=%s&CRTCcategory='>%s</a></li>", $row["GenreName"], $row["GenreName"]);
                    }
                }

                ?>
                </ul>
            </li>
        <li class="side-dropdown"><a href="#">CRTC Categories</a>
            <ul>
            <?php
            foreach($CRTCcat as $get => $cat) {
            printf("<li><a href='browse.php?by=$get_by&letter=$get_letter&genre=&CRTCcategory=%s'>%s</a></li>", $get, $cat);
            }?>
            </ul>
        </li>
        </ul>

    </aside>
    <article>
    <h3>Browsing by <?echo $get_by?></h3>
    <?php
	      	paginate($db, $query);
    ?>
    </article>
</body>
</html>
