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
    $count = 0;

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

  $response = wp_remote_get("https://api.dev.ourstoryz.com/api/templates/event/keepsakealbum?event_id=" . intval($related_event_id) . "&storyz_id=" . intval($storyz_id) . "&display_by=" . $display_type);

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


// done
function keepsakealbum_data_by_guest($atts)
{
  $atts = shortcode_atts(
    array(
      'display_type' => '' // Default display type is 'Guest'
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

    // Fetch related events data
    $album_data = fetch_keepsakealbum_data_by_display_type($event_id, $storyz_id, $display_type);

    // Check if album data is available
    if (!empty($album_data['data']['keepsakeAlbum'])) {
      $all = $album_data['data']['keepsakeAlbum'];

      // Start HTML output
      $output = '';

      // Initialize guest counter
      $guest_count = 0;

      // Empty div to include the loop data
      $output .= '<div class="d-flex">';

      foreach ($all as $guest) {
        // Check if guest counter has reached 3
        if ($guest_count >= 3) {
          break;
        }

        $guest_name = $guest['guest_name'];
        $guest_profile = $guest['guest_profile'];
        $guest_images = isset($guest['images']) ? $guest['images'] : array();

        // Start guest HTML
        $output .= '<div class="d-flex justify-content-center align-items-center">';
        $output .= '<div class="event-card bg-white">';
        $output .= '<div class="d-flex align-items-center justify-content-center" style="gap: 16px;">';
        $output .= '<img src="' . esc_url($guest_profile) . '" class="mb-3 event-img-big" style="border-radius: 10px; height: 150px; width: 150px;" alt="Main Event">';
        $output .= '<div>';
        $output .= '<h5>' . esc_html($guest_name) . '</h5>';
        $output .= '<p>Date</p>'; // Replace "Date" with the actual date
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="event-img-container">';

        // Initialize media counter
        $media_count = 0;

        // Loop through guest images
        foreach ($guest_images as $media) {
          // Check if media counter has reached 3
          if ($media_count >= 3) {
            break;
          }

          $photo_url = $media['photo_url'];

          if (strpos($photo_url, 'mpr') !== false) {
            // If the URL contains "mpr", assume it's a video
            $output .= '<video class="event-img-small" controls style="height: 150px; width: 150px;">
                                        <source src="' . esc_url($photo_url) . '" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>';
          } else {
            // Otherwise, it's an image
            $output .= '<img src="' . esc_url($photo_url) . '" class="event-img-small" style="height: 150px; width: 150px;" alt="Event Image">';
          }

          // Increment media counter
          $media_count++;
        }

        $output .= '</div>'; // Close event-img-container
        $output .= '</div>'; // Close event-card
        $output .= '</div>'; // Close main container

        // Increment guest counter
        $guest_count++;
      }

      $output .= '</div>'; // Close guests-container

      return $output;
    } else {
      return 'No Keepsakealum data found.';
    }
  } else {
    return 'No data available.';
  }
}
add_shortcode('keepsakealbum_guest_data', 'keepsakealbum_data_by_guest');