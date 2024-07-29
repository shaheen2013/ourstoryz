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
$tbl_name = $wpdb->prefix . 'signup_history';
$results = $wpdb->get_results("SELECT * FROM $tbl_name", ARRAY_A);
 

?>

<table class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
        <tr>
            <th><b>ID </b></th>
            <th><b>First Name </b></th>
            <th><b>Event Type</b></th>
            <th><b>Organization Name</b></th>
            <th><b>Brand Logo</b></th>
            <th><b>Event Name</b></th>
            <th><b>Created At</b></th>
            <th><b>Location</b></th>
        </tr>
    </thead>
    <?php

    if ($results) {
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . renderValue("event_type", $row['event_type']) . '</td>';
            echo '<td>' . $row['organization_name'] . '</td>';
            echo '<td>' . $row['brand_logo'] . '</td>';
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