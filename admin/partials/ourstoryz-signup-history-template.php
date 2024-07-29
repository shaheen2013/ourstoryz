<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://mediusware.com
 * @since      1.0.0
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/admin/partials
 */

global $wpdb;
$tbl_name = $wpdb->prefix . 'signup_history';

// Default sorting
$sort_column = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

$results = $wpdb->get_results("SELECT * FROM $tbl_name ORDER BY $sort_column $sort_order", ARRAY_A);

function sort_link($column, $label) {
    $current_order = (isset($_GET['sort_by']) && $_GET['sort_by'] == $column && $_GET['order'] == 'asc') ? 'desc' : 'asc';
    $url = add_query_arg(array('sort_by' => $column, 'order' => $current_order));
    return '<a href="' . esc_url($url) . '">' . $label . '</a>';
}

?>

<table class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
        <tr>
            <th><b><?php echo sort_link('id', 'ID'); ?></b></th>
            <th><b><?php echo sort_link('first_name', 'First Name'); ?></b></th>
            <th><b><?php echo sort_link('event_type', 'Event Type'); ?></b></th>
            <th><b><?php echo sort_link('organization_name', 'Organization Name'); ?></b></th>
            <th><b>Brand Logo</b></th>
            <th><b><?php echo sort_link('event_name', 'Event Name'); ?></b></th>
            <th><b><?php echo sort_link('created_at', 'Created At'); ?></b></th>
            <th><b><?php echo sort_link('location', 'Location'); ?></b></th>
        </tr>
    </thead>
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
            echo '<td><a href="/wp-admin/admin.php?page=ourstoryz_event_details&record_id=' . esc_html($row['record_id']) . '">View</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="8">No data found</td></tr>';
    }

    ?>
</table>
