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
<form method="post" action="">
    <label for="source_url">Source URL:</label>
    <input type="text" name="source_url" id="source_url" required><br>
    <label for="target_url">Target URL:</label>
    <input type="text" name="target_url" id="target_url" required><br>
    <input type="submit" name="submit" value="Update URLs">
</form>
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

 