<?php
	include("headers/main_header.php");
	include("headers/menu_header.php");
	include("headers/function_header.php");
?>
    <?
    // Find out if being browsed by artist/album
    if(isset($_GET['by'])){
        $get_by = $_GET['by'];
        if(is_null($get_by)){$get_by = "Artist";}
    } else{
        $get_by = "Artist";
    }

    // Array to build LIKE portion of query
    $like_array = array();
    if(isset($_GET['letter'])){
        $like_array[$get_by] = $_GET['letter'];
        if($like_array[$get_by] == "All" || $like_array[$get_by] == ''){
            unset($like_array[$get_by]);}
    } 

    // Find out if being browsed by genre
    if(isset($_GET['genre']) && $_GET['genre'] !== ''){
        $like_array['Genre'] = $_GET['genre'];
        if($like_array['Genre'] == "all"){unset($like_array['Genre']);}
    } 

    if(isset($_GET['CRTCcategory']) && $_GET['CRTCcategory'] !== ''){
        $like_array['Custom2'] = $_GET['CRTCcategory'];
    } 

    if(isset($_GET['cancon'])){
        $like_array['Custom1'] = $_GET['cancon'];
        if(!($get_cancon == 'yes' || $get_cancon =='Yes')){
            $like_array['Custom1'] = '';
        }
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
    if(!empty($like_array)){
        $query = $query . " WHERE ";
        var_dump($like_array);
        echo "end " .  end($like_array) . "<br>";
        foreach($like_array as $column => $value){
            if($value !== ''){
                if(end($like_array) !== $value){
                 if($column == "Artist" || $column == "Album"){
                     $query = $query . " $column LIKE '$value%' AND";
                 } else {
                     $query = $query . " $column LIKE '$value' AND";
                 }
              }
             else{
                  if($column == "Artist" || $column == "Album"){
                     $query = $query . " $column LIKE '$value%'";
                 } else {
                     $query = $query . " $column LIKE '$value'";
                 }
             }
            }
        }
    }

    echo $query;


    //People can browse by letter and either Genre OR CRTC Catagories, not both. 
/*    if($get_letter != '')
    {
        $query = $query . " WHERE $get_by LIKE '$get_letter%'";
        if($get_genre != ''){
            if($get_genre != "all"){
                $query = $query . " AND Genre LIKE '$get_genre' AND Custom1 LIKE '$get_cancon'";
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
*/
    // People can browse by either Artist or Album
    $query = $query . " GROUP BY $get_by COLLATE NOCASE";
    ?>

    <aside class="letterbar">
        <ul class="letters">
        <li>
        <ul>
        <?foreach($alpha as $let) {
            if(null !== isset($like_array['letter']) && $like_array['letter'] == $let){
	    	printf("<li class='selected'><a href='browse.php?by=$get_by&letter=$let&genre=$get_genre&CRTCcategory=$get_cat'> $let</a></li> ");
            } else { 
printf("<li><a href='browse.php?by=$get_by&letter=$let&genre=$get_genre&CRTCcategory=$get_cat'> $let</a></li> ");
    	}}?>
        </ul>
        </li>
        </ul>
    </aside>
    <aside class="sidebar"></span>
        <ul class="side-collapsible">
        <li class="side-header">Browse by</a>
        <li class="side-dropdown"><a class="" href="#">Artist/Album<span class="caret"></span></a>
            <ul>
                <li <?if($get_by == "Artist"){echo "class=selected";}?>><a href='browse.php?by=Artist&letter=<?echo $like_array['letter'];?>&genre=<?echo $get_genre?>&CRTCcategory=<?echo $get_cat?>'>Browse by Artist</a></li>
                <li <?if($get_by == "Album"){echo "class=selected";}?>><a href='browse.php?by=Album&letter=<?echo $like_array['letter'];?>&genre=<?echo $get_genre?>&CRTCcategory=<?echo $get_cat?>'>Browse by Album</a></li>
            </ul>
        </li>
        <li class="side-dropdown"><a class="" href="#">Genres<span class="caret"></span></a>
                <ul>
                <li <?if($get_genre=="all"){echo "class=selected";}?>><a href='browse.php?by=<?echo $get_by?>&letter=<?echo $like_array['letter']?>&genre=all&CRTCcategory='>All</a></li>
                <?
                while ($row = $genres->fetchArray()) {
                    if($row["GenreName"] == $get_genre){
                        printf("<li class='selected'><a href='browse.php?by=$get_by&letter=%s&genre=%s&CRTCcategory='>%s</a></li>", $like_array["letter"], $row["GenreName"], $row["GenreName"]);
                    }else{printf("<li><a href='browse.php?by=$get_by&letter=%s&genre=%s&CRTCcategory='>%s</a></li>", $like_array["letter"], $row["GenreName"], $row["GenreName"]);
                    }
                }

                ?>
                </ul>
            </li>
        <li class="side-dropdown"><a href="#">CRTC Categories</a>
            <ul>
            <?php
            foreach($CRTCcat as $get => $cat) {
                if($get == $get_cat) {
                    printf("<li class='selected'><a href='browse.php?by=$get_by&letter=%s&genre=&CRTCcategory=%s'>%s</a></li>", $like_array["letter"], $get, $cat);
                }
                else{
                    printf("<li><a href='browse.php?by=$get_by&letter=%s&genre=&CRTCcategory=%s'>%s</a></li>", $like_array["letter"], $get, $cat);
                }
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
