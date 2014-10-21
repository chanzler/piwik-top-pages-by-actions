var settings = $.extend( {
	rowHeight			: 25,
});
var history = [];
var trend = [];
var icon = "&nbsp;";

var refreshTopPagesByActionsWidget = function (element, refreshAfterXSecs, numberOfEntries) {
        // if the widget has been removed from the DOM, abort
        if ($(element).parent().length == 0) {
            return;
        }
		var column_widths = new Array();

        var ajaxRequest = new ajaxHelper();
        ajaxRequest.addParams({
            module: 'API',
            method: 'TopPagesByActions.getTopPagesByActions',
            format: 'json',
            lastMinutes: 20
        }, 'get');
        ajaxRequest.setFormat('json');
        ajaxRequest.setCallback(function (data) {
        	$('.position').addClass("delete");
            $.each( data, function( index, value ){
        		//calculate trend
        		if (value['number']-history[value['idaction_url']] > (history[value['idaction_url']] /100) * 2) 
        			(trend[value['idaction_url']]>=100)?trend[value['idaction_url']]=100:trend[value['idaction_url']]+=20;
            	else if (history[value['idaction_url']]-value['number'] > (value['number'] /100) * 2) 
            		(trend[value['idaction_url']]<=-100)?trend[value['idaction_url']]=-100:trend[value['idaction_url']]-=20;
            	else if (value['number']-history[value['idaction_url']] > (history[value['idaction_url']] /100) * 1) 
            		(trend[value['idaction_url']]>=100)?trend[value['idaction_url']]=100:trend[value['idaction_url']]+=10;
            	else if (history[value['idaction_url']]-value['number'] > (value['number'] /100) * 1) 
            		(trend[value['idaction_url']]<=-100)?trend[value['idaction_url']]=-100:trend[value['idaction_url']]-=10;
            	else 
            		(trend[value['idaction_url']]<0)?trend[value['idaction_url']]+=1:trend[value['idaction_url']]-=1;
        		history[value['idaction_url']] = value['number'];
        		if (trend[value['idaction_url']] < -55) icon = "<img src=\"plugins/TopPagesByActions/images/doubleDownArrow.png\">";
            	else if (trend[value['idaction_url']] > 55) icon = "<img src=\"plugins/TopPagesByActions/images/doubleUpArrow.png\">";
            	else if (trend[value['idaction_url']] < -25) icon = "<img src=\"plugins/TopPagesByActions/images/downArrow.png\">";
            	else if (trend[value['idaction_url']] > 25) icon = "<img src=\"plugins/TopPagesByActions/images/upArrow.png\">";
            	else icon = "&nbsp;";
            	if ( $("#idaction"+value['idaction_url']).length ) {
            		$("#idaction"+value['idaction_url']).removeClass('delete');
            		$("#idaction"+value['idaction_url']).find(".number").text(value['number']);
            		$("#idaction"+value['idaction_url']).attr("table_pos", index);
            		$("#idaction"+value['idaction_url']).find(".trend").html(icon);
            	} else {
                	name = value['name']; 
                    if (name == "null") {
                    	name = value['url'] 
                    }
            		history[value['idaction_url']] = value['number'];
                	trend[value['idaction_url']] = 0;
					rowClass = "columneven";
					$( ".tpbv table" ).append( "<tr id=\"idaction"+value['idaction_url']+"\" class=\"position pos"+index+"\" content_id="+value['idaction_url']+"\" table_pos=\""+index+"\" style=\"position:absolute;\"><td class=\"trend "+rowClass+"\">"+icon+"</td><td class=\"number "+rowClass+"\">"+value['number']+"</td><td class=\""+rowClass+"\">"+name+"</td><td class=\""+rowClass+"\">"+((value['time'] != null)?value['time'].split(".")[0]:"0")+":"+((value['time'] != null)?value['time'].split(".")[1].substring(0,2):"00")+"</td></tr>" );
            	}
            });
            console.log(history);
            console.log(trend);
            $( ".delete").remove();

			// Set each td's width
            $(".tpbv table").find('tr:first-child th').each(function() {
            	column_widths.push($(this).width());
    		});
            $(".tpbv table").find('tr td').each(function() {
    			$(this).css( {
    				minWidth: column_widths[$(this).index()]
    			} );
            });

            // Set each row's height and width
			$(".tpbv table").find('tr').each(function() {
				$(this).width($(this).outerWidth(true));
				$(this).height(settings['rowHeight']);
			});

			// Set table height and width
            $(".tpbv table").height((data.length*settings['rowHeight'])+40);

			// Put all the rows back in place
            var vertical_offset = 30; // Beginning distance of rows from the table body in pixels
            $(".tpbv table").find('tr.position').each(function(index) {
            	$(this).css('top', vertical_offset);
            	vertical_offset += settings['rowHeight'];
			});

			//animation
			var vertical_offset = 30; // Beginning distance of rows from the table body in pixels
			for ( index = 0; index < data.length; index++) {
				$(".tpbv table").find("tr[table_pos="+index+"]").stop().delay(1 * index).animate({ top: vertical_offset}, 1000, 'swing').appendTo(".tpbv table");
				vertical_offset += settings['rowHeight'];
			}
            // schedule another request
            setTimeout(function () { refreshTopPagesByActionsWidget(element, refreshAfterXSecs, numberOfEntries); }, refreshAfterXSecs * 1000);
            
        });
        ajaxRequest.send(true);
    };

    var exports = require("piwik/TopPagesByActions");
    exports.initTopPagesByActionsWidget = function (refreshInterval, numberOfEntries) {
		var column_widths = new Array();
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
            		history[value['idaction_url']] = value['number'];
                	trend[value['idaction_url']] = 0;
                	icon = "&nbsp;";
					rowClass = "columneven";
                	$( ".tpbv table" ).append( "<tr id=\"idaction"+value['idaction_url']+"\" class=\"position pos"+index+"\" content_id="+value['idaction_url']+"\" table_pos=\""+index+"\" style=\"position:absolute;\"><td class=\"trend "+rowClass+"\">"+icon+"</td><td class=\"number "+rowClass+"\">"+value['number']+"</td><td class=\"name "+rowClass+"\">"+name+"</td><td class=\"time "+rowClass+"\">"+((value['time'] != null)?value['time'].split(".")[0]:"0")+":"+((value['time'] != null)?value['time'].split(".")[1].substring(0,2):"00")+"</td></tr>" );
                i++;
            });
            $('.tpbv').each(function() {
                var $this = $(this),
                   refreshAfterXSecs = refreshInterval;

    			// Set each td's width
                $(".tpbv table").find('tr:first-child th').each(function() {
                	column_widths.push($(this).width());
        		});
                $(".tpbv table").find('tr td').each(function() {
        			$(this).css( {
        				minWidth: column_widths[$(this).index()]
        			} );
                });

                // Set each row's height and width
    			$(".tpbv table").find('tr').each(function() {
    				$(this).height(settings['rowHeight']);
    			});

                // Set table height and width
    			$(".tpbv table").height((data.length*settings['rowHeight'])+40);
                
    			// Put all the rows back in place
                var vertical_offset = 30; // Beginning distance of rows from the table body in pixels
                $(".tpbv table").find('tr.position').each(function(index) {
                	$(this).css('top', vertical_offset);
                	vertical_offset += settings['rowHeight'];
    			});
                
                for (j=0; j<i; j++){
                	$(".tpbv table").find('tr.pos'+j).css({ top: 30+(j*settings['rowHeight']) }).appendTo(".tpbv table");
                }
                setTimeout(function() { refreshTopPagesByActionsWidget($this, refreshAfterXSecs, numberOfEntries); }, refreshAfterXSecs * 1000);
            });
        });
        ajaxRequest.send(true);
     };
