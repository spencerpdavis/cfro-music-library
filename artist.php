
<?php
    require("headers/menu_header.php");
    require("headers/main_header.php");
    require("headers/function_header.php");
        
    $artist = $_GET["Artist"];
                    
    $artist_query = "SELECT * FROM Songs WHERE Artist = '$artist' COLLATE NOCASE GROUP BY Album COLLATE NOCASE";
?>
<body>
    <h2><?$artist?></h2>
    <article>
    <?php
        paginate($db, $artist_query);
    ?>
    </article>
</body>
