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

  if ($search === false || $search === '') {
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

  function getCityFromLocation($location)
  {
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
    // Check if there are no events in the data array
    if (empty($data['data'])) {
      echo "No data found.";
      return;
    }

    // Loop through the data and display event names
    foreach ($data['data'] as $event) {
      $coverImage = !empty($event['cover_image']) ? $event['cover_image'] : 'https://img.freepik.com/free-photo/office-worker-using-videocall-conference-meet-with-business-people-webcam-talking-colleagues-remote-videoconference-having-internet-conversation-teleconference-call_482257-50395.jpg?w=740&t=st=1716383152~exp=1716383752~hmac=209ddeafc2a81e5ccf12e00c67eee75704106cbbf1f0eaafb91e589173c1337f';
      $rsvpDeadline = !empty($event['rsvp_deadline']) ? date('M j', strtotime($event['rsvp_deadline'])) : ' ';
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

      echo '<div class="container mt-5">
          <div class="card p-3 event-link" data-event-id="' . $event['id'] . '" style="cursor:pointer">
            <div class="row g-0">
              <div class="col-md-2">
                <img
                src="' . htmlspecialchars($coverImage) . '"
                  class="img-fluid rounded-start card-img"
                  alt="Event Image"
                />
              </div>
              <div class="col-md-10">
                <div
                  class="card-body d-flex align-items-center justify-content-between"
                >
                  <div>
                    <h5 class="card-title"> ' . htmlspecialchars($event['event_name']) . ' </h5>
                    <p class="card-text text-muted">
                    ' . htmlspecialchars($event['event_type']) . (!empty($rsvpDeadline) ? ' &bull; RSVP by ' . htmlspecialchars($rsvpDeadline) : '') . '
                    </p>
                  </div>
                  <div>
                    <p class="date-text">' . htmlspecialchars($formattedDate) . '</p>
                  </div>
                  <div>
                    <p class="link-text">
                    ' . htmlspecialchars($cityName) . ' <small class="text-muted arrow">&rarr;</small>
                    </p>
                    <div></div>
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



// Enqueue your script// Enqueue your script
function enqueue_custom_script()
{
  wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/custom-script.js', array('jquery'), '1.0', true);

  // Localize script with AJAX URL and nonce
  wp_localize_script('custom-script', 'ajax_object', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'ajax_nonce' => wp_create_nonce('fetch_mini_website_template_nonce')
  )
  );
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');

// AJAX handler function
function fetch_mini_website_template()
{
  // Check if the request is coming from a valid source
  check_ajax_referer('fetch_mini_website_template_nonce', 'security');

  // Get the event ID from the AJAX request
  $event_id = $_POST['event_id'];




  // Make the API call
  $response = wp_remote_get("https://api.dev.ourstoryz.com/api/templates/event/single?event_id=" . $event_id);

  if (is_wp_error($response)) {
    wp_send_json_error('Error occurred while fetching data from API.');
  }

  $body = wp_remote_retrieve_body($response);
  $data = json_decode($body);


  if (!$data) {
    wp_send_json_error('Invalid API response.');
  }

  $miniWebsiteTemplateId = $data->data->mini_website_template_id;
  if (!$data || !isset($data->data->mini_website_template_id)) {
    // Set default ID if API response is invalid or mini_website_template_id is not set
    $updated_value = get_option('default_template');

    $miniWebsiteTemplateId =  $updated_value; // Replace 'default_id' with your actual default ID
  } else {
    $miniWebsiteTemplateId = $data->data->mini_website_template_id;
  }

  // Return the mini website template ID
  wp_send_json_success($miniWebsiteTemplateId);
}

// Hook up the AJAX action
add_action('wp_ajax_fetch_mini_website_template', 'fetch_mini_website_template');
add_action('wp_ajax_nopriv_fetch_mini_website_template', 'fetch_mini_website_template');

