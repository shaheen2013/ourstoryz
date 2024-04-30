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

global $wpdb;
$tbl_name = $wpdb->prefix . 'ourstoryz_users';
$results = $wpdb->get_results("SELECT * FROM $tbl_name", ARRAY_A);

?>

<table class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
        <tr>
            <th><b>ID </b></th>
            <th><b>Service Level </b></th>
            <th><b>User Type</b></th>
            <th><b>Location</b></th>
            <th><b>Event Start</b></th>
            <th><b>Event End</b></th>
            <th><b>Created At</b></th>
            <th><b>Action</b></th>
        </tr>
    </thead>
    <?php

    if ($results) {
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . $row['record_id'] . '</td>';
            echo '<td>' . $row['service_level'] . '</td>';
            echo '<td>' . renderValue("event_type", $row['event_type']) . '</td>';
            echo '<td>' . $row['event_location'] . '</td>';
            echo '<td>' . $row['start_date_time'] . '</td>';
            echo '<td>' . $row['end_date_time'] . '</td>';
            echo '<td>' . $row['created_at'] . '</td>';
            echo '<td><a href="/wp-admin/admin.php?page=ourstoryz_event_details&record_id=' . $row['record_id'] . '">View</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<p>No data found</p>';
    }

    ?>
</table>