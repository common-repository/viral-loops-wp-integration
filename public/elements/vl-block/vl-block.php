<?php
/**
 *  Vl Block
 * @package         vl-gutenberg
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function vl_gutenberg_vl_block_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "vl-gutenberg/vl-block" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'vl-gutenberg-vl-block-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);

	$editor_css = 'build/index.css';
	wp_register_style(
		'vl-gutenberg-vl-block-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'vl-gutenberg-vl-block-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'vl-gutenberg/vl-block', array(
		'editor_script'   => 'vl-gutenberg-vl-block-block-editor',
		'editor_style'    => 'vl-gutenberg-vl-block-block-editor',
		'style'           => 'vl-gutenberg-vl-block-block',
		'render_callback' => 'gutenberg_examples_dynamic_render_callback',
		'attributes'      => [
			'widgetType'=> [
				'type'=> 'string'
			],
			'referAFriendWidgetType'=> [
				'type'=> 'string'
			]
		],
	) );
}
add_action( 'init', 'vl_gutenberg_vl_block_block_init' );


/* Dynamic Block additional function */
function gutenberg_examples_dynamic_render_callback( $block_attributes, $content ) {

	$campaigns = get_option( 'vl_campaigns' );
	$campaign_is_active = false;
	$campaign_loaded = false;
	$widget = '';
	if (isset($block_attributes['widgetType'])) {
		$widget = $block_attributes['widgetType'];
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
				if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline') {
					$widget = '';
				}
				if($single_campaign['campaignType']=='the-ecommerce-referral' || $single_campaign['campaignType']=='online-to-offline' || in_array($widget, $widget_list[$single_campaign['campaignType']]) ) {
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
				$campaign_is_active = true;
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
	return ob_get_clean();
}


/**
 * This is our callback function that embeds our phrase in a WP_REST_Response
 */
function prefix_get_endpoint_phrase() {
    // rest_ensure_response() wraps the data we want to return into a WP_REST_Response, and ensures it will be properly returned.
	$campaigns = get_option( 'vl_campaigns' );
	//$json_campaigns = json_encode($campaigns) ;
    return rest_ensure_response( $campaigns );
}
 
/**
 * This function is where we register our routes for our example endpoint.
 */
function prefix_register_example_routes() {
    // register_rest_route() handles more arguments but we are going to stick to the basics for now.
    register_rest_route( 'vl-routes', '/campaigns', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'prefix_get_endpoint_phrase',
    ) );
}
 
add_action( 'rest_api_init', 'prefix_register_example_routes' );
