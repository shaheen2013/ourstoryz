<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mediusware.com
 * @since             1.0.0
 * @package           ourstoryz
 *
 * @wordpress-plugin
 * Plugin Name:       Ourstoryz
 * Plugin URI:        https://mediusware.com
 * Description:       Custom Description
 * Version:           1.0.0
 * Author:            Mediusware
 * Author URI:        https://mediusware.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ourstoryz
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('OURSTORYZ_VERSION', '1.0.0');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ourstoryz-activator.php
 */
function activate_ourstoryz()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-ourstoryz-activator.php';
	ourstoryz_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ourstoryz-deactivator.php
 */
function deactivate_ourstoryz()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-ourstoryz-deactivator.php';
	ourstoryz_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ourstoryz');
register_deactivation_hook(__FILE__, 'deactivate_ourstoryz');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-ourstoryz.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ourstoryz()
{

	$plugin = new ourstoryz();
	$plugin->run();
}
run_ourstoryz();


add_shortcode('search_result', 'search_result_show');

function search_result_show()
{
	// Get the value of 'event' from the URL query parameters
	$search = filter_input(INPUT_GET, 'event', FILTER_SANITIZE_STRING);

	if ($search === false || $search === null) {
		echo "Invalid input.";
		return;
	}

	$search = urlencode($search); // Encode the search query

	// Construct the API endpoint URL with the search key
	$url = "https://api.dev.ourstoryz.com/api/templates/event/list?searchKey=$search";

	// Fetch data from the API
	$response = file_get_contents($url);

	// Check for errors
	if ($response === false) {
		// Get detailed error message
		$error = error_get_last();
		if ($error !== null) {
			echo "Error fetching data from API: " . $error['message'];
		} else {
			echo "Failed to fetch data from API.";
		}
	} else {
		// Display the response data
		echo $response;
	}
}

