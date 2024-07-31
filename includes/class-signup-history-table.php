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


 function create_signup_history_table() {
    global $wpdb;
  
    $table_name = $wpdb->prefix . 'signup_history';
    $charset_collate = $wpdb->get_charset_collate();
  
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            profile_image varchar(255) DEFAULT '' NOT NULL,
            storyz_name varchar(255) DEFAULT '' NOT NULL,
            storyz_image varchar(255) DEFAULT '' NOT NULL,
            organization_name varchar(255) DEFAULT '' NOT NULL,
            tagline varchar(255) DEFAULT '' NOT NULL,
            brand_logo varchar(255) DEFAULT '' NOT NULL,
            event_name varchar(255) DEFAULT '' NOT NULL,
            event_start_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            event_end_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            event_image varchar(255) DEFAULT '' NOT NULL,
            location varchar(255) DEFAULT '' NOT NULL,
            event_greeting varchar(255) DEFAULT '' NOT NULL,
            event_type varchar(255) DEFAULT '' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
  
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
  }
  
  register_activation_hook(__FILE__, 'create_signup_history_table');
   