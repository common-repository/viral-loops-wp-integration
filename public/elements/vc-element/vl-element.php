<?php
class VlElement {
	private $base;
	private $dropdown_title = '';
	private $widgets = array();

	public function __construct() {
		$this->base = 'vc_vl_element';
		add_shortcode( 'vc_vl_element', array( $this, 'render' ) );
		$campaigns = get_option( 'vl_campaigns' );
		$campaign_is_active = false;
		$widget_array_list = array(
			'the-milestone-referral' =>  [
				'Form Widget'           => 'embedForm',
				'Milestone Widget'      => 'milestoneWidget',
				'Referral Count Widget' => 'referralCountWidget'
			],
			'refer-a-friend' =>  [
				'Form Widget'  => 'embedForm',
				'Reward Stats' => 'rewardStats'
			],
			'the-leaderboard-giveaway' =>  [
				'Embed Form'    => 'embedForm2',
				'Popup Trigger' => 'popupTrigger'
			],
			'the-tempting-giveaway' =>  [
				'Embed Form'    => 'embedForm2',
				'Popup Trigger' => 'popupTrigger'
			],
			'the-startup-pre-launch' =>  [
				'Embed Form'    => 'embedForm2',
				'Popup Trigger' => 'popupTrigger'
			],
			'the-ecommerce-referral' =>  [],
			'online-to-offline' =>  [],
			'newsletter-referral' =>  [
				'Form Widget'      => 'embedForm',
				'Milestone Widget' => 'milestoneWidget',
				'Invitee Widget'   => 'inviteeForm'
			],
		);
		if($campaigns) {
			foreach ($campaigns as $single_campaign) {
				if ($single_campaign['active'] === 'true') {
					$campaign_is_active = true;
					$this->dropdown_title = esc_html__( 'Select the campaign widget that you want to add.', 'vloops_wp_plugin' );
					$this->widgets = $widget_array_list[$single_campaign['campaignType']];
					if(empty($this->widgets)) {
						$this->dropdown_title = esc_html__( 'Campaign type without widget.', 'vloops_wp_plugin' );
						add_action( 'vc_after_init', array( $this, 'remove_widget_type_dropdown') );
						break;
					}
					$this->widgets = array_merge( array('Select a widget type' => 'none'), $this->widgets );					
					break;
				}				
			}
		}
		
		if ($campaign_is_active === false) {
			add_action( 'vc_after_init', array( $this, 'remove_widget_type_dropdown') );
			$this->dropdown_title = esc_html__( 'No Active Campaigns found', 'vloops_wp_plugin' );
			$this->widget_class = 'hide-vl-dropdown';
		}
		
		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'element_title', array($this, 'add_title_field' ) );
		}
		
		if ( function_exists( 'vc_lean_map' ) ) {
			vc_lean_map( 'vc_vl_element', array($this, 'vcMap' ) );
		}

	}

	public function getBase() {
		return $this->base;
	}

	public function vcMap() {
		return array(
				'name'                      => 'Viral Loops',
				'base'                      => $this->getBase(),
				'category'                  => esc_html__( 'Viral Loops', 'vloops_wp_plugin' ),
				'icon'                      => 'vc-vl-icon',
				'allowed_container_element' => 'vc_row',
				'description'               => esc_html__( 'Add a campaign widget to your page', 'vloops_wp_plugin' ),
				'admin_enqueue_css'         => array( plugin_dir_url(__FILE__) . 'css/vc-element.css'),
				'params'                    => array(
					array(
						'type'        => 'element_title',
						'param_name'  => 'element_header',
						'heading'     => $this->dropdown_title,
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'widget_type',
						'value'       => $this->widgets,
					),						
				)
			);
	}

	public function render( $atts, $content = null ) {
		$args   = array(
			'widget_type'     => '',
		);
		$params = shortcode_atts( $args, $atts );
		$params['widget_type']  = ! empty( $params['widget_type'] ) ? $params['widget_type'] : $args['widget_type'];
		$html = $this->get_frontend_html( $params );

		return $html;
	}
	
	/**
	 * Gets html to be rendered on the front-end
	 *
	 * @param array $params array of parameters to pass to method
	 *
	 * @return html
	 */
	private function get_frontend_html( $params = array() ) {
		//HTML Content
		if ( is_array( $params ) && count( $params ) ) {
			extract( $params );
		}
		
		$campaigns = get_option( 'vl_campaigns' );
		$campaign_is_active = false;
		$campaign_loaded = false;
		$widget = '';
		if (!empty($widget_type)) {
			$widget = $widget_type;
		}
		
		$widget_list = [
			"the-milestone-referral"   => ["embedForm", "milestoneWidget", "referralCountWidget"],
			"refer-a-friend"           => ["embedForm", "rewardStats"],
			"the-leaderboard-giveaway" => ["embedForm2", "popupTrigger"],
			"the-tempting-giveaway"    => ["embedForm2", "popupTrigger"],
			"the-startup-pre-launch"   => ["embedForm2", "popupTrigger"],
			"the-ecommerce-referral"   => [],
			"online-to-offline"        => [],
			"newsletter-referral"      => ["embedForm", "milestoneWidget", "referralCountWidget"],
		];

		ob_start();
		if($campaigns) {
			foreach ($campaigns as $single_campaign) {
				if ($single_campaign['active'] === 'true') {
					$campaign_is_active = true;
					if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline') {
						$widget = '';
					}
					if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline' || in_array($widget, $widget_list[$single_campaign['campaignType']])) { ?>
						<div class="shortcode-container">
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
			
		<?php } else if ($campaign_loaded === false) { ?>
			<h6><?php _e( 'No widget selected or widget type does not exist for this campaign type. Please check element on the back-end.', 'vloops_wp_plugin' )?></h6>
		<?php	
		}			
		return ob_get_clean();

	}
	
	public function remove_widget_type_dropdown() {
		if( function_exists('vc_remove_param') ){ 
			vc_remove_param( $this->base , 'widget_type' ); 
		}
	}
	
	public function add_title_field() {
		return '';
	}

}

new VlElement;