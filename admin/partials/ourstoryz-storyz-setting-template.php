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





<div class="wrap">
    <h2>Our Storyz Setting</h2>
    <h2 class="nav-tab-wrapper">
        <a href="#tab1" class="nav-tab ourstoryz-tab-link active">Find & Replace</a>
        <a href="#tab2" class="nav-tab ourstoryz-tab-link">Auth Token</a>
        <a href="#tab3" class="nav-tab ourstoryz-tab-link">Default Template select</a>
        <a href="#tab4" class="nav-tab ourstoryz-tab-link">Google Maps API Key</a>
    </h2>

    <div id="tab1" class="ourstoryz-tab-content" style="display:block;">
        <!-- Content for Tab 1 -->

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
    </div>

    <div id="tab2" class="ourstoryz-tab-content" style="display:none;">
        <!-- Content for Tab 2 -->
        <p>Click the button below to generate JWT token for logged-in user:</p>
        <button id="generateTokenButton" class="button-primary d-block mt-3">Generate Auth Token</button>
        <div id="auth-token-display-container" style="position: relative; display: inline-block;">
            <input type="text" id="auth-token-display" readonly style="padding-right: 30px;">
            <span id="copy-icon" style="position: absolute; right: 5px; top: 5px; cursor: pointer;">
                &#x1F4CB; <!-- Clipboard emoji, you can use any icon here -->
            </span>
            <div id="error-message" style="display: none; color: red;">Incorrect password</div>
        </div>
    </div>
    <div id="tab3" class="ourstoryz-tab-content" style="display:none;">
        <!-- Content for Tab 1 -->

        <div class="container mt-5 w-25 m-auto">
            <div class="container">
                <label for="options" class="form-label">Select a number:</label>
                <select id="options" class="form-select">
                    <option value="6946">Avalone</option>
                    <option value="7250">Basic</option>

                </select>
                <button id="submitBtn" class="btn btn-primary mt-3">Submit</button>
                <div id="result" class="mt-3"></div>
            </div>
        </div>
    </div>
    <div id="tab4" class="ourstoryz-tab-content w-25 m-auto" style="display:none;">
        <h3>Google Maps API Key</h3>

        <form id="google-maps-api-key-form">
            <div class="mb-3">
                <label for="google_maps_api_key" class="form-label">Google Maps API Key:</label>
                <input type="text" class="form-control  " name="google_maps_api_key" id="google_maps_api_key" value="<?php echo esc_attr($stored_api_key); ?>" required>
                <div id="error-message" style="color: red; margin-top: 5px;"></div>
            </div>
            <button type="submit" class="btn btn-primary">Save API Key</button>
        </form>
        <div id="success-message" style="color: green; margin-top: 10px; display: none;"></div>
    </div>
</div>




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