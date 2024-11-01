// External Dependencies
import React, { Component } from 'react';


class PublicScript extends Component {
	static campaignId = '';
	
	constructor(props) {
		super(props);		
		this.vlScript = {};
		this.state = {
			widgetType: this.props.widgetType,
		}
	}
	
	componentDidMount(){	
		this.loadScript();
	}
	
	componentWillReceiveProps(nextProps) {
		// Check whether widget has changed
		if (nextProps.widgetType !== this.state.widgetType) {
			this.setState({widgetType: nextProps.widgetType}, function() {this.loadScript()});
		}
	}
	
	//Dynamically load script
	loadScript() {
		this.campaignId = this.props.campaignId;		
		this.vlScript = {
			'the-milestone-referral' : '!function(){var a=window.VL=window.VL||{};return a.instances=a.instances||{},a.invoked?void(window.console&&console.error&&console.error("VL snippet loaded twice.")):(a.invoked=!0,void(a.load=function(b,c,d){var e={};e.publicToken=b,e.config=c||{};var f=document.createElement("script");f.type="text/javascript",f.id="vrlps-js",f.defer=!0,f.src="https://app.viral-loops.com/client/vl/vl.min.js";var g=document.getElementsByTagName("script")[0];return g.parentNode.insertBefore(f,g),f.onload=function(){a.setup(e),a.instances[b]=e},e.identify=e.identify||function(a,b){e.afterLoad={identify:{userData:a,cb:b}}},e.pendingEvents=[],e.track=e.track||function(a,b){e.pendingEvents.push({event:a,cb:b})},e.pendingHooks=[],e.addHook=e.addHook||function(a,b){e.pendingHooks.push({name:a,cb:b})},e.$=e.$||function(a){e.pendingHooks.push({name:"ready",cb:a})},e}))}();var campaign=VL.load("'+ this.campaignId +'",{autoLoadWidgets:!0});',
			'refer-a-friend' : '!function(){var a=window.VL=window.VL||{};return a.instances=a.instances||{},a.invoked?void(window.console&&console.error&&console.error("VL snippet loaded twice.")):(a.invoked=!0,void(a.load=function(b,c,d){var e={};e.publicToken=b,e.config=c||{};var f=document.createElement("script");f.type="text/javascript",f.id="vrlps-js",f.defer=!0,f.src="https://app.viral-loops.com/client/vl/vl.min.js";var g=document.getElementsByTagName("script")[0];return g.parentNode.insertBefore(f,g),f.onload=function(){a.setup(e),a.instances[b]=e},e.identify=e.identify||function(a,b){e.afterLoad={identify:{userData:a,cb:b}}},e.pendingEvents=[],e.track=e.track||function(a,b){e.pendingEvents.push({event:a,cb:b})},e.pendingHooks=[],e.addHook=e.addHook||function(a,b){e.pendingHooks.push({name:a,cb:b})},e.$=e.$||function(a){e.pendingHooks.push({name:"ready",cb:a})},e}))}();var campaign=VL.load("'+ this.campaignId +'",{autoLoadWidgets:!0});',
			'the-leaderboard-giveaway' : '!function(a,b,c,d,t){var e,f=a.getElementsByTagName("head")[0];*//*if(!a.getElementById(c)){*//*if(e=a.createElement(b),e.id=c,e.setAttribute("data-vrlps-ucid",d),e.setAttribute("data-vrlps-version","2"), e.setAttribute("data-vrlps-template", t),e.src="https://app.viral-loops.com/popup_assets/js/vl_load_v2.min.js",window.ub){jQuery=null,$=null;var g=a.createElement(b);g.src="https://code.jquery.com/jquery-2.2.4.min.js",f.appendChild(g)}f.appendChild(e);var h=a.createElement("link");h.rel="stylesheet",h.type="text/css",h.href="https://app.viral-loops.com/static/vl-loader.css",f.appendChild(h);var i=a.createElement("div");i.id="vl-overlay",i.style.display="none";var j=a.createElement("div");j.id="vl-loader",i.appendChild(j),a.addEventListener("DOMContentLoaded",function(b){a.body.appendChild(i);for(var c=a.getElementsByClassName("vrlps-trigger"),d=0;d<c.length;d++)c[d].removeAttribute("href"),c[d].onclick=function(){a.getElementById("vl-overlay").style.display="block"};var e=a.querySelectorAll("[data-vl-widget=\'popupTrigger\']");[].forEach.call(e,function(b){var c=a.createElement("div");c.className="vl-embedded-cta-loader",b.appendChild(c)})})*//*}*//*}(document,"script","vrlps-js","'+ this.campaignId +'","ranking");',
			'the-ecommerce-referral' : '!function(){var a=window.VL=window.VL||{};return a.instances=a.instances||{},a.invoked?void(window.console&&console.error&&console.error("VL snippet loaded twice.")):(a.invoked=!0,void(a.load=function(b,c,d){var e={};e.publicToken=b,e.config=c||{};var f=document.createElement("script");f.type="text/javascript",f.id="vrlps-js",f.defer=!0,f.src="https://app.viral-loops.com/client/vl/vl.min.js";var g=document.getElementsByTagName("script")[0];return g.parentNode.insertBefore(f,g),f.onload=function(){a.setup(e),a.instances[b]=e},e.identify=e.identify||function(a,b){e.afterLoad={identify:{userData:a,cb:b}}},e.pendingEvents=[],e.track=e.track||function(a,b){e.pendingEvents.push({event:a,cb:b})},e.pendingHooks=[],e.addHook=e.addHook||function(a,b){e.pendingHooks.push({name:a,cb:b})},e.$=e.$||function(a){e.pendingHooks.push({name:"ready",cb:a})},e}))}();var campaign=VL.load("'+ this.campaignId +'",{autoLoadWidgets:!0});campaign.addHook("boot",function(){campaign.widgets.create("rewardingWidget",{container:"body",position:"bottom-left"}),campaign.widgets.create("rewardingWidgetTrigger",{container:"body",position:"bottom-left"})});',
			'the-tempting-giveaway' : '!function(a,b,c,d,t){var e,f=a.getElementsByTagName("head")[0];/*if(!a.getElementById(c)){*/if(e=a.createElement(b),e.id=c,e.setAttribute("data-vrlps-ucid",d),e.setAttribute("data-vrlps-version","2"), e.setAttribute("data-vrlps-template", t),e.src="https://app.viral-loops.com/popup_assets/js/vl_load_v2.min.js",window.ub){jQuery=null,$=null;var g=a.createElement(b);g.src="https://code.jquery.com/jquery-2.2.4.min.js",f.appendChild(g)}f.appendChild(e);var h=a.createElement("link");h.rel="stylesheet",h.type="text/css",h.href="https://app.viral-loops.com/static/vl-loader.css",f.appendChild(h);var i=a.createElement("div");i.id="vl-overlay",i.style.display="none";var j=a.createElement("div");j.id="vl-loader",i.appendChild(j),a.addEventListener("DOMContentLoaded",function(b){a.body.appendChild(i);for(var c=a.getElementsByClassName("vrlps-trigger"),d=0;d<c.length;d++)c[d].removeAttribute("href"),c[d].onclick=function(){a.getElementById("vl-overlay").style.display="block"};var e=a.querySelectorAll("[data-vl-widget=\'popupTrigger\']");[].forEach.call(e,function(b){var c=a.createElement("div");c.className="vl-embedded-cta-loader",b.appendChild(c)})})/*}*/}(document,"script","vrlps-js","'+ this.campaignId +'","sweepstake");',
			'the-startup-pre-launch' : '!function(a,b,c,d,t){var e,f=a.getElementsByTagName("head")[0];/*if(!a.getElementById(c)){*/if(e=a.createElement(b),e.id=c,e.setAttribute("data-vrlps-ucid",d),e.setAttribute("data-vrlps-version","2"), e.setAttribute("data-vrlps-template", t),e.src="https://app.viral-loops.com/popup_assets/js/vl_load_v2.min.js",window.ub){jQuery=null,$=null;var g=a.createElement(b);g.src="https://code.jquery.com/jquery-2.2.4.min.js",f.appendChild(g)}f.appendChild(e);var h=a.createElement("link");h.rel="stylesheet",h.type="text/css",h.href="https://app.viral-loops.com/static/vl-loader.css",f.appendChild(h);var i=a.createElement("div");i.id="vl-overlay",i.style.display="none";var j=a.createElement("div");j.id="vl-loader",i.appendChild(j),a.addEventListener("DOMContentLoaded",function(b){a.body.appendChild(i);for(var c=a.getElementsByClassName("vrlps-trigger"),d=0;d<c.length;d++)c[d].removeAttribute("href"),c[d].onclick=function(){a.getElementById("vl-overlay").style.display="block"};var e=a.querySelectorAll("[data-vl-widget=\'popupTrigger\']");[].forEach.call(e,function(b){var c=a.createElement("div");c.className="vl-embedded-cta-loader",b.appendChild(c)})})/*}*/}(document,"script","vrlps-js","'+ this.campaignId +'","waitlist");',
			'online-to-offline' : 
			'!function(){var a=window.VL=window.VL||{};return a.instances=a.instances||{},a.invoked?void(window.console&&console.error&&console.error("VL snippet loaded twice.")):(a.invoked=!0,void(a.load=function(b,c,d){var e={};e.publicToken=b,e.config=c||{};var f=document.createElement("script");f.type="text/javascript",f.id="vrlps-js",f.defer=!0,f.src="https://app.viral-loops.com/client/vl/vl.min.js";var g=document.getElementsByTagName("script")[0];return g.parentNode.insertBefore(f,g),f.onload=function(){a.setup(e),a.instances[b]=e},e.identify=e.identify||function(a,b){e.afterLoad={identify:{userData:a,cb:b}}},e.pendingEvents=[],e.track=e.track||function(a,b){e.pendingEvents.push({event:a,cb:b})},e.pendingHooks=[],e.addHook=e.addHook||function(a,b){e.pendingHooks.push({name:a,cb:b})},e.$=e.$||function(a){e.pendingHooks.push({name:"ready",cb:a})},e}))}();var campaign=VL.load("'+ this.campaignId +'",{autoLoadWidgets:!0});campaign.addHook("boot",function(){campaign.widgets.create("rewardingWidget",{container:"body",position:"bottom-left"}),campaign.widgets.create("rewardingWidgetTrigger",{container:"body",position:"bottom-left"})});',
			'newsletter-referral' : '!function(){var a=window.VL=window.VL||{};return a.instances=a.instances||{},a.invoked?void(window.console&&console.error&&console.error("VL snippet loaded twice.")):(a.invoked=!0,void(a.load=function(b,c,d){var e={};e.publicToken=b,e.config=c||{};var f=document.createElement("script");f.type="text/javascript",f.id="vrlps-js",f.defer=!0,f.src="https://app.viral-loops.com/client/vl/vl.min.js";var g=document.getElementsByTagName("script")[0];return g.parentNode.insertBefore(f,g),f.onload=function(){a.setup(e),a.instances[b]=e},e.identify=e.identify||function(a,b){e.afterLoad={identify:{userData:a,cb:b}}},e.pendingEvents=[],e.track=e.track||function(a,b){e.pendingEvents.push({event:a,cb:b})},e.pendingHooks=[],e.addHook=e.addHook||function(a,b){e.pendingHooks.push({name:a,cb:b})},e.$=e.$||function(a){e.pendingHooks.push({name:"ready",cb:a})},e}))}();var campaign=VL.load("'+ this.campaignId +'",{autoLoadWidgets:!0});'
		};	
		
		var scriptElements = document.getElementsByClassName('vl_shortcode_script');
		var requiredElement = scriptElements[0];
		requiredElement.innerHTML = '';
		
		//Create script element
		let script = document.createElement("script");
		let campaignType = this.props.campaignType;
		script.setAttribute("class", "vloops_public_script");
		//if campaignType is one of the following, we ignore this.vlScript and load the external script instead
		if(campaignType==='the-leaderboard-giveaway' || campaignType==='the-tempting-giveaway' || campaignType==='the-startup-pre-launch"') {
			//console.log(campaignType);
			script.src = "https://app.viral-loops.com/widgetsV2/core/loader.js";
		} else {
			script.text  = this.vlScript[campaignType];
		}
		script.async = false;
		//script.onload = () => this.scriptLoaded();
		var widgetElement = document.getElementById("vl_widget_element_"+this.state.widgetType);
		if(widgetElement) {	
			//console.log('deleting widgetElement '+ this.state.widgetType);		
			widgetElement.innerHTML = '';	
		}
		
		document.querySelectorAll('.vl-frame').forEach(function(a){
			a.remove();
		});
		
		//We remove any existing instances of the script
		var existingScriptElements = document.querySelectorAll(".vloops_public_script");	
		if (existingScriptElements.length) {
			//console.log('existingScriptElements');
			existingScriptElements.forEach(function(a){
				a.remove();
			});
			
		} //else {
			
			if(campaignType==='the-leaderboard-giveaway' || campaignType==='the-tempting-giveaway' || campaignType==='the-startup-pre-launch') {
				//document.getElementsByTagName("head")[0].appendChild(script);
				document.head.appendChild(script);
			} else {
				requiredElement.appendChild(script);
			}
		//}			

	}
	
	render() {
		return (
		  <div>
			<div id="vl_referrer_app_script_cont"></div>
		  </div>
		);		
	}
}

export default PublicScript;
