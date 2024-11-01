<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://viral-loops.com
 * @since      1.0.0
 *
 * @package    Vloops_Wp_Plugin
 * @subpackage Vloops_Wp_Plugin/includes
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
 * @package    Vloops_Wp_Plugin
 * @subpackage Vloops_Wp_Plugin/includes
 * @author     Viral Loops
 */
class Vloops_Wp_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $vloops_wp_plugin    The string used to uniquely identify this plugin.
	 */
	protected $vloops_wp_plugin;

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
	public function __construct() {
		if ( defined( 'VLOOPS_WP_PLUGIN_VERSION' ) ) {
			$this->version = VLOOPS_WP_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->vloops_wp_plugin = 'vloops-wp-plugin';

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
	 * - Vloops_Wp_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Vloops_Wp_Plugin_i18n. Defines internationalization functionality.
	 * - Vloops_Wp_Plugin_Admin. Defines all hooks for the admin area.
	 * - Vloops_Wp_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vloops-wp-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vloops-wp-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vloops-wp-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-vloops-wp-plugin-public.php';

		$this->loader = new Vloops_Wp_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Vloops_Wp_Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Vloops_Wp_Plugin_Admin( $this->get_vloops_wp_plugin(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_nopriv_js_vloops_ajax_save_campaign', $plugin_admin, 'vloops_ajax_save_campaign' );
		$this->loader->add_action( 'wp_ajax_js_vloops_ajax_save_campaign', $plugin_admin, 'vloops_ajax_save_campaign' );
		$this->loader->add_action( 'wp_ajax_nopriv_js_vloops_ajax_activate_campaign', $plugin_admin, 'vloops_ajax_activate_campaign' );
		$this->loader->add_action( 'wp_ajax_js_vloops_ajax_activate_campaign', $plugin_admin, 'vloops_ajax_activate_campaign' );
		$this->loader->add_action( 'wp_ajax_nopriv_js_vloops_ajax_deactivate_campaign', $plugin_admin, 'vloops_ajax_deactivate_campaign' );
		$this->loader->add_action( 'wp_ajax_js_vloops_ajax_deactivate_campaign', $plugin_admin, 'vloops_ajax_deactivate_campaign' );
		$this->loader->add_action( 'wp_ajax_nopriv_js_vloops_ajax_delete_campaign', $plugin_admin, 'vloops_ajax_delete_campaign' );
		$this->loader->add_action( 'wp_ajax_js_vloops_ajax_delete_campaign', $plugin_admin, 'vloops_ajax_delete_campaign' );
		$this->loader->add_action( 'wp_ajax_nopriv_js_vloops_ajax_reload_campaigns', $plugin_admin, 'vloops_ajax_reload_campaigns' );
		$this->loader->add_action( 'wp_ajax_js_vloops_ajax_reload_campaigns', $plugin_admin, 'vloops_ajax_reload_campaigns' );
		//Add Settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'vloops_wp_plugin_settings_init' );
		//Add Options Page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'vloops_wp_plugin_options_page' );
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'vl_gutenberg_block' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Vloops_Wp_Plugin_Public( $this->get_vloops_wp_plugin(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'vc_before_init', $plugin_public, 'vl_vc_before_init' );
		$this->loader->add_action( 'the_post', $plugin_public, 'is_elementor' );
		$this->loader->add_action( 'divi_extensions_init',  $plugin_public, 'vloops_initialize_extension' );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_vloops_wp_plugin() {
		return $this->vloops_wp_plugin;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
