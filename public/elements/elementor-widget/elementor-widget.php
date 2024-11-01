<?php
/**
 * Elementor Viral Loops Widget.
 *
 * @since 1.0.0
 */
class ElementorVloopsWidget extends \Elementor\Widget_Base {
	private $dropdown_title = '';
	private $widgets = array();

	public function __construct( $data = [], $args = null ) {		
		parent::__construct( $data, $args );
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
				'embedForm'           => 'Form Widget',
				'milestoneWidget'     => 'Milestone Widget',
				'inviteeForm'         => 'Invitee Widget'
			],			
		);
		if($campaigns) {
			foreach ($campaigns as $single_campaign) {
				if ($single_campaign['active'] === 'true') {
					$campaign_is_active = true;
					$this->dropdown_title = esc_html__( 'Select the campaign widget that you want to add.', 'vloops_wp_plugin' );
					$this->widgets = $widget_array_list[$single_campaign['campaignType']];
					
					if(empty($this->widgets)) {
						add_action( 'elementor/element/before_section_end', function($el_widget, $section_id, $args) {
							if( $el_widget->get_name() == 'viral_loops_widget' && $section_id == 'vl_content_section' ) 
								{
									$el_widget->remove_control('vl_widget_type');
								}
							}, 10, 3 
						);
						add_action( 'elementor/element/before_section_end', array($this, 'add_vl_title_control') , 10, 3 );
						break;
					}
					$this->widgets = array_merge( array('none' => 'Select a widget type'), $this->widgets );					
					break;
				}
				
			}
		}		
		if ($campaign_is_active === false) {
			$this->dropdown_title = esc_html__( 'No Active Campaigns found', 'vloops_wp_plugin' );
		}
	}	
	
	public function add_vl_title_control($el_widget, $section_id, $args) {
		if( $el_widget->get_name() == 'viral_loops_widget' && $section_id == 'vl_content_section') {	
			/* The method is called multiple times, so we need to check and add the control only the first time */
			$widget_controls = $el_widget->get_stack()['controls'];
			if(!isset($widget_controls['vloops_title_control'])){
				$el_widget->add_control(
					'vloops_title_control',
					[
						'label' => __( 'Campaign type without widget', 'vloops_wp_plugin' ),
						'type' => \Elementor\Controls_Manager::HEADING ,
					]
				);
			}
		}
	}
	
	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'viral_loops_widget';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Viral Loops', 'vloops_wp_plugin' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'elementor-vloops-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the  widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'vl_content_section',
			[
				'label' => __( 'Settings', 'vloops_wp_plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);		
		$this->add_control(
			'vl_widget_type',
			[
				'label' => __( 'Widget Type', 'vloops_wp_plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->widgets
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
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
		if($campaigns) {
			foreach ($campaigns as $single_campaign) {
				if ($single_campaign['active'] === 'true') {
					$campaign_is_active = true;
					if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline') {
						$widget = '';
					}
					if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline' || in_array($widget, $widget_list[$single_campaign['campaignType']])) {
						?>
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
			
		<?php } else if ($campaign_loaded === false) {
		?>
			<h6><?php _e( 'No widget selected or widget type does not exist for this campaign type. Please check element on the back-end.', 'vloops_wp_plugin' )?></h6>
		<?php	
		}
	}
	

}