(function() {
	var widgetList = [];
    tinymce.PluginManager.add('vlbutton', function( editor, url ) {
        editor.addButton( 'vlbutton', {
            image: tinyMCE_object.image_url,
			tooltip: tinyMCE_object.button_title,
            onclick: function() {
				if(widgetList.length == 0) {
					Object.keys(tinyMCE_widgetList).forEach(function(key,index) {
						widgetList.push({text:tinyMCE_widgetList[key], value: key});
					});
				}
				var paramList;
				var paramText;
				var paramBody = [];
				if(tinyMCE_isCampaignActive == true) {
					console.log(tinyMCE_widgetList);
					if (Object.keys(tinyMCE_widgetList).length > 0) {
						paramList = {
							type   : 'listbox',
							name   : 'vllistbox',
							label  : 'Widget Type',
							values : widgetList
						};
						paramText = '';
						paramBody.push(paramList);
					} else {
						paramList = '';
						paramText = {
							type   : 'container',
							name   : 'container',
							label  : '',
							html   : tinyMCE_object.no_widget_message
						}
						paramBody.push(paramText);
					}
				} else {
					paramList = '';
					paramText = {
						type   : 'container',
						name   : 'noActiveCampaigns',
						label  : '',
						html   : tinyMCE_object.no_active_campaigns_message
					}
					paramBody.push(paramText);
				}
                editor.windowManager.open( {
                    title: tinyMCE_object.button_title,
                    body: paramBody,
                    onsubmit: function( e ) {
						var selectedWidgetType;
						if(e.data.vllistbox == 'none' || tinyMCE_isCampaignActive != true) {
							return;
						} else if((typeof e.data.vllistbox != 'undefined')  && (e.data.vllistbox != 'none')) {
							selectedWidgetType = 'widget="' + e.data.vllistbox + '"';
							editor.insertContent( '[vl_form ' + selectedWidgetType + ']');
						} else {
							editor.insertContent( '[vl_form]');
						}
                    }
                });
            },
        });
    });

})();