/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { TextControl, Icon } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { widgetList } from '../../../js/element-vars.js';
import { withSelect } from '@wordpress/data';
import { Component } from '@wordpress/element';
import { vlLogo } from './images/vl-logo-mini.js';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object} [props]           Properties passed from the editor.
 * @param {string} [props.className] Class name generated for the block.
 *
 * @return {WPElement} Element to render.
 */
	
class Edit extends Component {
	constructor(props) {
		super(props);
		//const { attributes, setAttributes, className } = props;
		this.updateWidget = this.updateWidget.bind(this);
		this.state = {
			campaigns : [],
			active : false,
			campaignType : '',
			widgets: {},
			noWidgetCampaign : false,
			campaignedFetched : false,
		}
	}	

	componentDidMount(){
		apiFetch ( {
			path: '/vl-routes/campaigns'
		}).then(campaigns =>{
			this.setState({ campaigns: campaigns });
		}).then((response) => {	
			let noWidgetCampaign = false;
			if(this.state.campaigns){
				for(const campaign of this.state.campaigns) {
					if (campaign.active === 'true') {
						this.state.active = true;
						var widgets = widgetList[campaign.campaignType];
						if (Object.keys(widgets).length === 0 ) {
							this.props.setAttributes({
								widgetType: ''
							});
							noWidgetCampaign =true;
						}				
						this.setState({ campaignType: campaign.campaignType, widgets: widgets, noWidgetCampaign: noWidgetCampaign });	break;
					}
				}
			}
			
			this.setState({campaignedFetched: true});	
		});
	}
	
	updateWidget(e){
		this.props.setAttributes({
			widgetType: e.target.value
		});
	}

	render(props) { 
		const { attributes, setAttributes, className } = this.props;
		const that = this;
		if ( this.state.campaignedFetched === true ) {
			if ( this.state.campaigns && this.state.campaigns.length === 0 ) {
				return (
				<div className={className}>
					<div className="components-placeholder__label">
						<span className="block-editor-block-icon"><Icon icon={vlLogo}/></span>{ __( 'Viral Loops', 'vloops_wp_plugin' ) }
					</div>			
					<div class="components-placeholder__instructions">{ __( 'No campaigns found.', 'vloops_wp_plugin' ) }</div>
				</div>
				)
			}
			if(!this.state.active) {
				return (
				<div className={className}>
					<div className="components-placeholder__label">
						<span className="block-editor-block-icon"><Icon icon={vlLogo}/></span>Viral Loops
					</div>				
					<div class="components-placeholder__instructions">{ __( 'No active campaigns found.', 'vloops_wp_plugin' ) }</div>
				</div>
				)
			} else {
				if (this.state.noWidgetCampaign) {
					return (
					<div className={className}>
						<div className="components-placeholder__label">
							<span className="block-editor-block-icon"><Icon icon={vlLogo}/></span>Viral Loops
						</div>
						<div class="components-placeholder__instructions">{ __( 'Campaign type without widget.', 'vloops_wp_plugin' ) }</div>
					</div>
					)
				}
				return (
				<div className={className}>
					<div>
						<div className="components-placeholder__label">
							<span className="block-editor-block-icon"><Icon icon={vlLogo}/></span>Viral Loops
						</div>
						<div class="components-placeholder__instructions">{ __( 'Select the campaign widget that you want to add.', 'vloops_wp_plugin' ) }</div>
					</div>
					<div>
						<select onChange={this.updateWidget} value={attributes.widgetType}>
							<option value='none' key='none-selected' >
								{ __( 'Select a widget', 'vloops_wp_plugin' ) }
							</option>
							{
								Object.keys(that.state.widgets).map(function(key) {
									return (
										<option value={that.state.widgets[key]} key={that.state.widgets[key]}>
											{key}
										</option>
									)
								})
							}
						</select>				
					</div>
				</div>
				);
			}
		} else {
			return (
				<div className={className}>
					<div className="components-placeholder__label">
						<span className="block-editor-block-icon"><Icon icon={vlLogo}/></span>{ __( 'Viral Loops', 'vloops_wp_plugin' ) }
					</div>
				</div>
			);
		}
	}
}

export default Edit;