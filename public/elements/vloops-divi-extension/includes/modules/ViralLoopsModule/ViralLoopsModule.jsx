// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';
import { widgetList } from './element-vars.js';
import PublicScript from './PublicScript';

class ViralLoopsModule extends Component {

	static slug = 'vloopsde_vloops_module';
	static stufff;
	constructor(props) {
		super(props);
		this.state = {
			campaigns : [],
			active : false,
			campaignType : '',
			widgets: {},
			noWidgetCampaign : false,
			campaignFetched : false,
			campaignId: '',
			widgetType: this.props.vl_widget_type
		}
	}
	
	componentDidMount(){
		fetch(window.wpApiSettings.root + 'vl-routes/campaigns', {
			method: 'get',
		}).then(response => response.json(),)
		.then(campaigns => {
			//console.log(campaigns);
			this.setState({ campaigns: campaigns });
		}).then((response) => {	
			let noWidgetCampaign = false;
			let widgetType = this.props.vl_widget_type;
			if(this.state.campaigns){
				for(const campaign of this.state.campaigns) {
					if (campaign.active === 'true') {
						var widgets = widgetList[campaign.campaignType];
						if (Object.keys(widgets).length === 0 ) {
							widgetType = '';						
							noWidgetCampaign = true;
						}				
						this.setState({ 
							campaignType: campaign.campaignType, 
							widgets: widgets, 
							noWidgetCampaign: noWidgetCampaign, 
							active: true, 
							campaignId: campaign.campaignId, 
							widgetType: widgetType 
						});					
						break;
					}
				}
			}			
			this.setState({campaignFetched: true});	
		});
	}
	componentWillReceiveProps(nextProps) {
		// Check whether widget has changed
		if (nextProps.vl_widget_type !== this.state.widgetType) {
			this.setState({widgetType: nextProps.vl_widget_type});
		}
	}
	
	render() {
		if ( this.state.campaignFetched === true ) {
			if ( this.state.campaigns && this.state.campaigns.length === 0 ) {
				return (
				<div className={this.state.campaignType}>		
					<div className="components-placeholder__instructions">No campaigns found.</div>
				</div>
				)
			}
			if(!this.state.active) {
				return (
				<div className={this.state.campaignType}>			
					<div className="components-placeholder__instructions">No active campaigns found.55</div>
				</div>
				)
			} else {
				return (
				<div className="shortcode-container">
					<div>
					{(this.state.campaignType==='the-leaderboard-giveaway' || this.state.campaignType==='the-tempting-giveaway' || this.state.campaignType==='the-startup-pre-launch' ) ?
						((this.state.widgetType==='popupTrigger')?
							<form-widget mode='popup' ucid={this.state.campaignId}></form-widget>	
							:
							<form-widget ucid={this.state.campaignId}></form-widget>
						)
						:
						<div id={"vl_widget_element_"+this.state.widgetType} data-vl-widget={this.state.widgetType}></div>						
					}						
					</div>
					<div id="vl_shortcode_script" className="vl_shortcode_script">
					</div>
					<div>
						<PublicScript campaignType={this.state.campaignType} campaignId ={this.state.campaignId} widgetType={this.state.widgetType}/>
					</div>
											
				</div>
				
				);
			}
		} else {
			return (
				<div className={this.state.campaignType}>
					<div className="components-placeholder__label">
					</div>
				</div>
			);
		}
	}
}

export default ViralLoopsModule;
