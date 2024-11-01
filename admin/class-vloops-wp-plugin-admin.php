<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://viral-loops.com
 * @since      1.0.0
 *
 * @package    Vloops_Wp_Plugin
 * @subpackage Vloops_Wp_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vloops_Wp_Plugin
 * @subpackage Vloops_Wp_Plugin/admin
 * @author     Viral Loops
 */
class Vloops_Wp_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $vloops_wp_plugin    The ID of this plugin.
	 */
	private $vloops_wp_plugin;

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
	 * @param      string    $vloops_wp_plugin       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $vloops_wp_plugin, $version ) {

		$this->vloops_wp_plugin = $vloops_wp_plugin;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->vloops_wp_plugin, plugin_dir_url( __FILE__ ) . 'css/vloops-wp-plugin-admin.css', array(), $this->version, 'all' );
		//wp_enqueue_style( $this->vloops_wp_plugin, plugin_dir_url( __FILE__ ) . 'css/vloops-wp-plugin-admin.css', array(), time(), 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->vloops_wp_plugin, plugin_dir_url( __FILE__ ) . 'js/vloops-wp-plugin-admin.js', array( 'jquery' ), time() , false );
		
		wp_localize_script( $this->vloops_wp_plugin, 'ajaxCall', array('ajaxurl' => admin_url( 'admin-ajax.php' ), 'not_found_message' => __( 'No campaign found for this token.', 'vloops_wp_plugin' ), 'error_message' => __( 'An error has occured. Please try again later.', 'vloops_wp_plugin' )) );

	}
	
	/**
	 * Register Admin Settings
	 *
	 * @since    1.0.0
	 */
	public function vloops_wp_plugin_settings_init() {

		/**
		 * Register Settings
	 	 */
		register_setting( 'vloops_wp_plugin', 'vloops_wp_plugin_options' );

		/**
		 *  Add Section in Registered Settings
	 	 */
		add_settings_section(
			 'vloops_wp_plugin_section_main', // Section ID
			__( '', 'vloops_wp_plugin' ),	  // Section heading
			 'vloops_wp_plugin_section_main_cb', // Callback function
			 'vloops_wp_plugin'					 // Related registered option
			);

		/**
		 *  Add Fields in Sections
	 	 */
		// CDN URL
		add_settings_field(
			 'vloops_wp_plugin_field_cdn_url', 		// Field Name
			 __('CDN URL', 'vloops_wp_plugin'),		// Field Label
			 'vloops_wp_plugin_field_cdn_url_cb',	// Callback Function
			 'vloops_wp_plugin',					// Related registered option
			 'vloops_wp_plugin_section_main',		// Related added section
			 [
			 	'label_for'				=> 'vloops_wp_plugin_field_cdn_url',
			 	'class' 				=> 'vloops_wp_plugin_row',
			 	'vloops_wp_plugin_custom_data'	=> 'custom',
			 ]
		);
	}


	/**
	 * Register Settings Page
	 *
	 * @since    1.0.0
	 */

	public function vloops_wp_plugin_options_page() {
		add_menu_page(
			'Viral Loops Settings',
			'Viral Loops',
			'manage_options',
			'vloops_wp_plugin',
			'vloops_wp_plugin_options_page_cb',
			plugins_url( '/images/viral-loops-logo.svg', __FILE__ )
		);

	}

	/**
	 * Ajax function used to save a new campaign
	 *
	 * @since    1.0.0
	 */	
	public function vloops_ajax_save_campaign() {
		// Get attributes
		$campaign = $_POST['campaign'];
		$option_campaigns = 'vl_campaigns';
		$campaigns = get_option( $option_campaigns );
		// Define Query Arguments

		if ( $campaigns !== false ) {
			$campaign_array = $campaigns;			
			
			$campaign_exists = false;
			$success = true;
			foreach ($campaign_array as $single_campaign) {
				if ($single_campaign['campaignId'] === $campaign['campaignId']) {
					$campaign_exists = true;
					break;
				}
			}			
			if ($campaign_exists === false) {
				$campaign_array[] = $campaign;
				update_option( $option_campaigns, $campaign_array );				
			} 
		} else {	
			$campaign_array = [$campaign];

			// The option hasn't been created yet, so add it with $autoload set to 'no'.
			add_option( $option_campaigns, $campaign_array );
		}
		if ($campaign_exists === true) {
			$message = 'Campaign already exists!';
		} else {
			if ($success === true) {
				$message = 'Campaign successfully added!';
			} else {
				$message = 'Something went wrong.';
			}
		}
		$res = array('success' => $success, 'campaign_exists' => $campaign_exists, 'message' => $message);
		echo json_encode($res);
		die();
	}

	/**
	 * Ajax function used to reload campaigns
	 *
	 * @since    1.0.0
	 */	
	public function vloops_ajax_reload_campaigns() {
		// Get attributes
		$option_campaigns = 'vl_campaigns' ;
		$campaign_array = get_option( $option_campaigns );
		foreach ($campaign_array as $single_campaign) {
			?>
			<div class="single-campaign">
				<?php if ($single_campaign['active'] === "true") { ?>
					<div>
						<span id="active_label" class="active-label"><?php _e( 'Active', 'vloops_wp_plugin' )?></span>
					</div>
				<?php } ?>
					<div class="single-campaign-col-container" data-id="<?php echo $single_campaign['campaignId']; ?>">
						<div class="single-campaign-left-col">								
							<div class="campaign-title">
								<?php echo $single_campaign['campaignTitle']; ?>
							</div>
							<div class="campaign-type">
								<?php echo $single_campaign['campaignType']; ?>
							</div>
							<div class="campaign-token">
								<?php echo $single_campaign['campaignToken']; ?>
							</div>
						</div>
						<div class="single-campaign-right-col">
							<div>
								<?php if ($single_campaign['active'] === "false") { ?>
									<a class="button-primary camp-activate" href="#"><?php _e( 'Activate', 'vloops_wp_plugin' )?></a>
									<div class="button-divider"></div>
									<a class="button camp-remove" href="#"><?php _e( 'Remove', 'vloops_wp_plugin' )?></a>
								<?php } else { ?>
									<a class="button camp-deactivate" href="#"><?php _e( 'Deactivate', 'vloops_wp_plugin' )?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			<?php
		}
		die();
	}
	
	/**
	 * Ajax function used to activate a campaign
	 *
	 * @since    1.0.0
	 */	
	public function vloops_ajax_activate_campaign() {
		// Get attributes
		$campaign_id = $_POST['campaign'];
		$option_campaigns = 'vl_campaigns' ;
		$campaign_array = get_option( $option_campaigns );
		$campaign_type = '';
		
		
		$campaign_new_array = [];
		foreach ($campaign_array as $single_campaign) {
			if ($single_campaign['campaignId'] === $campaign_id) {
				$single_campaign['active'] = "true";
				$campaign_type = $single_campaign['campaignType'];
				
			} else {
				$single_campaign['active'] = "false";
			}
			$campaign_new_array[] = $single_campaign;
			?>
				<div class="single-campaign">
					<?php if ($single_campaign['active'] === "true") { ?>
						<div>
							<span id="active_label" class="active-label"><?php _e( 'Active', 'vloops_wp_plugin' )?></span>
						</div>
					<?php } ?>
						<div class="single-campaign-col-container" data-id="<?php echo $single_campaign['campaignId']; ?>">
							<div class="single-campaign-left-col">								
								<div class="campaign-title">
									<?php echo $single_campaign['campaignTitle']; ?>
								</div>
								<div class="campaign-type">
									<?php echo $single_campaign['campaignType']; ?>
								</div>
								<div class="campaign-token">
									<?php echo $single_campaign['campaignToken']; ?>
								</div>
							</div>
							<div class="single-campaign-right-col">
								<div>
									<?php if ($single_campaign['active'] === "false") { ?>
										<a class="button-primary camp-activate" href="#"><?php _e( 'Activate', 'vloops_wp_plugin' )?></a>
										<div class="button-divider"></div>
										<a class="button camp-remove" href="#"><?php _e( 'Remove', 'vloops_wp_plugin' )?></a>
									<?php } else { ?>
										<a class="button camp-deactivate" href="#"><?php _e( 'Deactivate', 'vloops_wp_plugin' )?></a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php
		}
		require( plugin_dir_path( __FILE__ ) . 'partials/vloops-wp-plugin-scripts.php');
		update_option( $option_campaigns, $campaign_new_array );
		update_option( 'vl_script', $vl_script );
		die();
	}
	
	/**
	 * Ajax function used to deactivate a campaign
	 *
	 * @since    1.0.0
	 */	
	public function vloops_ajax_deactivate_campaign() {
		// Get attributes
		$campaign_id = $_POST['campaign'];
		$option_campaigns = 'vl_campaigns' ;
		$campaign_array = get_option( $option_campaigns );
		$campaign_new_array = [];
		foreach ($campaign_array as $single_campaign) {
			if ($single_campaign['campaignId'] === $campaign_id) {
				$single_campaign['active'] = "false";
				
			}
			$campaign_new_array[] = $single_campaign;
			?>
				<div class="single-campaign">
					<?php if ($single_campaign['active'] === "true") { ?>
						<div>
							<span id="active_label" class="active-label"><?php _e( 'Active', 'vloops_wp_plugin' )?></span>
						</div>
					<?php } ?>
						<div class="single-campaign-col-container" data-id="<?php echo $single_campaign['campaignId']; ?>">
							<div class="single-campaign-left-col">								
								<div class="campaign-title">
									<?php echo $single_campaign['campaignTitle']; ?>
								</div>
								<div class="campaign-type">
									<?php echo $single_campaign['campaignType']; ?>
								</div>
								<div class="campaign-token">
									<?php echo $single_campaign['campaignToken']; ?>
								</div>
							</div>
							<div class="single-campaign-right-col">
								<div>
									<?php if ($single_campaign['active'] === "false") { ?>
										<a class="button-primary camp-activate" href="#"><?php _e( 'Activate', 'vloops_wp_plugin' )?></a>
										<div class="button-divider"></div>
										<a class="button camp-remove" href="#"><?php _e( 'Remove', 'vloops_wp_plugin' )?></a>
									<?php } else { ?>
										<a class="button camp-deactivate" href="#"><?php _e( 'Deactivate', 'vloops_wp_plugin' )?></a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php
		}
		
		update_option( $option_campaigns, $campaign_new_array );
		update_option( 'vl_script', '' );
		die();
	}
	
	/**
	 * Ajax function used to delete a campaign
	 *
	 * @since    1.0.0
	 */	
	public function vloops_ajax_delete_campaign() {
		// Get attributes
		$campaign_id = $_POST['campaign'];
		$option_campaigns = 'vl_campaigns' ;
		$campaign_array = get_option( $option_campaigns );
		$campaign_new_array = [];
		foreach ($campaign_array as $single_campaign) {
			if ($single_campaign['campaignId'] !== $campaign_id) {
				$campaign_new_array[] = $single_campaign;				
						
			?>
				<div class="single-campaign">
					<?php if ($single_campaign['active'] === "true") { ?>
						<div>
							<span id="active_label" class="active-label"><?php _e( 'Active', 'vloops_wp_plugin' )?></span>
						</div>
					<?php } ?>
						<div class="single-campaign-col-container" data-id="<?php echo $single_campaign['campaignId']; ?>">
							<div class="single-campaign-left-col">								
								<div class="campaign-title">
									<?php echo $single_campaign['campaignTitle']; ?>
								</div>
								<div class="campaign-type">
									<?php echo $single_campaign['campaignType']; ?>
								</div>
								<div class="campaign-token">
									<?php echo $single_campaign['campaignToken']; ?>
								</div>
							</div>
							<div class="single-campaign-right-col">
								<div>
									<?php if ($single_campaign['active'] === "false") { ?>
										<a class="button-primary camp-activate" href="#"><?php _e( 'Activate', 'vloops_wp_plugin' )?></a>
										<div class="button-divider"></div>
										<a class="button camp-remove" href="#"><?php _e( 'Remove', 'vloops_wp_plugin' )?></a>
									<?php } else { ?>
										<a class="button camp-deactivate" href="#"><?php _e( 'Deactivate', 'vloops_wp_plugin' )?></a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php
			}
		}
		update_option( $option_campaigns, $campaign_new_array );
		die();
	}	
	
	public function vl_gutenberg_block() {
		wp_enqueue_script('vl-gutenberg-block', plugin_dir_url(__FILE__) . '/js/gutenberg-block.js', array('wp-blocks','wp-editor'), true );
	}	
	
}


//Import Display Callbacks
require_once plugin_dir_path( __FILE__ ).'partials/vloops-wp-plugin-admin-display.php';