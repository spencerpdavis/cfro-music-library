<?php

//Pagination function
function paginate ($db) {
    try {
        // Find how many items are in table
        $total = $db->query('
        ')->fetchColumn();
        
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
    $end = min(($offset + $limit), $total)

    // "back" link
    $prevLink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
    $nextLink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

    // Display paging info
    echo '<div id="paging"><p>', $prevLink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, 'results ', $nextLink, ' </p></div>';

    // Prepare paged query
    $stmt = $db->prepare('
        SELECT
            *
        FROM
            table
        ORDER BY
            name
        LIMIT
            :limit
        OFFSET
            :offset
    ');

    // Bind the query params
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INIT);
    $STMT->execute();

    // Any results?
    if ($stmt->rowCount() > 0) {
        // Define how we want to fetch results
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $iterator = new IteratorIterator($stmt);

        // Display the results
        foreach ($iterator as $row) {
            echo '<p>', $row['name'], '</p>';
        }
    } else {
        echo '<p>No results could be displayed.</p>';
        }
    } catch (Exception $e) {
        echo '<p>', $e->getMessage(), '</p>';
    }
}
