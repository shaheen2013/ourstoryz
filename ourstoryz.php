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

function ourstoryz_enqueue_styles()
{
    // Enqueue Bootstrap CSS from CDN
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), '4.5.2');
}
add_action('wp_enqueue_scripts', 'ourstoryz_enqueue_styles');

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
    // Get and sanitize the value of 'event' from the URL query parameters
    $search = filter_input(INPUT_GET, 'event', FILTER_SANITIZE_STRING);

    if ($search === false || $search === null) {
        echo "Invalid input.";
        return;
    }

    // Encode the search query
    $search = urlencode($search);

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
        return;
    }

    // Decode the JSON response
    $data = json_decode($response, true);
    function getCityFromLocation($location) {
        // Split the location string by commas
        $parts = explode(',', $location);
        // Return the second part (the city name) if it exists
        return isset($parts[0]) ? trim($parts[0]) : 'Unknown City';
    }
    // Check if decoding was successful
    if ($data === null) {
        echo "Error decoding JSON response.";
        return;
    }

    // Check if API response contains data
    if (isset($data['data']) && is_array($data['data'])) {
        // Loop through the data and display event names

        foreach ($data['data'] as $event) {
            $coverImage = !empty($event['cover_image']) ? $event['cover_image'] : 'https://img.freepik.com/free-photo/office-worker-using-videocall-conference-meet-with-business-people-webcam-talking-colleagues-remote-videoconference-having-internet-conversation-teleconference-call_482257-50395.jpg?w=740&t=st=1716383152~exp=1716383752~hmac=209ddeafc2a81e5ccf12e00c67eee75704106cbbf1f0eaafb91e589173c1337f';
            $rsvpDeadline = !empty($event['rsvp_deadline']) ? date('M j', strtotime($event['rsvp_deadline'])) : 'No deadline';
            $eventStartDate = new DateTime($event['event_start_date']);
            $eventEndDate = new DateTime($event['event_end_date']);
            if ($eventStartDate->format('Y-m-d') === $eventEndDate->format('Y-m-d')) {
                // Same day event
                $formattedDate = $eventStartDate->format('F j, Y');
            } else {
                // Multi-day event
                $formattedDate = $eventStartDate->format('F j') . '-' . $eventEndDate->format('j, Y');
            }
            $cityName = getCityFromLocation($event['location']['location']);
            // Add a <div> tag with a specific color for each event name
            echo '<div class="container">
                  <div class="row">
                      <div class="col">
                          <div class="card">
                              <div class="row no-gutters">
                                  <div class="col-md-2">
                                      <img src="' . $coverImage . '" class="card-img" alt="Image">
                                  </div>
                                  <div class="col-md-3">
                                      <div class="card-body">
                                          <h5 class="card-title">' . $event['event_name'] . '</h5>
                                          <p class="card-text">' . $event['event_type'] . ' <small>RSVP by ' . $rsvpDeadline . '</small></p>
                                          
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                  <p class="card-text"><small class="text-muted">' . $formattedDate . '</small></p>
                                  </div>
                                  <div class="col-md-3">
                                  <p class="card-text"><small class="text-muted">' . $cityName . '</small></p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>';
        }
    } else {
        echo "No events found.";
    }
}


