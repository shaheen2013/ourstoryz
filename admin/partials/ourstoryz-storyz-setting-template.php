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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5 w-25 m-auto">
    <h3 class="text-center my-5">Url Find and replace</h3>
    <form method="post" action="">
        <div class="mb-3">
            <label for="source_url" class="form-label">Source URL:</label>
            <input type="text" class="form-control" name="source_url" id="source_url" required>
        </div>
        <div class="mb-3">
            <label for="target_url" class="form-label">Target URL:</label>
            <input type="text" class="form-control" name="target_url" id="target_url" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary  m-auto">Update URLs</button>
    </form>
</div>

</body>





<?php
if (isset($_POST['submit'])) {
    $source_url = esc_url($_POST['source_url']);
    $target_url = esc_url($_POST['target_url']);

    global $wpdb;

    // Update wp_options
    $wpdb->query($wpdb->prepare("
        UPDATE {$wpdb->options}
        SET option_value = REPLACE(option_value, %s, %s)
        WHERE option_name = 'home' OR option_name = 'siteurl';
    ", $source_url, $target_url));

    // Update wp_posts
    $wpdb->query($wpdb->prepare("
        UPDATE {$wpdb->posts}
        SET guid = REPLACE(guid, %s, %s);
    ", $source_url, $target_url));

    $wpdb->query($wpdb->prepare("
        UPDATE {$wpdb->posts}
        SET post_content = REPLACE(post_content, %s, %s);
    ", $source_url, $target_url));

    // Update wp_postmeta
    $wpdb->query($wpdb->prepare("
        UPDATE {$wpdb->postmeta}
        SET meta_value = REPLACE(meta_value, %s, %s);
    ", $source_url, $target_url));

    echo 'URLs updated successfully!';
}


?>


 
 