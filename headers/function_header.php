<?php
// Open database
require("settings.php");
$db = new SQLite3($database_file);
if(!$db) {
	echo $db->lastErrorMsg();
} 
if (!$db) die ($error);

//Convert delphi time
function convert_delphi($delphiDate){
	$days = intval($delphiDate)-25568;
	$fraction = '' + $delphiDate;
	$fraction = substr($fraction, strpos($fraction, '.'));
	$sec = $fraction * 24 * 60 * 60;
	$date = date('F j, Y', mktime(0, 0, $sec, 1, $days, 1970));
	return $date;
}
// Convert seconds to minutes:seconds

function milliToSeconds($milli) {
	$minutes = round($milli/60000, 0, PHP_ROUND_HALF_DOWN);
	$seconds = round(($milli - ($minutes * 60)) / 1000) % 60;
	$minSecs = $minutes . ':' . $seconds;
	return $minSecs;
}

//Pagination function
function paginate ($db, $query) {

    try {
        // Find how many items are in table
        $results = $db->query($query);
        $total = 0;
		while ($results->fetchArray())
			$total++;
		
		// Find page to determine columns
		$url = parse_url($_SERVER['REQUEST_URI'])["path"];
		$exploded_page = explode('/', $url);
		$page = substr(end($exploded_page), 0, -4);
		echo $page;
		
		// Set columns
		switch ($page) {
			case "recent":
			case "browse":
            case "genre":
				$column_names = array("Artist", "Album", "Genre", "Date Added", "CanCon");
				$columns = array("Artist", "Album", "Genre", "DateAdded", "CanCon");
				break;

			case "Artist":
				$column_names = array("Album", "Genre", "Date Added", "CanCon");
				$columns = array("Album", "Genre", "DateAdded", "CanCon");
				break;

			case "search":
				$column_names = array("Song Title", "Artist", "Album", "Genre", "CanCon");
				$columns = array("SongTitle", "Artist", "Album", "Genre", "CanCon");
				break;

			case "album":
				$column_names = array("Track #", "Artist", "Song Title", "Length", "YouTube", "CanCon");
				$columns = array("TrackNumber", "Artist", "SongTitle", "SongLength", "YouTube", "CanCon");
				break;
		}
		
		// Items per page
        $limit = 20;
		
		// Total pages
        $pages = ceil($total / $limit);

        // Current page
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
        	'option' => array(
            'default' => 1,
            'min_range' => 1,
			),
		)));
		
		// Calculate offset for query
		$offset = ($page - 1) * $limit;

		// Some info for user
		$start = $offset + 1;
		$end = min(($offset + $limit), $total);

		// "back" link
		$prevLink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
		$nextLink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

		// Display paging info
		echo '<div id="paging"><p>', $prevLink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, 'results ', $nextLink, ' </p></div>';

		// Prepare paged query
		$prepared_query = $query . ' LIMIT ' . $limit . ' OFFSET ' . $offset;
		
		$stmt = $db->prepare($prepared_query);

		// Bind the query params
		$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt_result = $stmt->execute();
		$stmt_total = 0;
		while ($stmt_result->fetchArray())
			$stmt_total++;
		
		// Any results?
		if ($stmt_total > 0) {
			// Define how we want to fetch results
			printf("<table align=center class=results>");
			printf("<tr>");
			foreach($column_names as $column) {
				printf("<td>%s</td>", $column);
			}
			// Display the results
			while ($entry = $stmt_result->fetchArray()) {
				printf("<tr>");
				foreach($columns as $column) {
					switch($column) {
						case "DateAdded":
							printf("<td>%s</td>", convert_delphi($entry[$column]));
							break;
						case "SongLength":
							printf("<td>%s</td>", milliToSeconds($entry[$column]));
							break;
						case "Album":
							printf("<td><a href='album.php?album=%s'>%s</td>", $entry['IDAlbum'], $entry[$column]);
							break;
                        case "YouTube":
                            $songTitle = str_replace(' ', '+', $entry['SongTitle']);
                            $artist = str_replace(' ', '+', $entry['Artist']);
                            
                            printf("<td><a href='https://www.youtube.com/results?search_query=%s+%s' target='_blank'>YouTube</a></td>", $songTitle, $artist);
                            break;
                        case "CanCon":
                            if($entry['Custom1']=='Yes' || $entry['Custom1']=='yes'){
                                $CanCon = '&#x2713;';
                            } else {
                                $CanCon = '&#x2717;';
                            }
                            printf("<td>$CanCon</td>");
                            break;
                        case "TrackNumber":
                            printf("<td>%s</td>", $entry[$column]);
                            break;
                        case "Genre":
                            printf("<td><a href='browse.php?by=genre&genre=%s'>%s</a></td>", $entry[$column], $entry[$column]);
                            break;
						case "Artist":
							printf("<td><a href='%s.php?%s=%s'>%s</td>", $column, $column, $entry['Artist'], $entry[$column]);
							break;
                        default:
                            printf("<td>%s</td>", $entry[$column]);
					}
				}
				printf("</tr>");
			}
			
		} else {
			echo '<p>No results could be displayed.</p>';
			}
    } catch (Exception $e) {
        echo '<p>', $e->getMessage(), '</p>';
    }
}
