<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://mediusware.com
 * @since      1.0.0
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/includes
 * @author     Mediusware <zahid@mediusware.com>
 */

 add_shortcode('search_result', 'search_result_show');
// All event list endpoint
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

  // var_dump($response);

  // return;

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

  // var_dump($data);

  // return;

  // function getCityFromLocation($location)
  // {
  //   // Split the location string by commas
  //   $parts = explode(',', $location);
  //   // Return the second part (the city name) if it exists
  //   return isset($parts[0]) ? trim($parts[0]) : 'Unknown City';
  // }

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

      $cityName = getCityFromLocation($event['location']);

          

      echo '<div class="container mt-5">
          <div class="card p-3 event-link" data-event-id="' . $event['event_id'] . '" style="cursor:pointer">
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
                    <p class="link-text">' . $cityName . ' <small class="text-muted arrow">&rarr;</small>
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

function getCityFromLocation($location)
{
  // Split the location string by commas
  $parts = explode(',', $location);
  // Return the second part (the city name) if it exists
  return isset($parts[2]) ? trim($parts[2]) : ' ';
}


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
// related event info 
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
 
        $location = getCityFromLocation($event['location']);
        // if (is_array($event['location'])) {
        //   $location .= explode("\n", $event['location']['location'])[0]; // Take only the first portion
        // } else {
        //   $location = explode("\n", $event['location'])[0]; // Take only the first portion
        // }



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
          $output .= '<p class="link-text">' . $location . '  <small class="text-muted arrow">&rarr;</small></p>';
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