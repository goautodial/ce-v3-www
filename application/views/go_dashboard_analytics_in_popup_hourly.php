<?php
########################################################################################################
####  Name:             	go_dashboard_analytics_in_popup_hourly.php                          ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:      	Chris Lomuntad                                                      ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$base = base_url();
$cwd = getcwd();
$curdate = date("Ymd");
$json_sph[] = "";
$xMin = "";

$hourvar = 22;
$hour = 9;

foreach($get_hourly_in_sales as $item):	
	while ($hour < $hourvar){
	$rowval = "Hour" . $hour; 
	$hourval = $hour; 
	$cnt = $item->$rowval;
	$json_sph[] = array($hourval,$cnt);
$hour++;
}
endforeach;

if (count($json_sph)<2) {
	$xMin = "min: 0,";
}
write_file("$cwd/data/sph-in-hourly-$groupId.json",json_encode($json_sph));
?>

<p class="sub">Inbound Sales / Hour (Today - 9:00 AM - 9:00 PM)</p>
<table style="margin-left: 20px;" width="100%">
	<tbody>
	<tr>
		<td><div id="inbound_placeholderz" style="width:95%;height:150px;"></div></td>
			<!--<td class="c"><a class="" href=""><? //echo $inbound_sph; ?></a></td>
			<td class="r"><a class="" href="">Outbound Sales / Hour</a></td>-->
	</tr>
	</tbody>
</table>

<script type="text/javascript">
$(function () {
    var options = {
	colors: ["#565656"],
        lines: { show: true },
        points: { show: false },
        xaxis: { <?php echo $xMin; ?> tickDecimals: 0,  tickSize: 1, ticks: [[9, "9 AM"], [10, "10 AM"], [11, "11 AM"], [12, "12 NN"], [13, "1 PM"], [14, "2 PM"], [15, "3 PM"], [16, "4 PM"], [17, "5 PM"], [18, "6 PM"], [19, "7 PM"], [20, "8 PM"], [21, "9 PM"]]},
        yaxis: { min: 0 },
	shadowSize: 7,
        grid: { borderWidth: 0, hoverable: true, clickable: true }
    };
    var data = [];
    var inbound_placeholderz = $("#inbound_placeholderz");
    var currentdate = "";

    var plot = $.plot(inbound_placeholderz, data, options);
    
    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #e7e7e7',
            padding: '2px',
            'font-size': '20px',
            'background-color': '#e7e7e7',
            opacity: 0.90
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    inbound_placeholderz.bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

//         if ($("#enableTooltip:checked").length > 0) {
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
                    
                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(0),
                        y = item.datapoint[1].toFixed(2);
                    var z = '';

		    switch (x) {
			case '1':
			    z = 'st';
			    break;
			case '2':
			    z = 'nd';
			    break;
			case '3':
			    z = 'rd';
			    break;
			case '21':
			    z = 'st';
			    break;
			case '22':
			    z = 'nd';
			    break;
			case '23':
			    z = 'rd';
			    break;
			default:
			    z = 'th';
		    }
                    
                    showTooltip(item.pageX, item.pageY,
                                x + z + " hr &raquo; " + y + " sph ");
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
//         }
    });

    // initiate a recurring data update
    function fetchData() {
	currentdate = '<? echo mdate("%Y%m%d", now()); ?>';

	function onDataReceived(series) {
	    // we get all the data in one go, if we only got partial
	    // data, we could merge it with what we already got
	    data = [ series ];
	    
	    $.plot(inbound_placeholderz, data, options);
	}
    
	$.ajax({
	    // usually, we'll just call the same URL, a script
	    // connected to a database, but in this case we only
	    // have static example files so we need to modify the
	    // URL
	    //url: "<? echo $base; ?>data/sph-in-hourly-" + currentdate + ".json",
	    url: "<? echo $base; ?>data/sph-in-hourly-<? echo $groupId; ?>" + ".json",	    
	    method: 'GET',
	    dataType: 'json',
	    success: onDataReceived
	});
	setTimeout(fetchData, 300000);
    }

    fetchData();
});
</script>