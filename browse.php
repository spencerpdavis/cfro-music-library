<?php
	include("headers/main_header.php");
	include("headers/menu_header.php");
	include("headers/function_header.php");
?>
    <?
    // Find out if being browsed by artist/album. Default is by Artist

    $get_by = SQLite3::escapeString($_GET['by']);
    if(is_null($get_by)){$get_by = "Artist";
    } else{
        $get_by = "Artist";
    }

    // Array to build LIKE portion of query
    $like_array = array();

    $like_array[$get_by] = SQLite3::escapeString($_GET['letter']);
    if($like_array[$get_by] == "All" || $like_array[$get_by] == ''){
            unset($like_array[$get_by]);
    } 
    if($like_array[$get_by] == "0-9"){
        $like_array[$get_by] = '[0-9]';
    }

    // Find out if being browsed by genre
    $like_array['Genre'] = SQLite3::escapeString($_GET['genre']);
    if($like_array['Genre'] == "all" || empty($like_array['Genre'])){unset($like_array['Genre']);}
     

    $like_array['Custom2'] = SQLite3::escapeString($_GET['CRTCcategory']);
    if($like_array['Custom2'] == "all" || empty($like_array['Custom2'])){unset($like_array['Custom2']);}
    
    $like_array['Custom1'] = SQLite3::escapeString($_GET['cancon']);
    if(!($like_array['Custom1'] == 'yes')){unset($like_array['Custom1']);}

    // Get genres and categories for sidepane
    $genre_query = "SELECT * FROM Genres";
    $genres = $db->query($genre_query);

   
    // Variable for all letters
    $alpha = range('A', 'Z');
    array_unshift($alpha, "All", "0-9");

    // ***** Create query. ***** 
    $query = "SELECT * FROM Songs";
    if(!empty($like_array)){
        $query = $query . " WHERE ";
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

    // People can browse by either Artist or Album
    $query = $query . " GROUP BY $get_by COLLATE NOCASE";
    ?>

    <aside class="letterbar">
        <ul class="letters">
        <li>
        <ul>
        <?
        foreach($alpha as $let) {
            list_selected($like_array[$get_by], $let);
            printf("<a href='browse.php?by=$get_by&letter=$let&genre=%s&CRTCcategory=%s&cancon=%s'> $let</a></li> ", $like_array['Genre'], $like_array['Custom2'], $like_array['Custom1']);
    	}?>
        </ul>
        </li>
        </ul>
    </aside>
    <aside class="sidebar"></span>
        <ul class="side-collapsible">
        <li class="side-header">Browse by</a>
        <li class="side-dropdown"><a class="" href="#">Artist/Album<span class="caret"></span></a>
            <ul>
                <?list_selected($get_by, "Artist")?><a href='browse.php?by=Artist&letter=<?echo $like_array['letter'];?>&genre=<?echo $like_array['Genre']?>&CRTCcategory=<?echo $like_array['Custom2']?>'>Browse by Artist</a></li>
                <?list_selected($get_by, "Album")?><a href='browse.php?by=Album&letter=<?echo $like_array['letter'];?>&genre=<?echo $like_array['Genre']?>&CRTCcategory=<?echo $like_array['Custom2']?>'>Browse by Album</a></li>
            </ul>
        </li>
        <li class="side-dropdown"><a class="" href="#">Canadian Content<span class="caret"></span></a>
            <ul>
                <?
                list_selected($like_array["Custom1"], '');
                printf("<a href='browse.php?by=$get_by&letter=%s&genre=%s&cancon=&CRTCcategory=%s'>All</a></li>", $like_array["letter"], $row["GenreName"], $row["GenreName"], $like_array["Custom2"]);
                list_selected($like_array["Custom1"], 'yes');
                printf("<a href='browse.php?by=$get_by&letter=%s&genre=%s&cancon=yes&CRTCcategory=%s'>Canadian Content Only</a></li>", $like_array["letter"], $row["GenreName"], $row["GenreName"], $like_array["Custom2"]);
            ?>
            </ul>
        <li class="side-dropdown"><a class="" href="#">Genres<span class="caret"></span></a>
                <ul>
                <li <?if($like_array['Genre']=="all"){echo "class=selected";}?>><a href='browse.php?by=<?echo $get_by?>&letter=<?echo $like_array['letter']?>&genre=all&CRTCcategory='>All</a></li>
                <?
                while ($row = $genres->fetchArray()) {
                    list_selected($row["GenreName"], $like_array["Genre"]);
                    printf("<a href='browse.php?by=$get_by&letter=%s&genre=%s&cancon=%s&CRTCcategory='>%s</a></li>", $like_array["letter"], $row["GenreName"], $like_array["Custom1"], $row["GenreName"]);
               }?>
                </ul>
            </li>
        <li class="side-dropdown"><a href="#">CRTC Categories</a>
            <ul>
            <?php
            foreach($CRTCcat as $get => $cat) {
                list_selected($get, $like_array["Custom2"]);
                printf("<a href='browse.php?by=$get_by&letter=%s&genre=&CRTCcategory=%s'>%s</a></li>", $like_array["letter"], $get, $cat);
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
