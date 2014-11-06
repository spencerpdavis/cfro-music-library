<?php

//Pagination function
function paginate ($db, $query) {
    try {
        // Find how many items are in table
        $results = $db->query($query);
        $total = 0;
		
		while ($results->fetchArray())
			$total++;
		
		echo $total;
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
		echo $page;
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
		echo $prepared_query;
		$stmt = $db->prepare($prepared_query);

		// Bind the query params
		$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt_result = $stmt->execute();
		$stmt_total = 0;
		while ($stmt_result->fetchArray())
			$stmt_total++;
		echo $stmt_total;
		// Any results?
		if ($stmt_total > 0) {
			// Define how we want to fetch results
			echo "Results: " . $stmt_total;
			var_dump($stmt_result);
			echo "Limit: " . $limit . "\nOffset: " . $offset . "\n";
			
			// Display the results
			while ($entry = $stmt_result->fetchArray()) {
				echo 'Artist: ' . $entry['AlbumArtist'];
			}
			
		} else {
			echo '<p>No results could be displayed.</p>';
			}
    } catch (Exception $e) {
        echo '<p>', $e->getMessage(), '</p>';
    }
}
