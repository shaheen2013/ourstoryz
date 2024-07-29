<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://mediusware.com
 * @since      1.0.0
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/admin/partials
 */

 
 

?>
 <table id="signup_table" class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
        <tr>
            <th><a href="#" class="sortable" data-orderby="id" data-order="asc">ID</a></th>
            <th><a href="#" class="sortable" data-orderby="first_name" data-order="asc">First Name</a></th>
            <th><a href="#" class="sortable" data-orderby="event_type" data-order="asc">Event Type</a></th>
            <th><a href="#" class="sortable" data-orderby="organization_name" data-order="asc">Organization Name</a></th>
            <th><a href="#" class="sortable" data-orderby="brand_logo" data-order="asc">Brand Logo</a></th>
            <th><a href="#" class="sortable" data-orderby="event_name" data-order="asc">Event Name</a></th>
            <th><a href="#" class="sortable" data-orderby="created_at" data-order="asc">Created At</a></th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
        <!-- Rows will be inserted here by AJAX -->
    </tbody>
</table>
<div id="signup_pagination">
    <!-- Pagination will be inserted here by AJAX -->
</div>
<?php 


function fetch_signup_data() {
    check_ajax_referer('ourstoryz_nonce', 'nonce');

    global $wpdb;
    $tbl_name = $wpdb->prefix . 'signup_history';

    $order_by = isset($_POST['order_by']) ? sanitize_text_field($_POST['order_by']) : 'first_name';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'asc';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $tbl_name ORDER BY $order_by $order LIMIT %d OFFSET %d",
        $limit, $offset
    ), ARRAY_A);

    $total_results = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name");

    $table_rows = '';
    foreach ($results as $row) {
        $table_rows .= '<tr>';
        $table_rows .= '<td>' . $row['id'] . '</td>';
        $table_rows .= '<td>' . $row['first_name'] . '</td>';
        $table_rows .= '<td>' . renderValue("event_type", $row['event_type']) . '</td>';
        $table_rows .= '<td>' . $row['organization_name'] . '</td>';
        $table_rows .= '<td>' . $row['brand_logo'] . '</td>';
        $table_rows .= '<td>' . $row['event_name'] . '</td>';
        $table_rows .= '<td>' . $row['created_at'] . '</td>';
        $table_rows .= '<td><a href="/wp-admin/admin.php?page=ourstoryz_event_details&record_id=' . $row['record_id'] . '">View</a></td>';
        $table_rows .= '</tr>';
    }

    $total_pages = ceil($total_results / $limit);
    $pagination = '';
    for ($i = 1; $i <= $total_pages; $i++) {
        $pagination .= '<a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a> ';
    }

    wp_send_json_success(array(
        'table_rows' => $table_rows,
        'pagination' => $pagination
    ));
}
add_action('wp_ajax_fetch_signup_data', 'fetch_signup_data');
add_action('wp_ajax_nopriv_fetch_signup_data', 'fetch_signup_data');

?>