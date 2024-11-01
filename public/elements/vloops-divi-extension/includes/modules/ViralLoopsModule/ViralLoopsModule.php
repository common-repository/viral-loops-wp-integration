<?php

class VLOOPSDE_ViralLoopsModule extends ET_Builder_Module {

	public $slug       = 'vloopsde_vloops_module';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => '',
		'author'     => 'Viral Loops',
		'author_uri' => '',
	);
	private $dropdown_title = '';
	private $widgets = array();
	private $fields_array = array();	
	private $vl_widget_type = array();
	
	
	public function init() {
		add_action( 'rest_api_init', array($this, 'vloops_register_campaigns_route') );
		wp_enqueue_style( 'vl_divi', plugin_dir_url( __FILE__ ) . 'css/vl_divi.css', array(), time(), 'all' );
		
		$this->name = esc_html__( 'Viral Loops', 'vloops_wp_plugin' );
		$this->vl_widget_type = array(
			'label'           => esc_html__( 'Widget Type', 'vloops_wp_plugin' ),
			'type'            => 'select',
			'options'         => array(),
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Select the campaign widget that you want to add.', 'vloopsde-vloops-divi-extension' ),
			'toggle_slug'     => 'main_content',
			'show_if'         => array(),
		);
		
		$this->vl_title_control = array(
			'label'           => esc_html__( 'Viral Loops title control', 'vloops_wp_plugin' ),
			'type'            => 'text',
			'id'              => 'vl_title_control',
			'default'         => 'vl_title_control_text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Viral Loops title.', 'vloopsde-vloops-divi-extension' ),
			'toggle_slug'     => 'main_content',
		);
		
		$campaigns = get_option( 'vl_campaigns' );
		$campaign_is_active = false;
		$widget_array_list = array(
			'the-milestone-referral' =>  [
				'embedForm'           => 'Form Widget',
				'milestoneWidget'     => 'Milestone Widget',
				'referralCountWidget' => 'Referral Count Widget'
			],
			'refer-a-friend' =>  [
				'embedForm'   => 'Form Widget',
				'rewardStats' => 'Reward Stats'
			],
			'the-leaderboard-giveaway' =>  [
				'embedForm2'   => 'Embed Form',
				'popupTrigger' => 'Popup Trigger'
			],
			'the-tempting-giveaway' =>  [
				'embedForm2'   => 'Embed Form',
				'popupTrigger' => 'Popup Trigger'
			],
			'the-startup-pre-launch' =>  [
				'embedForm2'   => 'Embed Form',
				'popupTrigger' => 'Popup Trigger'
			],
			'the-ecommerce-referral' =>  [],
			'online-to-offline' =>  [],
			'newsletter-referral' =>  [
				'embedForm'       => 'Form Widget',
				'milestoneWidget' => 'Milestone Widget',
				'inviteeForm'     => 'Invitee Widget'
			],				
		);
		if($campaigns) {
			foreach ($campaigns as $single_campaign) {
				if ($single_campaign['active'] === 'true') {
					$campaign_is_active = true;
					//$this->dropdown_title = esc_html__( 'Select the campaign widget that you want to add.', 'vloops_wp_plugin' );
					$this->widgets = $widget_array_list[$single_campaign['campaignType']];
					if(empty($this->widgets)) {
						$this->vl_title_control['label'] = esc_html__( 'Campaign type without widget.', 'vloops_wp_plugin' );
						$this->fields_array['vl_title_control'] = $this->vl_title_control;
						break;
					}
					$this->widgets = array_merge( array('none' => 'Select a widget type'), $this->widgets );
					$this->vl_widget_type['options'] = $this->widgets;
					$this->fields_array['vl_widget_type'] = $this->vl_widget_type;
					break;
				}
				
			}
		}
		
		if ($campaign_is_active === false) {
			$this->vl_title_control['label'] = esc_html__( 'No Active Campaigns found', 'vloops_wp_plugin' );
			$this->widget_class = 'hide-vl-dropdown';
			$this->fields_array['vl_title_control'] = $this->vl_title_control;
		}
	}

	public function get_fields() {
		return $this->fields_array;
	}

	public function render( $attrs, $content = null, $render_slug ) {
		$settings = $this->props;
		$vl_widget_type = '';
		if (isset($settings['vl_widget_type'])) {
			$vl_widget_type = $settings['vl_widget_type'];
		}
		
		$campaigns = get_option( 'vl_campaigns' );
		$campaign_is_active = false;
		$campaign_loaded = false;
		$widget = '';
		if (!empty($vl_widget_type)) {
			$widget = $vl_widget_type;
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
		
		ob_start();
		if($campaigns) {
			foreach ($campaigns as $single_campaign) {
				if ($single_campaign['active'] === 'true') {
					$campaign_is_active = true;
					if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline') {
						$widget = '';
					}
					//var_dump($single_campaign['campaignType']);
					if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline' || in_array($widget, $widget_list[$single_campaign['campaignType']])) {
						?>
						<div class="shortcode-container">
							<div id="vl_shortcode_script" >
							</div>
							<div>
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
			<h4><?php _e( 'No active campaigns found.', 'vloops_wp_plugin' )?></h4>
			
		<?php } else if ($campaign_loaded === false) {
			//echo $widget;
			//echo $single_campaign['campaignType'];
		?>
			<h6><?php _e( 'No widget selected or widget type does not exist for this campaign type. Please check element on the back-end.', 'vloops_wp_plugin' )?></h6>
		<?php	
		}
		return ob_get_clean();		
	}
	
	
	/**
	 * This function is where we register our routes for our example endpoint.
	 */
	public function vloops_register_campaigns_route() {
		// register_rest_route() handles more arguments but we are going to stick to the basics for now.
		register_rest_route( 'vl-routes', '/campaigns', array(
			// By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
			'methods'  => WP_REST_Server::READABLE,
			// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
			'callback' => 'prefix_get_endpoint_phrase',
			/*'permission_callback' => function() {
				return current_user_can( 'edit_posts' );
			},*/
		) );
	}
	 
	



}

new VLOOPSDE_ViralLoopsModule;
