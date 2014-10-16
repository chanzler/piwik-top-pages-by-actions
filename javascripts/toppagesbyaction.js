    var refreshTopPagesByActionsWidget = function (element, refreshAfterXSecs, numberOfEntries) {
        // if the widget has been removed from the DOM, abort
        if ($(element).parent().length == 0) {
            return;
        }

        var ajaxRequest = new ajaxHelper();
        ajaxRequest.addParams({
            module: 'API',
            method: 'TopPagesByActions.getTopPagesByActions',
            format: 'json',
            lastMinutes: 20
        }, 'get');
        ajaxRequest.setFormat('json');
        ajaxRequest.setCallback(function (data) {
            $.each( data, function( index, value ){
            	if ( $("#idaction"+value['idaction_url']).length ) {
            		$("#idaction"+value['idaction_url']).removeClass('delete');
            		$("#idaction"+value['idaction_url']).find(".number").text(value['number']);
                	if (value['number']-value['histNumber'] > (value['histNumber'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/uArrow.png\">";
                	else if (value['histNumber']-value['number'] > (value['number'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/dArrow.png\">";
                	else icon = "&nbsp;";
            		$("#idaction"+value['idaction_url']).find(".trend").html(icon);
            	} else {
                	if ($( ".position").length >= numberOfEntries) {
                		$( ".position").last().remove();
                	}
                	name = value['name']; 
                    if (name == "null") {
                    	name = value['url'] 
                    }
                	if (value['number']-value['histNumber'] > (value['histNumber'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/uArrow.png\">";
                	else if (value['histNumber']-value['number'] > (value['number'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/dArrow.png\">";
                	else icon = "&nbsp;";
					rowClass = "columneven";
					console.log("new item");
					$( ".tableSorter" ).append( "<tr id=\"idaction"+value['idaction_url']+"\" class=\"position\"><td class=\"trend "+rowClass+"\">"+icon+"</td><td class=\"number "+rowClass+"\">"+value['number']+"</td><td class=\""+rowClass+"\">"+name+"</td><td class=\""+rowClass+"\">"+((value['time'] != null)?value['time'].split(".")[0]:"0")+":"+((value['time'] != null)?value['time'].split(".")[1].substring(0,2):"00")+" min.</td></tr>" );
					$('table.tableSorter').tableSort();
            	}
            });
			$.fn.tableSort.sortTable();

            // schedule another request
            setTimeout(function () { refreshTopPagesByActionsWidget(element, refreshAfterXSecs, numberOfEntries); }, refreshAfterXSecs * 1000);
        });
        ajaxRequest.send(true);
    };

    var exports = require("piwik/TopPagesByActions");
    exports.initTopPagesByActionsWidget = function (refreshInterval, numberOfEntries) {
        var ajaxRequest = new ajaxHelper();
        ajaxRequest.addParams({
            module: 'API',
            method: 'TopPagesByActions.getTopPagesByActions',
            format: 'json',
            lastMinutes: 20
        }, 'get');
        ajaxRequest.setFormat('json');
        ajaxRequest.setCallback(function (data) {
        	i=1;
            $.each( data, function( index, value ){
            	name = value['name']; 
                if (name == "null") {
                	name = value['url'] 
                }
                if(i <= numberOfEntries)
                	if (value['number']-value['histNumber'] > (value['histNumber'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/uArrow.png\">";
                	else if (value['histNumber']-value['number'] > (value['number'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/dArrow.png\">";
                	else icon = "&nbsp;";
					rowClass = "columneven";
                	$( ".tableSorter" ).append( "<tr id=\"idaction"+value['idaction_url']+"\" class=\"position\"><td class=\"trend "+rowClass+"\">"+icon+"</td><td class=\"number "+rowClass+"\">"+value['number']+"</td><td class=\""+rowClass+"\">"+name+"</td><td class=\""+rowClass+"\">"+((value['time'] != null)?value['time'].split(".")[0]:"0")+":"+((value['time'] != null)?value['time'].split(".")[1].substring(0,2):"00")+" min.</td></tr>" );
                i++;
            });
            $('.tpbv').each(function() {
                var $this = $(this),
                   refreshAfterXSecs = refreshInterval;
                setTimeout(function() { refreshTopPagesByActionsWidget($this, refreshAfterXSecs, numberOfEntries); }, refreshAfterXSecs * 1000);
            });
        });
        ajaxRequest.send(true);
     };
