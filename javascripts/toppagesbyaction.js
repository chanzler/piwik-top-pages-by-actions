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
        	$('.position').addClass("delete");
            $.each( data, function( index, value ){
            	if ( $("#idaction"+value['idaction_url']).length ) {
            		$("#idaction"+value['idaction_url']).removeClass('delete');
            		$("#idaction"+value['idaction_url']).find(".number").text(value['number']);
                	if (value['number']-value['histNumber'] > (value['histNumber'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/uArrow.png\">";
                	else if (value['histNumber']-value['number'] > (value['number'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/dArrow.png\">";
                	else icon = "&nbsp;";
            		$("#idaction"+value['idaction_url']).find(".trend").html(icon);
            	} else {
                	//if ($( ".position").length >= numberOfEntries) {
                	//	$( ".position").last().remove();
                	//}
                	name = value['name']; 
                    if (name == "null") {
                    	name = value['url'] 
                    }
                	if (value['number']-value['histNumber'] > (value['histNumber'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/uArrow.png\">";
                	else if (value['histNumber']-value['number'] > (value['number'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/dArrow.png\">";
                	else icon = "&nbsp;";
					rowClass = "columneven";
					console.log("new item");
					$( ".tpbv table" ).append( "<tr id=\"idaction"+value['idaction_url']+"\" class=\"position pos"+index+"\" style=\"position:absolute;\"><td class=\"trend "+rowClass+"\">"+icon+"</td><td class=\"number "+rowClass+"\">"+value['number']+"</td><td class=\""+rowClass+"\">"+name+"</td><td class=\""+rowClass+"\">"+((value['time'] != null)?value['time'].split(".")[0]:"0")+":"+((value['time'] != null)?value['time'].split(".")[1].substring(0,2):"00")+" min.</td></tr>" );
            	}
            });
			//$.fn.tableSort.sortTable();
            $( ".delete").remove();

            // Set table height and width
            $(".tpbv table").height((data.length*30)+30).width($(this).outerWidth());

			// Put all the rows back in place
            var vertical_offset = 0; // Beginning distance of rows from the table body in pixels
            $(".tpbv table").find('tr').each(function(index) {
            	$(this).css('top', vertical_offset);
            	vertical_offset += 30;
			});

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
                	if (value['number']-value['histNumber'] > (value['histNumber'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/uArrow.png\">";
                	else if (value['histNumber']-value['number'] > (value['number'] /100) * 15) icon = "<img src=\"plugins/TopPagesByActions/images/dArrow.png\">";
                	else icon = "&nbsp;";
					rowClass = "columneven";
                	$( ".tpbv table" ).append( "<tr id=\"idaction"+value['idaction_url']+"\" class=\"position pos"+index+"\" style=\"position:absolute;\"><td class=\"trend "+rowClass+"\">"+icon+"</td><td class=\"number "+rowClass+"\">"+value['number']+"</td><td class=\""+rowClass+"\">"+name+"</td><td class=\""+rowClass+"\">"+((value['time'] != null)?value['time'].split(".")[0]:"0")+":"+((value['time'] != null)?value['time'].split(".")[1].substring(0,2):"00")+" min.</td></tr>" );
                i++;
            });
            $('.tpbv').each(function() {
                var $this = $(this),
                   refreshAfterXSecs = refreshInterval;

    			// Set each td's width
                $(".tpbv table").find('tr:first-child th').each(function() {
                	column_widths.push($(this).outerWidth(true));
        		});
                $(".tpbv table").find('tr td').each(function() {
        			$(this).css( {
        				minWidth: column_widths[$(this).index()],
        				padding: 0
        			} );
                });

                // Set each row's height and width
    			$(".tpbv table").find('tr').each(function() {
    				//$(this).width($(this).outerWidth(true));
    				$(this).height($(this).outerHeight(true));
    			});

                // Set table height and width
                $(".tpbv table").height((data.length*30)+30).width($(this).outerWidth());
                
    			// Put all the rows back in place
                var vertical_offset = 0; // Beginning distance of rows from the table body in pixels
                $(".tpbv table").find('tr').each(function(index) {
                	$(this).css('top', vertical_offset);
                	vertical_offset += 30;
    			});
                
                for (j=1; j=i; j++){
                	$(".tpbv table").find('tr.pos'+j).css({ top: 30+(j*30) }).appendTo(".tpbv table");
                }
                setTimeout(function() { refreshTopPagesByActionsWidget($this, refreshAfterXSecs, numberOfEntries); }, refreshAfterXSecs * 1000);
            });
        });
        ajaxRequest.send(true);
     };
