<?php

/**
 * Display Signup History with Pagination and Sorting
 */
function render_signup_history() {
    global $wpdb;

    // Define default sorting and pagination values
    $page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $orderby = isset($_GET['orderby']) ? sanitize_key($_GET['orderby']) : 'id';
    $order = isset($_GET['order']) ? strtoupper(sanitize_key($_GET['order'])) : 'ASC';
    $per_page = 10; // Number of items per page

    // Validate order
    $order = in_array($order, ['ASC', 'DESC']) ? $order : 'ASC';

    // Get total items
    $table_name = $wpdb->prefix . 'signup_history';
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    // Calculate the offset
    $offset = ($page - 1) * $per_page;

    // Fetch data with sorting and pagination
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d",
        $per_page,
        $offset
    ), ARRAY_A);

    // Generate pagination links
    $total_pages = ceil($total_items / $per_page);
    $pagination = paginate_links([
        'total' => $total_pages,
        'current' => $page,
        'format' => '?paged=%#%&orderby=' . urlencode($orderby) . '&order=' . urlencode($order),
    ]);

    ?>
    <div class="wrap">
        <h2>Signup History</h2>
        <table class="wp-list-table widefat fixed striped table-view-list pages">
            <thead>
                <tr>
                    <th><a href="?orderby=id&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">ID</a></th>
                    <th><a href="?orderby=first_name&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">First Name</a></th>
                    <th><a href="?orderby=event_type&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Event Type</a></th>
                    <th><a href="?orderby=organization_name&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Organization Name</a></th>
                    <th><a href="?orderby=brand_logo&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Brand Logo</a></th>
                    <th><a href="?orderby=event_name&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Event Name</a></th>
                    <th><a href="?orderby=created_at&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Created At</a></th>
                    <th><a href="?orderby=location&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Location</a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($results) {
                    foreach ($results as $row) {
                        echo '<tr>';
                        echo '<td>' . esc_html($row['id']) . '</td>';
                        echo '<td>' . esc_html($row['first_name']) . '</td>';
                        echo '<td>' . esc_html($row['event_type']) . '</td>';
                        echo '<td>' . esc_html($row['organization_name']) . '</td>';
                        echo '<td>' . esc_html($row['brand_logo']) . '</td>';
                        echo '<td>' . esc_html($row['event_name']) . '</td>';
                        echo '<td>' . esc_html($row['created_at']) . '</td>';
                        echo '<td>' . esc_html($row['location']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8">No data found</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <div class="tablenav">
            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
    <?php
}
