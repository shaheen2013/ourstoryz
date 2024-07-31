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