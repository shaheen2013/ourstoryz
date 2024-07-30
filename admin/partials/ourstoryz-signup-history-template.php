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

<!-- <table   class="wp-list-table widefat fixed striped table-view-list pages">
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

</table> -->
 
<table class="wp-list-table widefat fixed striped table-view-list tags">
    <caption class="screen-reader-text">Table ordered hierarchically. Ascending.</caption>
    <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox">
                <label for="cb-select-all-1"><span class="screen-reader-text">Select All</span></label>
            </td>
            <th scope="col" id="id" class="manage-column column-id sorted asc" aria-sort="ascending" abbr="ID">
                <a href="#"><span>ID</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span></a>
            </th>
            <th scope="col" id="first-name" class="manage-column column-first-name sortable desc" abbr="First Name">
                <a href="#"><span>First Name</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" id="event-type" class="manage-column column-event-type sortable desc" abbr="Event Type">
                <a href="#"><span>Event Type</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" id="organization-name" class="manage-column column-organization-name sortable desc" abbr="Organization Name">
                <a href="#"><span>Organization Name</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" id="brand-logo" class="manage-column column-brand-logo sortable desc" abbr="Brand Logo">
                <a href="#"><span>Brand Logo</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" id="event-name" class="manage-column column-event-name sortable desc" abbr="Event Name">
                <a href="#"><span>Event Name</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" id="created-at" class="manage-column column-created-at sortable desc" abbr="Created At">
                <a href="#"><span>Created At</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" id="location" class="manage-column column-location sortable desc" abbr="Location">
                <a href="#"><span>Location</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
        </tr>
    </thead>
    <tbody id="the-list" data-wp-lists="list:tag">
        <?php
        if ($results) {
            foreach ($results as $row) {
                echo '<tr>';
                echo '<th scope="row" class="check-column"><input type="checkbox" name="post[]" value="' . $row['id'] . '"></th>';
                echo '<td class="column-primary" data-colname="ID">' . $row['id'] . '</td>';
                echo '<td data-colname="First Name">' . $row['first_name'] . '</td>';
                echo '<td data-colname="Event Type">' . renderValue("event_type", $row['event_type']) . '</td>';
                echo '<td data-colname="Organization Name">' . $row['organization_name'] . '</td>';
                echo '<td data-colname="Brand Logo">' . $row['brand_logo'] . '</td>';
                echo '<td data-colname="Event Name">' . $row['event_name'] . '</td>';
                echo '<td data-colname="Created At">' . $row['created_at'] . '</td>';
                echo '<td data-colname="Location"><button class="view-button" data-record-id="' . $row['record_id'] . '">View</button></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr class="no-items"><td class="colspanchange" colspan="9">No data found</td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox">
                <label for="cb-select-all-2"><span class="screen-reader-text">Select All</span></label>
            </td>
            <th scope="col" class="manage-column column-id sorted asc" aria-sort="ascending" abbr="ID">
                <a href="#"><span>ID</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span></a>
            </th>
            <th scope="col" class="manage-column column-first-name sortable desc" abbr="First Name">
                <a href="#"><span>First Name</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" class="manage-column column-event-type sortable desc" abbr="Event Type">
                <a href="#"><span>Event Type</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" class="manage-column column-organization-name sortable desc" abbr="Organization Name">
                <a href="#"><span>Organization Name</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" class="manage-column column-brand-logo sortable desc" abbr="Brand Logo">
                <a href="#"><span>Brand Logo</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" class="manage-column column-event-name sortable desc" abbr="Event Name">
                <a href="#"><span>Event Name</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" class="manage-column column-created-at sortable desc" abbr="Created At">
                <a href="#"><span>Created At</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
            <th scope="col" class="manage-column column-location sortable desc" abbr="Location">
                <a href="#"><span>Location</span><span class="sorting-indicators">
                    <span class="sorting-indicator asc" aria-hidden="true"></span>
                    <span class="sorting-indicator desc" aria-hidden="true"></span>
                </span><span class="screen-reader-text">Sort ascending.</span></a>
            </th>
        </tr>
    </tfoot>
</table>

 

 