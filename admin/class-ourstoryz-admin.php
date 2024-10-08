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

        wp_enqueue_script('html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js', array(), null, true);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ourstoryz-admin.js', array('jquery', 'html2canvas'), $this->version, false);
        wp_enqueue_script('setting-tab', plugin_dir_url(__FILE__) . 'js/setting-tab.js', array('jquery'), $this->version, false);
        wp_localize_script(
            $this->plugin_name,
            'ajax_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
            )
        );
    }



    // Rsgister custom post type 'Signup'

    function create_signup_post_type()
    {
        $labels = array(
            'name'                  => 'Signups',
            'singular_name'         => 'Signup',
            'menu_name'             => 'Signups',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Signup',
            'edit_item' => 'Edit Our Signup',
            'new_item' => 'New Signup',
            'view_item' => 'View Signup',
            'search_items' => 'Search Signup',
            'not_found' => 'No Signup found',
            'not_found_in_trash' => 'No Signup found in Trash',

        );


        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-admin-post', // Customize the menu icon
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            // 'taxonomies'            => array('signup_category', 'signup_tag'), // Add custom taxonomies
            'rewrite'               => array('slug' => 'signup'), // Customize the permalink slug
            'has_archive'           => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',

        );

        register_post_type('signup', $args);
    }

    // function custom_signup_taxonomies()
    // {
    //     // Custom category taxonomy
    //     register_taxonomy(
    //         'signup_category',
    //         'signup',
    //         array(
    //             'label' => 'Signup Categories',
    //             'hierarchical' => true,
    //             'show_admin_column' => true,
    //             'sortable' => true, // Enable sorting
    //             'rewrite' => array('slug' => 'signup-category'),
    //         )
    //     );

    //     // Custom tag taxonomy
    //     register_taxonomy(
    //         'signup_tag',
    //         'signup',
    //         array(
    //             'label' => 'Signup Tags',
    //             'hierarchical' => false,
    //             'show_admin_column' => true,
    //             'sortable' => true, // Enable sorting
    //             'rewrite' => array('slug' => 'signup-tag'),
    //         )
    //     );
    // }


   
  


    // End Register custom post type 'Signup'

    // Register custom post type 'ourstoryz'
    function custom_ourstoryz_post_type()
    {
        $labels = array(
            'name' => 'Our Storyz',
            'singular_name' => 'Our Storyz',
            'menu_name' => 'Our Storyz',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Our Storyz',
            'edit_item' => 'Edit Our Storyz',
            'new_item' => 'New Our Storyz',
            'view_item' => 'View Our Storyz',
            'search_items' => 'Search Our Storyz',
            'not_found' => 'No Our Storyz found',
            'not_found_in_trash' => 'No Our Storyz found in Trash',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_menu' => true,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-admin-post', // Customize the menu icon
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'taxonomies' => array('ourstoryz_category', 'ourstoryz_tag'), // Add custom taxonomies
            'rewrite' => array('slug' => 'our-storyz'), // Customize the permalink slug
            'has_archive' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );

        register_post_type('ourstoryz', $args);
    }

    // Hook to add custom post type 'ourstoryz'


    // Register custom taxonomies 'ourstoryz_category' and 'ourstoryz_tag' for 'ourstoryz' post type
    function custom_ourstoryz_taxonomies()
    {
        // Custom category taxonomy
        register_taxonomy(
            'ourstoryz_category',
            'ourstoryz',
            array(
                'label' => 'Our Storyz Categories',
                'hierarchical' => true,
                'show_admin_column' => true,
                'sortable' => true, // Enable sorting
                'rewrite' => array('slug' => 'our-storyz-category'),
            )
        );

        // Custom tag taxonomy
        register_taxonomy(
            'ourstoryz_tag',
            'ourstoryz',
            array(
                'label' => 'Our Storyz Tags',
                'hierarchical' => false,
                'show_admin_column' => true,
                'sortable' => true, // Enable sorting
                'rewrite' => array('slug' => 'our-storyz-tag'),
            )
        );
    }

    // Hook to add custom taxonomies


    // Customize submenu names for 'Our Storyz' post type
    function custom_ourstoryz_submenu_names()
    {
        global $submenu;

        // Rename submenu items for 'Our Storyz' post type
        if (isset($submenu['edit.php?post_type=ourstoryz'])) {
            $submenu['edit.php?post_type=ourstoryz'][5][0] = 'Our Storyz Templates';
            $submenu['edit.php?post_type=ourstoryz'][10][0] = 'Add New Template';
        }
    }


    // function custom_ourstoryz_submenu_events()
    // {
    //     add_submenu_page(
    //         'edit.php?post_type=ourstoryz',
    //         'Our Storyz Events',
    //         'Our Storyz Events',
    //         'manage_options',
    //         'ourstoryz_events',
    //         array($this, 'custom_ourstoryz_events_page'),
    //     );
    // }


    function custom_ourstoryz_submenu_events()
    {
        add_submenu_page(
            'edit.php?post_type=signup',
            'Our Storyz Events',
            'Our Storyz Events',
            'manage_options',
            'ourstoryz_events',
            array($this, 'custom_ourstoryz_events_page'),
        );
    }


    // Custom page callback for 'Our Storyz Events' submenu
    function custom_ourstoryz_events_page()
    {
        // Display the content for the 'Our Storyz Events' submenu page here
        $temp_path = plugin_dir_path(__FILE__) . 'partials/ourstoryz-signup-history-template.php';
        if (file_exists($temp_path)) {
            include($temp_path);
        } else {
            echo "File not found";
        }
    }



    function custom_ourstoryz_setting_page()
    {
        add_submenu_page(
            'edit.php?post_type=ourstoryz',
            'Our Storyz Setting',
            'Our Storyz Setting',
            'manage_options',
            'ourstoryz_setting',
            array($this, 'custom_ourstoryz_setting'),
        );
    }

    // Custom page callback for 'Our Storyz Events' submenu
    function custom_ourstoryz_setting()
    {
        $temp_path = plugin_dir_path(__FILE__) . 'partials/ourstoryz-storyz-setting-template.php';
        if (file_exists($temp_path)) {
            include($temp_path);
        } else {
            echo "File not found";
        }
    }

    // Hook to customize submenu names


    function custom_post_table_column_header($columns)
    {
        global $post_type;

        // Check if the current post type is 'ourstoryz'
        if ($post_type === 'ourstoryz') {

            // $columns['ourstoryz_category'] = 'Category'; // Add Category column
            // $columns['ourstoryz_tag'] = 'Tags';
            // Add your custom column to the columns array
            $columns['generate_screenshot'] = 'Action';
            $columns['show_screenshot'] = 'Screenshot';
        }

        return $columns;
    }

    // Customize Admin Column Content
    function custom_post_table_column_content($column_name, $post_id)
    {
        switch ($column_name) {
            case 'generate_screenshot':
                echo '<button class="capture-screenshot-button button button-primary" data-post-id="' . $post_id . '">Generate Thumbnail</button>';
                break;
            case 'show_screenshot':
                if (has_post_thumbnail($post_id)) {
                    echo '<img src="' . esc_url(get_the_post_thumbnail_url($post_id, array(100, 100))) . '" alt="Post Thumbnail" width="100" height="100">';
                } else {
                    echo 'No Image';
                }
                break;
            case 'ourstoryz_category':
                $categories = get_the_terms($post_id, 'ourstoryz_category');
                if ($categories && !is_wp_error($categories)) {
                    $category_links = array();
                    foreach ($categories as $category) {
                        $category_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=ourstoryz&ourstoryz_category=' . $category->slug)) . '">' . $category->name . '</a>';
                    }
                    echo implode(', ', $category_links);
                } else {
                    echo 'No category';
                }
                break;
            case 'ourstoryz_tag':
                $tags = get_the_terms($post_id, 'ourstoryz_tag');
                if ($tags && !is_wp_error($tags)) {
                    $tag_links = array();
                    foreach ($tags as $tag) {
                        $tag_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=ourstoryz&ourstoryz_tag=' . $tag->slug)) . '">' . $tag->name . '</a>';
                    }
                    echo implode(', ', $tag_links);
                } else {
                    echo 'No tags';
                }
                break;
            default:
                // Handle other column names if needed
                break;
        }
    }


    // Make Admin Columns Sortable
    function sortable_custom_post_columns($columns)
    {
        $columns['ourstoryz_category'] = 'ourstoryz_category';
        $columns['ourstoryz_tag'] = 'ourstoryz_tag';
        return $columns;
    }

    function save_post_screenshot()
    {
        if (!isset($_POST['post_id']) || !isset($_POST['screenshot_data'])) {
            wp_send_json_error();
        }

        $post_id = $_POST['post_id'];
        $screenshot_data = $_POST['screenshot_data'];

        // Get upload directory
        $upload_dir = wp_upload_dir();

        // Delete previous thumbnail attachment if it exists
        $previous_thumbnail_id = get_post_thumbnail_id($post_id);
        if ($previous_thumbnail_id) {
            wp_delete_attachment($previous_thumbnail_id, true); // Delete the attachment permanently
        }

        // Save new screenshot data to a file
        $screenshot_path = $upload_dir['path'] . '/screenshot-' . $post_id . '.png';
        file_put_contents($screenshot_path, base64_decode(str_replace('data:image/png;base64,', '', $screenshot_data)));

        // Create attachment for the new screenshot
        $attachment = array(
            'post_mime_type' => 'image/png',
            'post_title' => 'Screenshot ' . $post_id,
            'post_content' => '',
            'post_status' => 'inherit'
        );

        // Insert the attachment into the media library
        $attach_id = wp_insert_attachment($attachment, $screenshot_path, $post_id);

        // Set post thumbnail
        if (!is_wp_error($attach_id)) {
            set_post_thumbnail($post_id, $attach_id);
        }

        // Return the URL to the saved screenshot
        $screenshot_url = $upload_dir['url'] . '/screenshot-' . $post_id . '.png';
        wp_send_json_success($screenshot_url);
    }

    function cropped_screenshot()
    {
        if (!isset($_POST['post_id']) || !isset($_POST['screenshot_data'])) {
            wp_send_json_error();
        }

        $post_id = $_POST['post_id'];
        $screenshot_data = $_POST['screenshot_data'];

        // Get upload directory
        $upload_dir = wp_upload_dir();

        // Delete previous cropped screenshot if it exists
        $previous_screenshot_path = $upload_dir['path'] . '/screenshot-crop' . $post_id . '.png';
        if (file_exists($previous_screenshot_path)) {
            unlink($previous_screenshot_path); // Delete the file
        }

        // Save new cropped screenshot data to a file
        $screenshot_path = $upload_dir['path'] . '/screenshot-crop' . $post_id . '.png';
        file_put_contents($screenshot_path, base64_decode(str_replace('data:image/png;base64,', '', $screenshot_data)));

        // Store the URL of the cropped screenshot in post meta
        $screenshot_url = $upload_dir['url'] . '/screenshot-crop' . $post_id . '.png';
        add_post_meta($post_id, '_thumbURL', $screenshot_url);

        wp_send_json_success($screenshot_path);
    }



    public function register_custom_endpoints()
    {
        register_rest_route(
            'custom/v1',
            '/ourstoryz/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'custom_rest_api_get_ourstoryz_posts'),
                'permission_callback' => array($this, 'jwt_authenticate'),
                'args' => array(
                    'category' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'tag' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'templateId' => array(
                        'sanitize_callback' => 'absint',
                    ),
                    'templateName' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'page' => array(
                        'sanitize_callback' => 'absint',
                    ),
                    'per_page' => array(
                        'sanitize_callback' => 'absint',
                    ),
                ),
            )
        );
    }

    public function jwt_authenticate($request)
    {
        $user = wp_get_current_user();
        if ($user->exists()) {
            return true;
        }
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }

    public function custom_jwt_add_custom_claims($data, $user)
    {
        $data['user'] = array(
            'id' => $user->ID,
            'email' => $user->user_email,
            'displayName' => $user->display_name,
        );
        return $data;
    }



    // Function to generate JWT token
    function generate_jwt_token_ajax()
    {
        // Get current user's username
        $current_user = wp_get_current_user();
        $username = $current_user->user_login;

        // Get the password from the AJAX request
        if (!isset($_POST['password'])) {
            wp_send_json_error('Password is required');
        }
        $password = sanitize_text_field($_POST['password']);

        // Create request body
        $body = array(
            'username' => $username,
            'password' => $password
        );

        // Log the request body for debugging
        error_log('Request Body: ' . print_r($body, true));
        $url = get_site_url() . '/wp-json/jwt-auth/v1/token/';
        // Send POST request to generate JWT token
        $response = wp_remote_post(
            $url,
            array(
                'body' => json_encode($body),
                'headers' => array(
                    'Content-Type' => 'application/json'
                )
            )
        );

        // Log the response for debugging
        error_log('Response: ' . print_r($response, true));

        if (is_wp_error($response)) {
            // Handle error
            $error_message = $response->get_error_message();
            wp_send_json_error($error_message);
        } else {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            // Log the decoded response body for debugging
            error_log('Decoded Response Body: ' . print_r($data, true));

            if (isset($data['token'])) {
                // Token generated successfully, return it
                wp_send_json(array('success' => true, 'token' => $data['token']));
            } else {
                // Token generation failed, check for specific error message
                $error_message = isset($data['message']) ? $data['message'] : 'Token generation failed';
                wp_send_json_error($error_message);
            }
        }

        wp_die(); // Always use wp_die() at the end of AJAX functions
    }


    function custom_rest_api_get_ourstoryz_posts($request)
    {
        // Retrieve query parameters
        $category = $request->get_param('category');
        $tag = $request->get_param('tag');
        $id = $request->get_param('templateId');
        $name = $request->get_param('templateName');
        $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;
        $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : 10;

        // Prepare arguments for WP_Query based on parameters
        $args = array(
            'post_type' => 'ourstoryz',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $page,
        );

        // Add taxonomy filters if provided
        if ($category) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'ourstoryz_category',
                    'field' => 'slug',
                    'terms' => $category,
                ),
            );
        }

        if ($tag) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'ourstoryz_tag',
                    'field' => 'slug',
                    'terms' => $tag,
                ),
            );
        }

        // Add post ID or name filter if provided
        if ($id) {
            $args['p'] = $id; // Filter by post ID
        }

        if ($name) {
            $args['name'] = $name; // Filter by post name (slug)
        }

        // Perform WP_Query to retrieve posts
        $query = new WP_Query($args);
        $posts = array();

        // Loop through query results and build response data
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();

                // Retrieve custom meta fields
                $thumb_url = get_post_meta($post_id, '_thumbURL', true);

                // Retrieve post tags and categories
                $post_tags = wp_get_post_terms($post_id, 'ourstoryz_tag', array('fields' => 'names'));
                $post_categories = wp_get_post_terms($post_id, 'ourstoryz_category');
                $category_names = array();
                foreach ($post_categories as $category) {
                    $category_names[] = $category->name;
                }

                // Build post item array
                $post_item = array(
                    'templateId' => $post_id,
                    'templateName' => get_the_title(),
                    'fullImage' => get_the_post_thumbnail_url($post_id, 'thumbnail'),
                    'thumbnail' => $thumb_url,
                    'tags' => $post_tags,
                    'designer' => get_the_author_meta('display_name', $query->post_author),
                    'categories' => $category_names,
                );

                // Add post item to posts array using post ID as key
                $posts[] = $post_item;
            }
            wp_reset_postdata();

            // Prepare pagination information
            $total_posts = $query->found_posts;
            $total_pages = $query->max_num_pages;
            $current_page = max(1, $page);
            update_option('ourstoryz_is_updated', false);


            // Return the response
            $response = new WP_REST_Response(
                array(
                    'posts' => $posts,
                    'total' => $total_posts,
                    'pages' => $total_pages,
                    'current_page' => $current_page,
                    'per_page' => $per_page,

                ),
                200
            );
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');

            return $response;
        }
    }



    // Random value get rest api

    function update_is_updated_flag($post_id, $post, $update)
    {
        // Check if this is a post update
        if (!$update) {
            return;
        }

        // Get the post object
        $post_object = get_post($post_id);

        // Check if the updated post is of the 'ourstoryz' post type
        if ($post_object->post_type === 'ourstoryz') {
            // Update the is_updated flag in the options table
            update_option('ourstoryz_is_updated', true);
        }
    }

    function is_updated_check()
    {
        register_rest_route(
            'ourstoryz/v1',
            '/is_updated/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_is_updated'),
                'permission_callback' => '__return_true', // Adjust permissions as needed
            )
        );
    }

    function get_is_updated()
    {
        // Retrieve the status of the 'ourstoryz_is_updated' flag
        $is_updated = get_option('ourstoryz_is_updated', false);
        $is_updated = filter_var($is_updated, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        // Ensure the value is strictly a boolean
        $is_updated = ($is_updated === null) ? false : $is_updated;

        // Create the response
        $response = new WP_REST_Response(
            array(
                'is_updated' => $is_updated,
            ),
            200
        );

        // Set headers to prevent caching
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        return $response;
    }



    function save_custom_data()
    {
        if (isset($_POST['value'])) {
            $value = $_POST['value'];
            $existing_value = get_option('default_template');

            if ($existing_value !== false) {
                update_option('default_template', $value);
                echo 'Data updated successfully!';
            } else {
                add_option('default_template', $value);
                echo 'Data added successfully!';
            }
        }
        wp_die();
    }

    function update_google_maps_api_key_callback() {
        if (!isset($_POST['google_maps_api_key']) || empty($_POST['google_maps_api_key'])) {
            wp_send_json_error(['message' => 'API key cannot be empty.']);
        } else {
            $google_maps_api_key = sanitize_text_field($_POST['google_maps_api_key']);
            update_option('google_maps_api_key', $google_maps_api_key);
            wp_send_json_success(['message' => 'API key updated successfully!']);
        }
    }
    
}
