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

<table id="signup_history_table" class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
        <tr>
            <th><b>ID</b></th>
            <th><b>First Name</b></th>
            <th><b>Event Type</b></th>
            <th><b>Organization Name</b></th>
            <th><b>Brand Logo</b></th>
            <th><b>Event Name</b></th>
            <th><b>Created At</b></th>
            <th><b>Location</b></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($results) {
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['first_name'] . '</td>';
                echo '<td>' . renderValue("event_type", $row['event_type']) . '</td>';
                echo '<td>' . $row['organization_name'] . '</td>';
                echo '<td>' . $row['brand_logo'] . '</td>';
                echo '<td>' . $row['event_name'] . '</td>';
                echo '<td>' . $row['created_at'] . '</td>';
                echo '<td><button class="view-button" data-record-id="' . $row['id'] . '">View</button></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="8">No data found</td></tr>';
        }
        ?>
    </tbody>
</table>

<?php
add_action('wp_ajax_get_signup_details', 'get_signup_details');
add_action('wp_ajax_nopriv_get_signup_details', 'get_signup_details');

function get_signup_details()
{
    global $wpdb;
    $record_id = intval($_GET['record_id']);
    $table_name = $wpdb->prefix . 'signup_history';
    $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $record_id), ARRAY_A);

    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
    wp_die();
}
?>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#signup_history_table').DataTable();

        $(document).on('click', '.view-button', function() {
            var recordId = $(this).data('record-id');
            $.ajax({
                url: ajaxurl,
                type: 'GET',
                data: {
                    action: 'get_signup_details',
                    record_id: recordId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data && Object.keys(data).length) {
                        // Handle displaying the details
                        console.log(data);
                    } else {
                        alert('No data found for this record.');
                    }
                }
            });
        });
    });
</script>
