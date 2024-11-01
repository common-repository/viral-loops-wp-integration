(function( $ ) {
	'use strict';
	
	//Add new Campaign
	$(document).on('click', '#add_campaign', function( event ) {
		event.preventDefault();
		$("#loading").show();
		$('#game-products').html('');
		var campaignToken = $('#viral-loops-api-key').val();
		$("#add_campaign").attr("disabled", "disabled");
		$.ajax({
			url: 'https://app.viral-loops.com/api/v2/template?apiToken=' + campaignToken,
			type: 'get',
			async: false,
			success: function( response ) {
				console.log('success');
				console.log(response);
				var isOk = true;
				if (response.message) {
					isOk = false;
					
					$("#loading").fadeOut(40);
					$("#add_campaign_notice").html(ajaxCall.not_found_message);
					$("#add_campaign_notice").animate({opacity : 1});
					$("#add_campaign").removeAttr("disabled");
					setTimeout(function(){
						$("#add_campaign_notice").animate({opacity : 0});								
					}, 4000);
				}
				
				if(isOk) {
					var responseCampaign = {campaignId: response.publicToken, campaignToken: campaignToken, campaignTitle: response.campaignName, campaignType: response.templateSlug};
					responseCampaign.active = false;
					$.ajax({
						url: ajaxCall.ajaxurl,
						type: 'post',
						dataType: 'json',
						async: false,
						data: {
							action: 'js_vloops_ajax_save_campaign',
							campaign: responseCampaign
						},
						success: function( res ) {
							$('#viral-loops-api-key').val('');
							$("#loading").fadeOut(40);
							$("#add_campaign_notice").html(res.message);
							$("#add_campaign_notice").animate({opacity : 1});
							$("#add_campaign").removeAttr("disabled");
							setTimeout(function(){
								$("#add_campaign_notice").animate({opacity : 0});								
							}, 4000);
							console.log(res);
							$.ajax({
								url: ajaxCall.ajaxurl,
								type: 'post',
								async: false,
								data: {
									action: 'js_vloops_ajax_reload_campaigns',
								},
								success: function( html ) {
									$("#campaign_container").html('');
									$('#campaign_container').append( html );
									$("#campaign_loading_new_state").hide();
								},
								error: function(error) {
									console.log('error');
									console.log(error);
								}
							});	
														
						},
						error: function(error) {
							console.log('error');
							console.log(error);
							$("#add_campaign").removeAttr("disabled");
						}
					});
				} else {
					$("#add_campaign").removeAttr("disabled");
				}
			},
			error: function( error ) {
				console.log(error);
				$("#loading").fadeOut(40);
				$("#add_campaign_notice").html(ajaxCall.error_message);
				$("#add_campaign_notice").animate({opacity : 1});
				$("#add_campaign").removeAttr("disabled");
				setTimeout(function(){
					$("#add_campaign_notice").animate({opacity : 0}); 								
				}, 4000);
			}
			
		});
			
	});	
	
	//Activate Campaign	
	$(document).on('click', '.camp-activate', function( event ) {
		event.preventDefault();
		$('#campaign_loading_new_state').show();
		var currentCampaignId = $(this).closest('.single-campaign-col-container').attr('data-id');
		$.ajax({
			url: ajaxCall.ajaxurl,
			type: 'post',
			async: false,
			data: {
				action: 'js_vloops_ajax_activate_campaign',
				campaign: currentCampaignId
			},
			success: function( html ) {
				$("#campaign_container").html('');
				$('#campaign_container').append( html );
				$("#campaign_loading_new_state").hide();
			},
			error: function(data) {
				console.log('error');
				console.log(data);
			}
		});
	});			
	
	//Deactivate Campaign	
	$(document).on('click', '.camp-deactivate', function( event ) {
		event.preventDefault();
		$('#campaign_loading_new_state').show();
		var currentCampaignId = $(this).closest('.single-campaign-col-container').attr('data-id');
		$.ajax({
			url: ajaxCall.ajaxurl,
			type: 'post',
			async: false,
			data: {
				action: 'js_vloops_ajax_deactivate_campaign',
				campaign: currentCampaignId
			},
			success: function( html ) {
				$("#campaign_container").html('');
				$('#campaign_container').append( html );
				$("#campaign_loading_new_state").hide();
			},
			error: function(data) {
				console.log('error');
				console.log(data);
			}
		});
	});
	
	//Delete Campaign Show Pop-up	
	$(document).on('click', '.camp-remove', function( event ) {
		event.preventDefault();
		var currentCampaignId = $(this).closest('.single-campaign-col-container').attr('data-id');
		$('#camp_delete').attr('data-id' , currentCampaignId);
		$('.campaign-dialog-background').show();
		$('#campaign_delete_popup').show();
		
	});
	
		
	//Delete Campaign Hide Pop-up	
	$(document).on('click', '#camp_delete_cancel', function( event ) {
		event.preventDefault();
		$('#camp_delete').attr('data-id' , '');
		$('.campaign-dialog-background').hide();
		$('#campaign_delete_popup').hide();
		
	});
	
	//Delete Campaign	
	$(document).on('click', '#camp_delete', function( event ) {
		event.preventDefault();
		$('#campaign_loading_new_state').show();
		var currentCampaignId = $(this).attr('data-id');
		$.ajax({
			url: ajaxCall.ajaxurl,
			type: 'post',
			async: false,
			data: {
				action: 'js_vloops_ajax_delete_campaign',
				campaign: currentCampaignId
			},
			success: function( html ) {
				console.log('delete success');
				//console.log(html);
				$('#camp_delete').attr('data-id' , '');
				$('.campaign-dialog-background').hide();
				$('#campaign_delete_popup').hide();
				$("#campaign_container").html('');
				$('#campaign_container').append( html );
				$("#campaign_loading_new_state").hide();

			},
			error: function(data) {
				console.log('error');
				console.log(data);
			}
		});
	});
	
	
//the end
})( jQuery );
