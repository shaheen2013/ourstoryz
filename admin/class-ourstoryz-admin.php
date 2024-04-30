<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mediusware.com
 * @since      1.0.0
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/admin
 * @author     Mediusware <zahid@mediusware.com>
 */
class ourstoryz_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in  ourstoryz_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The  ourstoryz_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ourstoryz-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in  ourstoryz_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The  ourstoryz_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ourstoryz-admin.js', array('jquery'), $this->version, false);
	}

	// Register custom post type 'ourstoryz'
function custom_ourstoryz_post_type() {
    $labels = array(
        'name'               => 'Our Storyz',
        'singular_name'      => 'Our Storyz',
        'menu_name'          => 'Our Storyz',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Our Storyz',
        'edit_item'          => 'Edit Our Storyz',
        'new_item'           => 'New Our Storyz',
        'view_item'          => 'View Our Storyz',
        'search_items'       => 'Search Our Storyz',
        'not_found'          => 'No Our Storyz found',
        'not_found_in_trash' => 'No Our Storyz found in Trash',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'show_in_menu'        => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-admin-post', // Customize the menu icon
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'taxonomies'          => array( 'ourstoryz_category', 'ourstoryz_tag' ), // Add custom taxonomies
        'rewrite'             => array( 'slug' => 'our-storyz' ), // Customize the permalink slug
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type( 'ourstoryz', $args );
}

// Hook to add custom post type 'ourstoryz'


// Register custom taxonomies 'ourstoryz_category' and 'ourstoryz_tag' for 'ourstoryz' post type
function custom_ourstoryz_taxonomies() {
    // Custom category taxonomy
    register_taxonomy(
        'ourstoryz_category',
        'ourstoryz',
        array(
            'label' => 'Our Storyz Categories',
            'hierarchical' => true,
            'rewrite' => array( 'slug' => 'our-storyz-category' ),
        )
    );

    // Custom tag taxonomy
    register_taxonomy(
        'ourstoryz_tag',
        'ourstoryz',
        array(
            'label' => 'Our Storyz Tags',
            'hierarchical' => false,
            'rewrite' => array( 'slug' => 'our-storyz-tag' ),
        )
    );
}

// Hook to add custom taxonomies
 

// Customize submenu names for 'Our Storyz' post type
function custom_ourstoryz_submenu_names() {
    global $submenu;

    // Rename submenu items for 'Our Storyz' post type
    if ( isset( $submenu['edit.php?post_type=ourstoryz'] ) ) {
        $submenu['edit.php?post_type=ourstoryz'][5][0] = 'Our Storyz Templates';
        $submenu['edit.php?post_type=ourstoryz'][10][0] = 'Add New Template';
    }
}


function custom_ourstoryz_submenu_events() {
    add_submenu_page(
        'edit.php?post_type=ourstoryz',
        'Our Storyz Events',
        'Our Storyz Events',
        'manage_options',
        'ourstoryz_events',
        array($this,'custom_ourstoryz_events_page'),
    );
}


// Custom page callback for 'Our Storyz Events' submenu
function custom_ourstoryz_events_page() {
    // Display the content for the 'Our Storyz Events' submenu page here
    $temp_path= plugin_dir_path(__FILE__). 'partials/ourstoryz-events-template.php';
	if(file_exists($temp_path)){
		include($temp_path);

	}
	else{
		echo "File not found";
	}
}
// Hook to customize submenu names

// Add custom columns to the 'Our Storyz' post type list table
function custom_ourstoryz_columns( $columns ) {
    $columns['ourstoryz_category'] = 'Category'; // Add Category column
    $columns['ourstoryz_tag'] = 'Tags'; // Add Tags column
    $columns['ourstoryz_featured_image'] = 'Thumbnail'; // Add Featured Image column

    return $columns;
}

// Populate custom columns with data for the 'Our Storyz' post type list table
function custom_ourstoryz_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'ourstoryz_category':
            $categories = get_the_terms( $post_id, 'ourstoryz_category' );
            if ( $categories && ! is_wp_error( $categories ) ) {
                $category_names = array();
                foreach ( $categories as $category ) {
                    $category_names[] = $category->name;
                }
                echo implode( ', ', $category_names );
            } else {
                echo 'No category';
            }
            break;

        case 'ourstoryz_tag':
            $tags = get_the_terms( $post_id, 'ourstoryz_tag' );
            if ( $tags && ! is_wp_error( $tags ) ) {
                $tag_names = array();
                foreach ( $tags as $tag ) {
                    $tag_names[] = $tag->name;
                }
                echo implode( ', ', $tag_names );
            } else {
                echo 'No tags';
            }
            break;

        case 'ourstoryz_featured_image':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, 'thumbnail' );
            } else {
                echo 'No featured image';
            }
            break;

        default:
            break;
    }
}




	
}
