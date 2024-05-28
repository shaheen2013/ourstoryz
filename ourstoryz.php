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

// Related event api fetch 


// Function to fetch mini website template
// function fetch_mini_website_template()
// {
//   // Check if the request is coming from a valid source
//   check_ajax_referer('fetch_mini_website_template_nonce', 'security');

//   // Get the event ID from the AJAX request
//   $event_id = $_POST['event_id'];

//   // Fetch API data
//   $api_data = fetch_api_data($event_id);

//   // Get mini website template ID from API data
//   if (!empty($api_data) && isset($api_data['data']['mini_website_template_id'])) {
//       $miniWebsiteTemplateId = $api_data['data']['mini_website_template_id'];
//   } else {
//       // Set default ID if API response is invalid or mini_website_template_id is not set
//       $updated_value = get_option('default_template');
//       $miniWebsiteTemplateId =  $updated_value; // Replace 'default_id' with your actual default ID
//   }

//   // Return the mini website template ID
//   wp_send_json_success($miniWebsiteTemplateId);
// }

// // Hook up the AJAX action
// add_action('wp_ajax_fetch_mini_website_template', 'fetch_mini_website_template');
// add_action('wp_ajax_nopriv_fetch_mini_website_template', 'fetch_mini_website_template');

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
    $host = 'Hosted by : ' . $hostedBy;
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

    // Return the "project_image" data
    return $projectImage;
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

function display_related_events_name()
{

  $data = fetch_api_data();

  // Check if the data is not empty and the "our_storyz_description" key exists
  if (!empty($data) && (isset($data['data']['storyz']['id']) && isset($data['data']['id']))) {
    // Get the "our_storyz_description" data
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];

    // Return the "our_storyz_description" data

    $data = fetch_related_events_data($storyz_id, $event_id);


    if (empty($data) || !isset($data['data'])) {
      return 'No related events found.';
    }

    $output = '<ul>';

    foreach ($data['data'] as $event) {
      if (isset($event['event_name'])) {
        $output .= '<li>' . esc_html($event['event_name']) . '</li>';
      }
    }

    $output .= '</ul>';
    return $output;
  }
}

add_shortcode('related_events_names', 'display_related_events_name');



function display_related_events_types($atts)
{

  $data = fetch_api_data();

  // Check if the data is not empty and the "our_storyz_description" key exists
  if (!empty($data) && (isset($data['data']['storyz']['id']) && isset($data['data']['id']))) {
    // Get the "our_storyz_description" data
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];

    // Return the "our_storyz_description" data

    $data = fetch_related_events_data($storyz_id, $event_id);


    if (empty($data) || !isset($data['data'])) {
      return 'No related events found.';
    }

    $output = '<ul>';

    foreach ($data['data'] as $event) {
      if (isset($event['event_type'])) {
        $output .= '<li>' . esc_html($event['event_type']) . '</li>';
      }
    }

    $output .= '</ul>';
    return $output;
  }
}

add_shortcode('related_events_types', 'display_related_events_types');


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


function display_related_events_location()
{

  $data = fetch_api_data();

  // Check if the data is not empty and the "our_storyz_description" key exists
  if (!empty($data) && (isset($data['data']['storyz']['id']) && isset($data['data']['id']))) {
    // Get the "our_storyz_description" data
    $storyz_id = $data['data']['storyz']['id'];
    $event_id = $data['data']['id'];

    // Return the "our_storyz_description" data

    $data = fetch_related_events_data($storyz_id, $event_id);


    if (empty($data) || !isset($data['data'])) {
      return 'No related events found.';
    }

    $output = '<ul>';

    foreach ($data['data'] as $event) {
      if (isset($event['location'])) {
        $output .= '<li>' . esc_html($event['location']) . '</li>';
      }
    }

    $output .= '</ul>';
    return $output;
  }
}

add_shortcode('related_events_location', 'display_related_events_location');


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



function display_related_events_guests_image()
{
    $data = fetch_api_data();

    // Check if the data is not empty and the "our_storyz_description" key exists
    if (!empty($data) && isset($data['data']['id'])) {
        // Get the "our_storyz_description" data
        $event_id = $data['data']['id'];

        // Fetch related guests data
        $data = fetch_related_guests_data($event_id);

        if (empty($data) || !isset($data['data'])) {
            return 'No related events found.';
        }

        $output = '<ul>';

        foreach ($data['data'] as $event) {
            if (isset($event['imageUrl']) && !empty($event['imageUrl'])) {
                // If image URL is available, add it to the list
                $output .= '<li><img src="' . esc_url($event['imageUrl']) . '" alt="Guest Image"></li>';
            }
        }

        $output .= '</ul>';
        return $output;
    } else {
        return 'No data available.';
    }
}

add_shortcode('related_events_guests_image', 'display_related_events_guests_image');



function display_guests_names()
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

        $output = '<ul>';

        foreach ($guests_data['data'] as $guest) {
            $full_name = '';

            // Check if both first_name and last_name are set
            if (isset($guest['first_name']) && isset($guest['last_name'])) {
                $full_name = $guest['first_name'] . ' ' . $guest['last_name'];
            }

            // If full_name is not empty, add it to the list
            if (!empty($full_name)) {
                $output .= '<li>' . esc_html($full_name) . '</li>';
            }
        }

        $output .= '</ul>';
        return $output;
    } else {
        return 'No data available.';
    }
}

add_shortcode('guests_names', 'display_guests_names');

function display_guests_images_and_names()
{
  $version = phpversion();
var_dump($version);
die();
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
            return $guests_count  ;
        } else {
            return $guests_count  ;
        }
    } else {
        return 'No data available.';
    }
}

add_shortcode('guests_count', 'display_guests_count');
