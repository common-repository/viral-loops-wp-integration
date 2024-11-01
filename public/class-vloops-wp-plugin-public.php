<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Vloops_Wp_Plugin
 * @subpackage Vloops_Wp_Plugin/public
 * @author     Viral Loops
 */
class Vloops_Wp_Plugin_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->vloops_wp_plugin = $plugin_name;
		$this->version = $version;
		add_shortcode( 'vl_form', array( $this, 'vl_form_shortcode') );
		require_once plugin_dir_path( __FILE__ ) . 'elements/vl-block/vl-block.php';
		require_once plugin_dir_path( __FILE__ ) . 'elements/vl-classic-editor-shortcode/vl-classic-editor-shortcode.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vloops_Wp_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vloops_Wp_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->vloops_wp_plugin, plugin_dir_url( __FILE__ ) . 'css/vloops-wp-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vloops_Wp_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vloops_Wp_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $post;
		if($post) {
			if(has_shortcode( $post->post_content, 'vl_form')){
				wp_register_script( $this->vloops_wp_plugin, plugin_dir_url( __FILE__ ) . 'js/vloops-wp-plugin-public.js', array( 'jquery' ), $this->version, false );
			}
		}
		//if ( ! defined('ET_FB_ENABLED') && ! defined('ET_BUILDER_LOAD_ON_AJAX') ) {
			//Load script on header or footer, depending on the campaign type
			$campaigns = get_option( 'vl_campaigns' );
			if($campaigns) {
				foreach ($campaigns as $single_campaign) {
					if ($single_campaign['active'] === 'true') {
						if($single_campaign['campaignType']=='the-leaderboard-giveaway' || $single_campaign['campaignType']=='the-tempting-giveaway' || $single_campaign['campaignType']=='the-startup-pre-launch') {
							add_action('wp_head', array( $this, 'vl_front_script' ));
						} else {
							add_action('wp_footer', array( $this, 'vl_front_script' ));
						}
						break;
					}
				}
			}
			//add_action('wp_footer', array( $this, 'vl_front_script' ));
		//}
			
			
	}
	
	/**
	 * Globally load VL script on the front-end
	 *
	 * @since    1.0.0
	 */		
	public function vl_front_script() {
		$vl_script = get_option( 'vl_script' );
		if($vl_script !== false && !empty($vl_script)) {	
			echo $vl_script;
		}
	}
	
	
	public function my_scripts_method2() {
		
		wp_enqueue_script( 'newscript', plugin_dir_url( __FILE__ ) . 'js/newsletter-referral.js', array( '' ) );
	}
	
	/**
	 * Shortcode displaying the VL widgets on the front-end
	 *
	 * @since    1.0.0
	 */		
	public function vl_form_shortcode($attr) {
		// For a future version whith multiple active campaigns
		/*if(isset($attr['campaign_id'])){
			$campaign_id = $attr['campaign_id'];
		} else {
			$campaign_id = '';
		}*/
		$widget = '';
		if(isset($attr['widget'])){
			$widget = $attr['widget'];
		}
		
		$widget_list = [
			"the-milestone-referral"   => ["embedForm", "milestoneWidget", "referralCountWidget"],
			"refer-a-friend"           => ["embedForm", "rewardStats"],
			"the-leaderboard-giveaway" => ["embedForm2", "popupTrigger"],
			"the-tempting-giveaway"    => ["embedForm2", "popupTrigger"],
			"the-startup-pre-launch"   => ["embedForm2", "popupTrigger"],
			"the-ecommerce-referral"   => [],
			"online-to-offline"        => [],
			"newsletter-referral"      => ["embedForm", "milestoneWidget", "inviteeForm"],
		];
	
		$option_name = 'vl_campaigns' ;
		$campaigns = get_option( $option_name );
		$campaign_is_active = false;
		$campaign_loaded = false;
		ob_start();
		if($campaigns) {
			foreach ($campaigns as $single_campaign) {
				if ($single_campaign['active'] === 'true') {
					$campaign_is_active = true;
					if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline') {
						$widget = '';
					}
					
					if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline' || in_array($widget, $widget_list[$single_campaign['campaignType']]) ) {		
					?>
						<div class="shortcode-container">
							<div class="">
								<?php if($single_campaign['campaignType']=='the-leaderboard-giveaway' || $single_campaign['campaignType']=='the-tempting-giveaway' || $single_campaign['campaignType']=='the-startup-pre-launch') { ?>
									<?php if ($widget === 'popupTrigger') { ?>
										<form-widget mode='popup' ucid='<?php echo $single_campaign['campaignId']; ?>'></form-widget>
									<?php } else { ?>
										<form-widget ucid='<?php echo $single_campaign['campaignId']; ?>'></form-widget>
									<?php } ?>
								<?php } else { ?>
									<div data-vl-widget="<?php echo $widget ?>"></div>
								<?php } ?>								
							</div>							
						</div>				
					<?php

						$campaign_loaded = true;
					}					
					break;
				}				
			} 
		}
		if ($campaign_is_active === false) {
		?>
			<h4><?php _e( 'No active campaigns found.', 'vloops_wp_plugin' );?></h4>
			
		<?php }  else if ($campaign_loaded === false) { ?>
		<h6><?php _e( 'No widget selected or widget type does not exist for this campaign type. Please check element on the back-end.', 'vloops_wp_plugin' )?></h6>
		<?php
		}

		return ob_get_clean();
	}
	
	
	/**
	 *  Load Visual Composer element
	 *
	*/	 
	public function vl_vc_before_init() {
		require_once( plugin_dir_path( __FILE__ ) . '/elements/vc-element/vl-element.php' );
	}

	/**
	 *  Elementor Widget
	 *
	*/
	public function is_elementor(){
		global $post;
		if(did_action( 'elementor/loaded' )) {
			//if(\Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID)) {
				wp_enqueue_style( 'vloops_elementor_css', plugin_dir_url( __FILE__ ) . '/elements/elementor-widget/css/vloops-wp-elementor-preview.css', array(), time(), 'all' );
				add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'vl_load_admin_css') );
				$this->vl_load_elementor_widget();

			//}
		}
	}
	
	public function vl_load_elementor_widget() {
		require_once( plugin_dir_path( __FILE__ ) . '/elements/elementor-widget/elementor-widget.php' );
		$vl_widget = new ElementorVloopsWidget;
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( $vl_widget );
	}
	
	public function vl_load_admin_css() {
		wp_enqueue_style( 'vloops_elementor_admin_css', plugin_dir_url( __FILE__ ) . '/elements/elementor-widget/css/vloops-wp-elementor-admin.css', array(), time(), 'all' );
	}
	
	public function vloops_initialize_extension() {
		//require_once plugin_dir_path( __FILE__ ) . '/elements/divi-module/includes/DiviModule.php';
		require_once plugin_dir_path( __FILE__ ) . '/elements/vloops-divi-extension/includes/VloopsDiviExtension.php';
	}



}
