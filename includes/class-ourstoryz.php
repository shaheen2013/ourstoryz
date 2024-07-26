<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mediusware.com
 * @since      1.0.0
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mediusware
 * @subpackage ourstoryz/includes
 * @author     Mediusware <zahid@mediusware.com>
 */
class ourstoryz
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var       ourstoryz_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('OURSTORYZ_VERSION')) {
			$this->version = OURSTORYZ_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ourstoryz';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - ourstoryz_Loader. Orchestrates the hooks of the plugin.
	 * - ourstoryz_i18n. Defines internationalization functionality.
	 * - ourstoryz_Admin. Defines all hooks for the admin area.
	 * - ourstoryz_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ourstoryz-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ourstoryz-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ourstoryz-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-ourstoryz-public.php';

		$this->loader = new ourstoryz_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the ourstoryz_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new ourstoryz_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new ourstoryz_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('init', $plugin_admin, 'custom_ourstoryz_post_type');
		$this->loader->add_action('init', $plugin_admin, 'create_signup_post_type');
		$this->loader->add_action('admin_menu', $plugin_admin, 'custom_ourstoryz_submenu_names');
		$this->loader->add_action('init', $plugin_admin, 'custom_ourstoryz_taxonomies');
		$this->loader->add_action('admin_menu', $plugin_admin, 'custom_ourstoryz_submenu_events');
		$this->loader->add_action('admin_menu', $plugin_admin, 'custom_ourstoryz_setting_page');
		$this->loader->add_action('wp_ajax_save_screenshot', $plugin_admin, 'save_post_screenshot');
		$this->loader->add_action('wp_ajax_cropped_screenshot', $plugin_admin, 'cropped_screenshot');
		$this->loader->add_action('manage_posts_custom_column', $plugin_admin, 'custom_post_table_column_content', 10, 2);
		$this->loader->add_filter('manage_posts_columns', $plugin_admin, 'custom_post_table_column_header');
		$this->loader->add_filter('jwt_auth_token_before_dispatch', $plugin_admin , 'custom_jwt_add_custom_claims', 10, 2);
		$this->loader->add_filter('manage_edit-ourstoryz_sortable_columns', $plugin_admin, 'sortable_custom_post_columns');
        $this->loader->add_action('wp_ajax_generate_jwt_token',$plugin_admin ,'generate_jwt_token_ajax');
		$this->loader->add_action('wp_ajax_nopriv_generate_jwt_token', $plugin_admin ,'generate_jwt_token_ajax'); // Allow for non-logged-in users as well
        $this->loader->add_action('save_post', $plugin_admin ,'update_is_updated_flag', 10, 3);
		// Rest api
		$this->loader->add_action('rest_api_init', $plugin_admin,  'register_custom_endpoints');
		$this->loader->add_action('rest_api_init',$plugin_admin,'is_updated_check');
		$this->loader->add_action('wp_ajax_save_custom_data',$plugin_admin, 'save_custom_data');
		 
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new ourstoryz_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return     ourstoryz_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
