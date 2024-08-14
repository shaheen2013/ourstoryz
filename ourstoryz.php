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
define('OURSTORYZ_VERSION', '1.1.1');

function ourstoryz_enqueue_styles()
{
  // Enqueue Bootstrap CSS from CDN
  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', array(), '5.2.3');
  wp_enqueue_style('signup-style', plugin_dir_url(__FILE__) . 'assets/style.css', array(), '1.0.0');
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

// event data 
require plugin_dir_path(__FILE__) . 'includes/class-event-data.php';

// end event data
//  include signup_history table

require plugin_dir_path(__FILE__) . 'includes/class-signup-history-table.php';
// end include signup_history table

// Keepsakealbum data 

require plugin_dir_path(__FILE__) . 'includes/class-keepsakealbum-data.php';

// end keepsakealbum data

// guest data
require plugin_dir_path(__FILE__) . 'includes/class-guest-data.php';
// end guest data
// Signup modal 

require plugin_dir_path(__FILE__) . 'includes/class-signup-modal.php';
// End Signup modal
function run_ourstoryz()
{

  $plugin = new ourstoryz();
  $plugin->run();
}
run_ourstoryz();

// Enqueue your script// Enqueue your script
function enqueue_custom_script()
{
  wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?render=6LdoHyMqAAAAADoxXp6VJMHKXQCHlg5x90f0W5Ph', array(), null, true);
  wp_enqueue_script('bootstrap-script','https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js','5.2.3',true);
  wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/custom-script.js', array('jquery'), '1.0', true);
  wp_enqueue_script('google-maps-api','https://maps.googleapis.com/maps/api/js?key=AIzaSyCG2YvMYjtoPcq3tP8ROejpgqd-RxenQOY&libraries=places',array(),null,true);



  wp_enqueue_script('signup-script', plugin_dir_url(__FILE__) . 'assets/custom.js', array(), '1.0.0', true);

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
