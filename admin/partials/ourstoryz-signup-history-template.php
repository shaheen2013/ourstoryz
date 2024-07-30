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
        // if ($results) {
        //     foreach ($results as $row) {
        //         echo '<tr>';
        //         echo '<td>' . $row['id'] . '</td>';
        //         echo '<td>' . $row['first_name'] . '</td>';
        //         echo '<td>' . renderValue("event_type", $row['event_type']) . '</td>';
        //         echo '<td>' . $row['organization_name'] . '</td>';
        //         echo '<td>' . $row['brand_logo'] . '</td>';
        //         echo '<td>' . $row['event_name'] . '</td>';
        //         echo '<td>' . $row['created_at'] . '</td>';
        //         echo '<td><a href="/wp-admin/admin.php?page=ourstoryz_event_details&record_id=' . $row['record_id'] . '">View</a></td>';
        //         echo '</tr>';
        //     }
        // } 
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
                echo '<td><a href="#" data-id="' . $row['id'] . '" onclick="showPopup(' . $row['id'] . '); return false;">View</a></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="8">No data found</td></tr>';
        }
        ?>
    </tbody>
</table>
<div id="popup" style="display: none;">
    <div id="popup-content">
        <span id="popup-close" onclick="closePopup();">&times;</span>
        <div id="popup-data"></div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#signup_history_table').DataTable();
    });


    function showPopup(recordId) {
        // Show the popup
        document.getElementById('popup').style.display = 'block';

        // Fetch data using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/wp-admin/admin-ajax.php?action=get_event_details&id=' + recordId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                displayDataInPopup(data);
            } else {
                console.error('Failed to fetch data');
            }
        };
        xhr.send();
    }

    function displayDataInPopup(data) {
        var popupData = document.getElementById('popup-data');
        popupData.innerHTML = `
        <p>ID: ${data.id}</p>
        <p>First Name: ${data.first_name}</p>
        <p>Last Name: ${data.last_name}</p>
        <p>Email: ${data.email}</p>
        <p>Profile Image: <img src="${data.profile_image}" alt="Profile Image"></p>
        <p>Storyz Name: ${data.storyz_name}</p>
        <p>Storyz Image: <img src="${data.storyz_image}" alt="Storyz Image"></p>
        <p>Organization Name: ${data.organization_name}</p>
        <p>Tagline: ${data.tagline}</p>
        <p>Brand Logo: <img src="${data.brand_logo}" alt="Brand Logo"></p>
        <p>Event Name: ${data.event_name}</p>
        <p>Event Start Date: ${data.event_start_date}</p>
        <p>Event End Date: ${data.event_end_date}</p>
        <p>Event Image: <img src="${data.event_image}" alt="Event Image"></p>
        <p>Location: ${data.location}</p>
        <p>Event Greeting: ${data.event_greeting}</p>
        <p>Event Type: ${data.event_type}</p>
    `;
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }
</script>
<style>
    /* Popup styling */
    #popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #popup-content {
        margin-top: 100px;
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        width: 50%;
        max-width: 600px;
        text-align: left;
    }

    #popup-close {
        float: right;
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
    }
</style>

<?php

add_action('wp_ajax_get_event_details', 'get_event_details');
add_action('wp_ajax_nopriv_get_event_details', 'get_event_details');

function get_event_details()
{
    global $wpdb;

    $record_id = intval($_GET['id']);
    $table_name = $wpdb->prefix . 'signup_history'; // replace with your actual table name

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $record_id), ARRAY_A);
  

    if ($row) {
        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'No record found'));
    }

    wp_die();
}

?>