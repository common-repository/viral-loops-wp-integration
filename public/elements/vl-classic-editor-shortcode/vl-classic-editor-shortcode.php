<?php
/**
 * Viral Loops Classic Editor Button.
 *
 * @since 3.0.3
 */
class VloopsClassicEditor {
	public function __construct() {
		//editor button script
		add_action('admin_head', array($this,'editor_button'));
	}

	public function editor_button() {
	   if (!current_user_can('edit_posts') && ! current_user_can('edit_pages')) {
			return;
	   }
	   if (get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', array($this, 'add_editor_script'));
			add_filter('mce_buttons', array($this, 'register_button'));
		  
			add_action ( 'after_wp_tiny_mce', array($this, 'vloops_tinymce_extra_vars' ));
	   }
	}
	
	public function register_button($buttons) {
	   array_push($buttons, "|", 'vlbutton');
	   return $buttons;
	}
	
	public function add_editor_script($plugin_array) {
	   $plugin_array["vlbutton"] = plugins_url('/js/vl-classic-editor-shortcode.js', __FILE__);
	   return $plugin_array;
	}
	
	public function vloops_tinymce_extra_vars() {
		$widgets = array();
		$campaigns = get_option( 'vl_campaigns' );
		$campaign_is_active = false;
		$widget_array_list = array(
			'the-milestone-referral' =>  [
				'embedForm'           => 'Form Widget',
				'milestoneWidget'     => 'Milestone Widget',
				'referralCountWidget' => 'Referral Count Widget'
			],
			'refer-a-friend' =>  [
				'embedForm' => 'Form Widget',
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
					$widgets = $widget_array_list[$single_campaign['campaignType']];
					if(!empty($widgets)) {
						$widgets = array_merge( array('none' => 'Select a widget type'), $widgets );
					}			
					break;
				}				
			}
		}

		?>
		<script type="text/javascript">
			var tinyMCE_widgetList = <?php echo json_encode(
				$widgets
			); ?>;
			var tinyMCE_isCampaignActive = <?php echo $campaign_is_active ? 'true' : 'false'; ?>;
			var tinyMCE_object = <?php echo json_encode(
				array(
					'button_title' => esc_html__('Viral Loops', 'vloops_wp_plugin'),
					'image_url' => plugins_url('/images/viral-loops-logo.svg', __FILE__ ),
					'no_active_campaigns_message' => esc_html__('No Active Campaigns found.', 'vloops_wp_plugin'),
					'no_widget_message' => esc_html__('Campaign type without widget.', 'vloops_wp_plugin'),
				)
			);
			?>;
		</script><?php
	}

}

new VloopsClassicEditor;