<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Vloops_Wp_Plugin
 * @subpackage Vloops_Wp_Plugin/admin/partials
 */

function vloops_wp_plugin_options_page_cb() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

	$image_url = plugins_url('../images/viral-loops-logo(full)-1-1.svg', __FILE__ );
    ?>

    <div class="wrap vl-admin">
		<img style="height: 35px;" src="<?php echo $image_url ?>" />
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<div class="section-subheader">
			<?php printf( __( 'Start your Viral Loops Campaign %s here%s.', 'vloops_wp_plugin' ), '<a target="_blank" href="https://app.viral-loops.com/?utm_source=WP_Plugin">', '</a>' ); ?>
		</div>

		<div class="metabox-holder">
			<div class="postbox">
				<h2 class="hndle"><?php _e( 'My Campaigns', 'vloops_wp_plugin' )?></h2>
				<div class="inside">
				<?php
				$campaigns = get_option( 'vl_campaigns' );
				if ( $campaigns !== false && !empty($campaigns) ) {
					?> 
					<p class="">
						<?php _e( 'You can choose which campaign to activate. Only one campaign can be active at a time.', 'vloops_wp_plugin' ); ?> 
					</p>
					<div class="campaigns" id="campaign_container">
					<?php
					foreach ($campaigns as $single_campaign) {
						//var_dump($single_campaign);
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
											<button class="camp-activate button-primary"><?php _e( 'Activate', 'vloops_wp_plugin' )?></button>
											<div class="button-divider"></div>
											<button class="button camp-remove"><?php _e( 'Remove', 'vloops_wp_plugin' )?></button>
										<?php } else { ?>
											<button class="button camp-deactivate"><?php _e( 'Deactivate', 'vloops_wp_plugin' )?></button>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>					
					<?php
					}
					?>
					</div>	
				<?php				
				} else {	
					?> 
					<div class="section-subheader">
						<?php _e( 'No campaigns found.', 'vloops_wp_plugin' )?>
					</div>
					<?php
				}
				?>		
					<div id="campaign_loading_new_state">
						<div class="campaign_new_state_loader">
						</div>
					</div>
					<div class="campaign-dialog-background"></div>
					<div id="campaign_delete_popup">
						<div class="campaign_delete_popup">
							<h3><?php _e( "Are you sure you'd like to delete this campaign?", 'vloops_wp_plugin' )?></h3>
							<p>
								<button id="camp_delete_cancel" class="button"><?php _e( 'Cancel', 'vloops_wp_plugin' )?></button>
								<button id="camp_delete" class="camp-delete button"><?php _e( 'Delete', 'vloops_wp_plugin' )?></button>
							</p>
						</div>
					</div>

				</div>		
			</div>
        </div>
		
		<div class="metabox-holder">
			<div class="section1 postbox">
				<h3 class="hndle section-header1"><?php _e( 'Add Campaign', 'vloops_wp_plugin' )?></h2>
				<div class="section-content1 inside">
					<p class="section-subheader1">
						<?php _e('Get the Secret API token of your campaign.', 'vloops_wp_plugin' ); ?>
					</p>
					<p>
						<img src="<?php echo plugins_url('../images/secretapitoken.jpg', __FILE__ ) ?>"/>
					</p>
					<table class="form-table" role="presentation">
						<tbody>
							<tr>
								<th scope="row" class="input-label"><?php _e( 'Secret API Token', 'vloops_wp_plugin' )?></th>
								<td>
									<input type="text" class="campaign-input" id="viral-loops-api-key" name="viral-loops-api-key" value="">
								</td>
							</tr>
						</tbody>
					</table>
					<button id="add_campaign" class="button"><?php _e( 'Add Campaign', 'vloops_wp_plugin' )?></button>
					<div class="loading-container">
						<div id="loading"></div>
					</div>
					<span id="add_campaign_notice"></span>
				</div>
			</div>
		</div>
		
    </div>
    <?php
}