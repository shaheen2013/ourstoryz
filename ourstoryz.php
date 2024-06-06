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
  wp_localize_script(
    'custom-script',
    'ajax_object',
    array(
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

    $miniWebsiteTemplateId = $updated_value; // Replace 'default_id' with your actual default ID
  } else {
    $miniWebsiteTemplateId = $data->data->mini_website_template_id;
  }

  // Return the mini website template ID
  wp_send_json_success($miniWebsiteTemplateId);
}

// Hook up the AJAX action
add_action('wp_ajax_fetch_mini_website_template', 'fetch_mini_website_template');
add_action('wp_ajax_nopriv_fetch_mini_website_template', 'fetch_mini_website_template');



// Function to fetch API data
function fetch_api_data()
{
  $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : '';
  $response = wp_remote_get("https://api.dev.ourstoryz.com/api/templates/event/storyz?event_id=" . $event_id);

  if (is_wp_error($response)) {
    return null;
  }
  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}


// Fetch and return event name
function get_event_name()
{

  $data = fetch_api_data();
  if (!empty($data) && isset($data['data']['event_name'])) {
    return esc_html($data['data']['event_name']);
  }
  return 'Event name not found.';
}
add_shortcode('event_name', 'get_event_name');


function get_event_cover_image()
{
  $data = fetch_api_data();

  if (!empty($data) && isset($data['data']['cover_image'])) {
    // Construct the image tag with the cover image URL

    $image_tag = '<img src="' . esc_url($data['data']['cover_image']) . '" alt="Event Cover Image" style="width: 100%;">';
    return $image_tag;
  }
  return 'Event cover image not found.';
}

add_shortcode('event_cover_image', 'get_event_cover_image');


// Fetch and return event description
function get_event_description()
{
  // Fetch the API data
  $data = fetch_api_data();

  // Check if the data is not empty and the event_description key exists
  if (!empty($data) && isset($data['data']['event_description'])) {
    // Get the event description
    $eventDescription = $data['data']['event_description'];

    // Escape and return the event description
    return esc_html($eventDescription);
  }

  // Return a default message if event_description is not found
  return 'Event description not found.';
}

// Register the shortcode [event_description]
add_shortcode('event_description', 'get_event_description');


// Fetch and return event start date
function get_event_end_date()
{
  $data = fetch_api_data();
  if (!empty($data) && isset($data['data']['event_start_date'])) {
    // Get the event start date from the API data
    $eventStartDate = $data['data']['event_start_date'];

    // Create a DateTime object from the event start date
    $date = new DateTime($eventStartDate);

    // Format the date in the desired format
    $formattedDate = $date->format('l, F d, Y');

    // Escape and return the formatted date
    return esc_html($formattedDate);
  }
  return 'Event start date not found.';
}
add_shortcode('event_date', 'get_event_end_date');

function get_rsvp_deadline()
{
  $data = fetch_api_data();
  if (!empty($data) && isset($data['data']['rsvp_deadline'])) {
    // Get the RSVP deadline from the API data
    $rsvpDeadline = $data['data']['rsvp_deadline'];

    // Create a DateTime object from the RSVP deadline
    $date = new DateTime($rsvpDeadline);

    // Format the date in the desired format
    $formattedDate = 'RSVP by ' . $date->format('F d');

    // Escape and return the formatted date
    return esc_html($formattedDate);
  }
  return 'RSVP deadline not found.';
}
add_shortcode('rsvp_deadline', 'get_rsvp_deadline');


function get_event_start_time()
{
  $data = fetch_api_data();
  if (!empty($data) && isset($data['data']['event_start_date'])) {
    // Get the event start date from the API data
    $eventStartDate = $data['data']['event_start_date'];

    // Create a DateTime object from the event start date
    $date = new DateTime($eventStartDate);

    // Set the timezone to PST (Pacific Standard Time)
    $timezone = new DateTimeZone('America/Los_Angeles');
    $date->setTimezone($timezone);

    // Format the time in the desired format
    $formattedTime = $date->format('g:ia') . ' PST';

    // Escape and return the formatted time
    return esc_html($formattedTime);
  }
  return 'Event start date not found.';
}
add_shortcode('event_start_time', 'get_event_start_time');



function get_greeting_title()
{
  // Fetch the API data
  $data = fetch_api_data();

  // Check if the data is not empty and the storyz object with greeting_title key exists
  if (!empty($data) && isset($data['data']['storyz']['greeting_title'])) {
    // Get the greeting title from the storyz object
    $greetingTitle = $data['data']['storyz']['greeting_title'];

    // Escape and return the greeting title
    return esc_html($greetingTitle);
  }

  // Return a default message if greeting_title is not found
  return 'Greeting title not found.';
}

// Register the shortcode [greeting_title]
add_shortcode('greeting_title', 'get_greeting_title');


function get_storyz_name()
{
  // Fetch the API data
  $data = fetch_api_data();

  // Check if the data is not empty and the storyz_name key exists
  if (!empty($data) && isset($data['data']['storyz']['storyz_name'])) {
    // Get the storyz name
    $storyzName = $data['data']['storyz']['storyz_name'];

    // Escape and return the storyz name
    return esc_html($storyzName);
  }

  // Return a default message if storyz_name is not found
  return 'Storyz name not found.';
}

// Register the shortcode [storyz_name]
add_shortcode('storyz_name', 'get_storyz_name');



function get_hosted_by()
{
  // Fetch the API data
  $data = fetch_api_data();

  // Check if the data is not empty and the "hosted_by" key exists
  if (!empty($data) && isset($data['data']['storyz']['hosted_by'])) {
    // Get the "hosted_by" data
    $hostedBy = $data['data']['storyz']['hosted_by'];
    $host = $hostedBy;
    // Return the "hosted_by" data
    return $host;
  }

  // Return a default message if "hosted_by" is not found
  return 'Hosted by data not found.';
}

// Register the shortcode [hosted_by]
add_shortcode('hosted_by', 'get_hosted_by');



function get_our_storyz_description()
{
  // Fetch the API data
  $data = fetch_api_data();

  // Check if the data is not empty and the "our_storyz_description" key exists
  if (!empty($data) && isset($data['data']['storyz']['our_storyz_description'])) {
    // Get the "our_storyz_description" data
    $ourStoryzDescription = $data['data']['storyz']['our_storyz_description'];

    // Return the "our_storyz_description" data
    return $ourStoryzDescription;
  }

  // Return a default message if "our_storyz_description" is not found
  return 'Our Storyz description not found.';
}

// Register the shortcode [our_storyz_description]
add_shortcode('our_storyz_description', 'get_our_storyz_description');


function get_project_image()
{
  // Fetch the API data
  $data = fetch_api_data();

  // Check if the data is not empty and the "project_image" key exists
  if (!empty($data) && isset($data['data']['storyz']['project_image'])) {
    // Get the "project_image" data
    $projectImage = $data['data']['storyz']['project_image'];

    // Return the HTML img tag with the "project_image" data
    return '<img src="' . esc_url($projectImage) . '" height="300" width="300" alt="Project Image">';
  }

  // Return a default message if "project_image" is not found
  return 'Project image not found.';
}

// Register the shortcode [project_image]
add_shortcode('project_image', 'get_project_image');




// Releted event data fetch 

function fetch_related_events_data($storyz_id, $related_event_id)
{

  $response = wp_remote_get("https://api.dev.ourstoryz.com/api/templates/event/list?storyz_id=" . intval($storyz_id) . "&related_event_id=" . intval($related_event_id));

  if (is_wp_error($response)) {
    return null;
  }

  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}

function display_related_events_info()
{
  $data = fetch_api_data();

  // Check if the data is not empty and the required keys exist
  if (!empty($data) && isset($data['data']['storyz']['id']) && isset($data['data']['id'])) {
    // Get the "our_storyz_description" data
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];

    // Fetch related events data
    $data = fetch_related_events_data($storyz_id, $event_id);

    if (empty($data) || !isset($data['data'])) {
      return 'No related events found.';
    }

    $output = '<div class="container mt-5">';

    foreach ($data['data'] as $event) {
      // Define an array of required fields
      $required_fields = ['event_name', 'event_type', 'cover_image', 'rsvp_deadline', 'event_start_date', 'event_end_date', 'location'];

      // Check if all required fields are present
      $all_fields_present = true;
      foreach ($required_fields as $field) {
        if (!array_key_exists($field, $event)) {
          $all_fields_present = false;
          break;
        }
      }

      if ($all_fields_present) {
        // Use a default image if cover_image is null
        $cover_image = $event['cover_image'] ?: 'https://mootup.com/wp-content/uploads/2020/07/Zoom-webinar-3d-8.8-screens-1024x576.png';

        // Format the RSVP deadline
        $rsvp_deadline = new DateTime($event['rsvp_deadline']);
        $formatted_rsvp_deadline = $rsvp_deadline->format('M j');

        // Format the event start and end dates
        $event_start_date = new DateTime($event['event_start_date']);
        $event_end_date = new DateTime($event['event_end_date']);
        if ($event_start_date->format('Y-m-d') === $event_end_date->format('Y-m-d')) {
          $formatted_event_dates = $event_start_date->format('F j, Y');
        } else {
          $formatted_event_dates = $event_start_date->format('F j') . ' - ' . $event_end_date->format('j, Y');
        }

        // Format location
        $location = '';
        if (is_array($event['location'])) {
          $location .= explode("\n", $event['location']['location'])[0]; // Take only the first portion
        } else {
          $location = explode("\n", $event['location'])[0]; // Take only the first portion
        }

        // Build the event card
        $output .= '<div class="card p-3 mb-3">';
        $output .= '<div class="row g-0">';
        $output .= '<div class="col-md-2">';
        $output .= '<img src="' . esc_url($cover_image) . '" class="img-fluid rounded-start card-img" alt="' . esc_attr($event['event_name']) . '">';
        $output .= '</div>';
        $output .= '<div class="col-md-10">';
        $output .= '<div class="card-body d-flex align-items-center justify-content-between">';
        $output .= '<div>';
        $output .= '<h5 class="card-title">' . esc_html($event['event_name']) . '</h5>';
        $output .= '<p class="card-text text-muted">' . esc_html($event['event_type']) . ' &bull; RSVP by ' . esc_html($formatted_rsvp_deadline) . '</p>';
        $output .= '</div>';
        $output .= '<div class="text-center flex-grow-1">';
        $output .= '<p class="date-text">' . esc_html($formatted_event_dates) . '</p>';
        $output .= '</div>';
        if (!empty($location)) {
          $output .= '<div class="text-end">';
          $output .= '<p class="link-text">' . esc_html($location) . ' <small class="text-muted arrow">&rarr;</small></p>';
          $output .= '</div>';
        } else {
          // If location is empty, just display the arrow
          $output .= '<div class="text-end">';
          $output .= '<p class="link-text"><small class="text-muted arrow">&rarr;</small></p>';
          $output .= '</div>';
        }
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
      }
    }

    $output .= '</div>';
    return $output;
  } else {
    return 'No data available.';
  }
}

add_shortcode('related_events_info', 'display_related_events_info');

function display_rsvp_deadlines()
{
  $data = fetch_api_data();
  // Check if the data is not empty and the "our_storyz_description" key exists
  if (!empty($data) && isset($data['data']['storyz']['id']) && isset($data['data']['id'])) {
    // Get the "our_storyz_description" data
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];

    // Fetch related events data
    $data = fetch_related_events_data($storyz_id, $event_id);

    if (empty($data) || !isset($data['data'])) {
      return 'No RSVP deadlines found.';
    }

    $output = '<ul>';

    foreach ($data['data'] as $event) {
      if (isset($event['rsvp_deadline'])) {
        // Parse the deadline date
        $deadline_date = date_create($event['rsvp_deadline']);
        // Format the date to display only month and day
        $formatted_deadline = "RSVP by " . date_format($deadline_date, "M d");
        $output .= '<li>' . esc_html($formatted_deadline) . '</li>';
      }
    }

    $output .= '</ul>';
    return $output;
  } else {
    return 'No data available.';
  }
}

add_shortcode('rsvp_deadlines', 'display_rsvp_deadlines');

function display_date()
{
  $data = fetch_api_data();
  // Check if the data is not empty and the "our_storyz_description" key exists
  if (!empty($data) && isset($data['data']['storyz']['id']) && isset($data['data']['id'])) {
    // Get the "our_storyz_description" data
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];

    // Fetch related events data
    $data = fetch_related_events_data($storyz_id, $event_id);

    if (empty($data) || !isset($data['data'])) {
      return 'No RSVP deadlines found.';
    }

    $output = '<ul>';

    foreach ($data['data'] as $event) {
      if (isset($event['event_start_date']) && isset($event['event_end_date'])) {

        $eventStartDate = new DateTime($event['event_start_date']);
        $eventEndDate = new DateTime($event['event_end_date']);
        if ($eventStartDate->format('Y-m-d') === $eventEndDate->format('Y-m-d')) {
          // Same day event
          $formattedDate = $eventStartDate->format('F j, Y');
        } else {
          // Multi-day event
          $formattedDate = $eventStartDate->format('F j') . '-' . $eventEndDate->format('j, Y');
        }
        $output .= '<li>' . esc_html($formattedDate) . '</li>';
      }
    }

    $output .= '</ul>';
    return $output;

  } else {
    return 'No data available.';
  }
}

add_shortcode('display_date', 'display_date');


// Guests Data fetch

function fetch_related_guests_data($related_event_id)
{

  $response = wp_remote_get("https://api.dev.ourstoryz.com/api/templates/event/guest/list?event_id=" . intval($related_event_id));

  if (is_wp_error($response)) {
    return null;
  }

  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}

function display_guests_images_and_names()
{

  $data = fetch_api_data();

  // Check if the data is not empty
  if (!empty($data) && isset($data['data']['id'])) {
    // Get the event ID
    $event_id = $data['data']['id'];

    // Fetch related guests data
    $guests_data = fetch_related_guests_data($event_id);

    if (empty($guests_data) || !isset($guests_data['data'])) {
      return 'No guests found.';
    }

    $output = '<div class="container">';
    $output .= '<div class="row justify-content-center">';

    // Counter to keep track of the number of images displayed
    $count = 0;

    foreach ($guests_data['data'] as $guest) {
      // Limit the number of images displayed to 4
      if ($count >= 4) {
        break;
      }

      $full_name = '';
      $image_url = '';

      // Check if both first_name and last_name are set
      if (isset($guest['first_name']) && isset($guest['last_name'])) {
        $full_name = $guest['first_name'] . ' ' . $guest['last_name'];
      }

      // Check if imageUrl is set
      if (isset($guest['imageUrl']) && !empty($guest['imageUrl'])) {
        $image_url = $guest['imageUrl'];
      }

      // If full_name and image_url are not empty, add them to the list
      if (!empty($full_name) && !empty($image_url)) {
        $output .= '<div class="col-6 col-md-3 text-center">';
        $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($full_name) . '" class="rounded-circle img-fluid">';
        $output .= '<div class="mt-2">' . esc_html($full_name) . '</div>';
        $output .= '</div>';
        $count++;
      }
    }

    $output .= '</div>';
    $output .= '</div>';
    return $output;
  } else {
    return 'No data available.';
  }
}

add_shortcode('guests_images_and_names', 'display_guests_images_and_names');

function display_guests_count()
{
  $data = fetch_api_data();

  // Check if the data is not empty
  if (!empty($data) && isset($data['data']['id'])) {
    // Get the event ID
    $event_id = $data['data']['id'];

    // Fetch related guests data
    $guests_data = fetch_related_guests_data($event_id);

    if (empty($guests_data) || !isset($guests_data['data'])) {
      return 'No guests attending.';
    }

    // Count the number of guests
    $guests_count = count($guests_data['data']);

    // Check if the count is greater than 1
    if ($guests_count > 1) {
      return $guests_count;
    } else {
      return $guests_count;
    }
  } else {
    return 'No data available.';
  }
}

add_shortcode('guests_count', 'display_guests_count');

function display_event_start_time()
{
  $data = fetch_api_data();

  // Check if the data is not empty and the required keys exist
  if (!empty($data) && isset($data['data']['id']) && isset($data['data']['event_start_date'])) {
    // Extract the event start date and time
    $event_start_date = new DateTime($data['data']['event_start_date']);

    // Convert timezone to CST
    $event_start_date->setTimezone(new DateTimeZone('America/Chicago'));

    // Format the time
    $formatted_time = $event_start_date->format('g:i A');

    // Output the formatted time with prefix
    return 'Begins: ' . $formatted_time . ' CST';
  } else {
    // No event start time available, return empty string
    return '';
  }
}

add_shortcode('event_start_time', 'display_event_start_time');

function display_event_end_time()
{
  $data = fetch_api_data();

  // Check if the data is not empty and the required keys exist
  if (!empty($data) && isset($data['data']['id']) && isset($data['data']['event_end_date'])) {
    // Extract the event end date and time
    $event_end_date = new DateTime($data['data']['event_end_date']);

    // Convert timezone to CST
    $event_end_date->setTimezone(new DateTimeZone('America/Chicago'));

    // Format the time
    $formatted_time = $event_end_date->format('g:i A');

    // Output the formatted time with prefix
    return 'Ends: ' . $formatted_time . ' CST';
  } else {
    // No event end time available, return empty string
    return '';
  }
}

add_shortcode('event_end_time', 'display_event_end_time');

function display_full_location()
{
  $data = fetch_api_data();


  // Check if the data is not empty and the required keys exist
  if (!empty($data) && isset($data['data']['id']) && isset($data['data']['location']['location'])) {
    $location_data = $data['data']['location'];

    // Extract location details
    $location = isset($location_data['location']) ? $location_data['location'] : '';
    $latitude = isset($location_data['latitude']) ? $location_data['latitude'] : '';
    $longitude = isset($location_data['longitude']) ? $location_data['longitude'] : '';

    // Format the location
    $formatted_location = $location . "\n" . $latitude . "\n" . $longitude;

    // Output the formatted location
    return $formatted_location;
  } else {
    // No location available, return empty string
    return '';
  }
}

add_shortcode('full_location', 'display_full_location');


//   keepsakealbum data
function fetch_related_events_keepsakealbum_data($related_event_id, $storyz_id)
{

  $response = wp_remote_get("https://api.dev.ourstoryz.com/api/templates/event/keepsakealbum?event_id=" . intval($related_event_id) . "&storyz_id=" . intval($storyz_id));

  if (is_wp_error($response)) {
    return null;
  }

  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}

function keepsakealbum()
{
  $data = fetch_api_data();

  // Check if the data is not empty and the "our_storyz_description" key exists
  if (!empty($data) && isset($data['data']['storyz']['id']) && isset($data['data']['id'])) {
    // Get the "our_storyz_description" data
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];

    // Fetch related events data
    $album_data = fetch_related_events_keepsakealbum_data($event_id, $storyz_id);

    if (empty($album_data) || !isset($album_data['data'])) {
      return 'No Keepsakealum data found.';
    }

    $all = $album_data['data']['keepsakeAlbum'];
    $images = $all[0]['images'];

    $output = '<div class="container">';
    $output .= '<div class="row justify-content-center">';

    // Counter to keep track of the number of images displayed
    // $count = 0;

    foreach ($images as $data) {
      // Limit the loop to run only four times
      // if ($count < 4) {
      // Variable to store HTML for media display
      $media_html = '';

      // Check if imageUrl is set
      if (isset($data['photo_url']) && !empty($data['photo_url'])) {
        $media_url = $data['photo_url'];

        // Check if the media URL ends with ".mp4"
        if (substr($media_url, -4) === '.mp4') {
          // If it's a video, generate HTML for video player
          $media_html = '<video controls class="img-fluid" style="width: 300px; height: 250px;border-radius: 10px; ">';
          $media_html .= '<source src="' . esc_url($media_url) . '" type="video/mp4">';
          $media_html .= 'Your browser does not support the video tag.';
          $media_html .= '</video>';

        } else {
          // If it's not a video, generate HTML for image
          $media_html = '<img src="' . esc_url($media_url) . '" alt="' . esc_attr($data['caption']) . '" class="img-fluid" style="width: 300px; height: 250px;border-radius: 10px;">';
        }
      }

      // If media HTML is not empty, add it to the output
      if (!empty($media_html)) {
        $output .= '<div class="col-6 col-md-3 text-center">';
        $output .= $media_html;
        $output .= '<div class="mt-2 text-white">' . esc_html($data['caption']) . '</div>';
        $output .= '</div>';

        $count++;
      }
      // } else {
      //   // If four images have been displayed, break out of the loop
      //   break;
      // }
    }

    $output .= '</div>';
    $output .= '</div>';
    return $output;
  } else {
    return 'No data available.';
  }
}

add_shortcode('keepsakealbum_data', 'keepsakealbum');


// Keepsakealbum by guest

function keepsakealbum_by_guest_event_date()
{
  $data = fetch_api_data();

  // Check if the data is not empty and the required keys exist
  if (!empty($data) && isset($data['data']['id']) && isset($data['data']['event_end_date'])) {
    // Extract the event end date and time
    $event_end_date = new DateTime($data['data']['event_end_date']);

    // Convert timezone to CST
    $event_end_date->setTimezone(new DateTimeZone('America/Chicago'));

    // Format the start and end dates as desired (e.g., "August 14-15, 2024")
    $start_date = clone $event_end_date;
    $end_date = clone $event_end_date;
    $end_date->modify('+1 day');

    // Check if the month is the same for both dates
    if ($start_date->format('F') === $end_date->format('F')) {
      $output = $start_date->format('F j') . '-' . $end_date->format('j, Y');
    } else {
      $output = $start_date->format('F j') . ' - ' . $end_date->format('F j, Y');
    }

    return $output;
  } else {
    return 'No data available.';
  }
}

// Register the shortcode
add_shortcode('keepsakealbum_event_date', 'keepsakealbum_by_guest_event_date');


// keepsakealbum data fetch by guest 

function fetch_keepsakealbum_data_by_display_type($related_event_id, $storyz_id, $display_type)
{

  $response = wp_remote_get("https://api.dev.ourstoryz.com/api/templates/event/keepsakealbum?event_id=" . intval($related_event_id) . "&storyz_id=" . intval($storyz_id) . "&display_by=" . intval($display_type));

  if (is_wp_error($response)) {
    return null;
  }

  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}
function keepsake_album_cover_image_data($atts)
{

  // Extract shortcode attributes
  $atts = shortcode_atts(
    array(
      'display_type' => 'Guest' // Default display type is 'image'
    ),
    $atts
  );

  // Extract display type from shortcode attributes
  $display_type = $atts['display_type'];

  $data = fetch_api_data();

  // Check if the data is not empty and the "cover_image" key exists
  if (!empty($data) && isset($data['data']['storyz']['id']) && isset($data['data']['id'])) {
    // Get the necessary IDs
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];

    // Fetch related events data
    $album_data = fetch_keepsakealbum_data_by_display_type($event_id, $storyz_id, $display_type);

    // Check if album data is not empty and contains the necessary keys
    if (empty($album_data) || !isset($album_data['data']['cover_image'])) {
      return 'No Keepsakealbum data found.';
    }

    // Get the cover image URL
    $cover_image_url = $album_data['data']['cover_image'];



    // Generate HTML for image
    $output = '<div class="container">';
    $output .= '<div class="row justify-content-center">';
    $output .= '<div class="col-12 text-center">';

    // Check display type

    $output .= '<img src="' . (!empty($cover_image_url) ? esc_url($cover_image_url) : 'https://example.com/dummy-image.jpg') . '" alt="Cover Image" class="img-fluid" style="width: 100%; height: auto; border-radius: 10px;">';

    $output .= '</div>'; // Close col-12
    $output .= '</div>'; // Close row
    $output .= '</div>'; // Close container

    return $output;
  } else {
    return 'No data available.';
  }
}

add_shortcode('keepsakealbum_coverimage_data', 'keepsake_album_cover_image_data');




function keepsakealbum_data_by_guest($atts)
{


  $atts = shortcode_atts(
    array(
      'display_type' => 'Guest' // Default display type is 'Guest'
    ),
    $atts
  );
  $display_type = $atts['display_type'];
  
  $data = fetch_api_data();

  // Check if the data is not empty and the required keys exist
  if (!empty($data) && isset($data['data']['storyz']['id']) && isset($data['data']['id'])) {
    // Get the IDs
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];
    $event_end_date = new DateTime($data['data']['event_end_date']);
    var_dump($event_end_date);
    die();
    // Convert timezone to CST
    $event_end_date->setTimezone(new DateTimeZone('America/Chicago'));

    // Format the start and end dates as desired (e.g., "August 14-15, 2024")
    $start_date = clone $event_end_date;
    $end_date = clone $event_end_date;
    $end_date->modify('+1 day');
    $event_date = '';
    // Check if the month is the same for both dates
    if ($start_date->format('F') === $end_date->format('F')) {
      $event_date = $start_date->format('F j') . '-' . $end_date->format('j, Y');
    } else {
      $event_date = $start_date->format('F j') . ' - ' . $end_date->format('F j, Y');
    }
    // Fetch related events data
    $album_data = fetch_keepsakealbum_data_by_display_type($event_id, $storyz_id, $display_type);

    // Check if album data is available
    if (empty($album_data) || !isset($album_data['data'])) {
      return 'No Keepsakealum data found.';
    }

    $all = $album_data['data']['keepsakeAlbum'];
    $images = $all[0]['images'];

    // Start HTML output
    $output = '<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">';
    $output .= '<div class="event-card bg-white">';

    foreach ($images as $data) {
      $event_image = '';
      $guest_profile = '';

      // Check if photo_url is set and not empty
      if (isset($data['photo_url']) && !empty($data['photo_url'])) {
        $media_url = $data['photo_url'];

        // Generate HTML for image
        $event_image = '<img src="' . esc_url($media_url) . '" alt="' . esc_attr($data['caption']) . '" class="event-img-small" style="border-radius: 10px;">';
      }

      if (isset($data['guest_profile']) && !empty($data['guest_profile'])) {
        $guest_profile = $data['guest_profile'];

        // Generate HTML for image
        $guest_profile = '<img style="border-radius: 10px;" src="' . esc_url($guest_profile) . '" class="mb-3" alt="Main Event">';
      }


      // Add media HTML to output if not empty
      if (!empty($event_image)) {
        $output .= '<div style="gap:16px;" class="d-flex align-items-center justify-content-center">';
        $output .= $guest_profile; // Assuming $data['guest_profile'] contains the guest profile image URL
        $output .= '<div>';
        $output .= '<h5>' . esc_html($data['guest_name']) . '</h5>'; // Assuming $data['guest_name'] contains the guest name
        $output .= '<p>' . esc_html($event_date) . '</p>'; // Assuming $data['date'] contains the date
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="event-img-container">';
        $output .= $event_image;
        $output .= '</div>'; // Close event-img-container
      }
    }

    $output .= '</div>'; // Close event-card
    $output .= '</div>'; // Close main container

    return $output;
  } else {
    return 'No data available.';
  }
}

add_shortcode('keepsakealbum_guest_data', 'keepsakealbum_data_by_guest');

