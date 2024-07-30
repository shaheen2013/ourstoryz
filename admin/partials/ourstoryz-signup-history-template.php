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
        echo '<td><button class="view-button" data-record-id="' . $row['record_id'] . '">View</button></td>';
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

function get_signup_details() {
    global $wpdb;
    $record_id = intval($_POST['record_id']);
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

<div id="signupHistoryModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modal-body">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#signup_history_table').DataTable();
    });


    jQuery(document).ready(function($) {
        const modal = document.getElementById("signupHistoryModal");
        const span = document.getElementsByClassName("close")[0];

        $('.view-button').on('click', function() {
            const recordId = $(this).data('record-id');
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'get_signup_details',
                    record_id: recordId
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    $('#modal-body').html(`
                    <p>ID: ${data.id}</p>
                    <p>First Name: ${data.first_name}</p>
                    <p>Event Type: ${data.event_type}</p>
                    <p>Organization Name: ${data.organization_name}</p>
                    <p>Brand Logo: ${data.brand_logo}</p>
                    <p>Event Name: ${data.event_name}</p>
                    <p>Created At: ${data.created_at}</p>
                    <p>Location: ${data.location}</p>
                `);
                    modal.style.display = "block";
                }
            });
        });

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });
</script>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
