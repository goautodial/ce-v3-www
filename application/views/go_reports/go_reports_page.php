<?php
############################################################################################
####  Name:             go_reports_page.php                                             ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
if (!isset($total_calls)) { $total_calls=0; }
if (!isset($total_leads)) { $total_leads=0; }
if (!isset($total_agents)) { $total_agents=0; }
if (!isset($total_sales)) { $total_sales=0; }
if (!isset($campaign_name) || $campaign_name=='null') { $campaign_name=''; }
	else { $campaign_name='- '.$campaign_name; }
if (!isset($campaign_id) || $campaign_id=='null') { $campaign_id='<span style="color:red">'.$this->lang->line("go_pls_sel_camp").'</span>'; $campaign_name=''; }

$base = base_url();

$isGraph = false;
$cwd = getcwd();
$curdate = date("Ymd");
$curyear = date("Y");
$xMin = "";
$user_group = $this->session->userdata("user_group");
// $fromDate = str_replace('-','',$from_date);
// $toDate = str_replace('-','',$to_date);
// $fDate = "$fromDate-$toDate";

switch($pagetitle) {
	case "sales_tracker":
		if (!isset($request) || $request != 'inbound') { $request = 'outbound'; }
		$iscamp = $this->lang->line("go_campaigns");
		break;
	default:
		if (!isset($request)) { $request = 'daily'; }
		$iscamp = ($pagetitle=='inbound_report') ? "Inbound Group" : $this->lang->line("go_campaigns");
}

// Disposition Statuses
if (count($statuses) > 0)
{
	foreach ($statuses as $item):
		$status[$item->status] = $item->status_name;
	endforeach;
}

// Start of Statistics Report
if ($pagetitle=="stats") {
    switch ($request) {
	    case "monthly":
			$requests = "Month";

			if (isset($data_calls))
			{
				foreach($data_calls as $item):
					$dayvar = 13;
					$day = 1;
					$json_data[$item->monthname]["label"] = $item->monthname;
					while ($day < $dayvar){
						$rowval = "Month".$day;
						$dayval = $day;

						$cnt = $item->$rowval;
						$json_data[$item->monthname]["data"][] = array($dayval,$cnt);
						$day++;
					}
				endforeach;
		    }
		    break;

	    case "weekly":
			$requests = "Week";

			if (isset($data_calls))
			{
				foreach($data_calls as $item):
					$dayvar = 7;
					$day = 0;
					$startWeek = date("M d, Y", strtotime("1.1.".$curyear." + ".$item->weekno." weeks -6 days"));
					$endWeek = date("M d, Y", strtotime("1.1.".$curyear." + ".$item->weekno." weeks"));
					$json_data[$item->weekno]["label"] = "$startWeek to $endWeek";
					while ($day < $dayvar){
						$rowval = "Day".$day;
						$dayval = $day;

						$cnt = $item->$rowval;
						$json_data[$item->weekno]["data"][] = array($dayval,$cnt);
						$day++;
					}
				endforeach;
		    }
		    break;
		    
	    default:
			$requests = "Day";

			if (isset($data_calls))
			{
				foreach($data_calls as $item):
					list($hour, $hourvar) = explode('-',$call_time);
					$requests = "Day";
					$hourvar = (strlen($hourvar)>2) ? substr($hourvar,0,-2) : $hourvar;
					$hour = (strlen($hour)>2) ? substr($hour,0,-2) : $hour;

					if ($hourvar == 24)
						$hourvar--;
					if ($hourvar < 23)
						$hourvar++;
					if ($hour > 0)
						$hour--;
					
					$json_data[$item->cdate]["label"] = date("M d, Y", strtotime($item->cdate));
					while ($hour <= $hourvar){
						$rowval = "Hour" . $hour; 
						$hourval = $hour;
						
						$cnt = $item->$rowval;
						$json_data[$item->cdate]["data"][] = array($hourval,$cnt);
						$hour++;
					}
				endforeach;
			}
		    break;
    }
	
	if (isset($data_status))
	{
		// Outbound Calls
		$tr = 0;
	//    $dispo_table = "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border:0px solid #000;\">";
		foreach($data_status as $i => $item):
		 	if ($tr > 5) {
				$dispo_table .= "<tr class=\"hiddenStatus\" style=\"display: none\">";
		 	} else {
				$dispo_table .= "<tr>";
			}
			
			$varCalls = 'call';
			if ($item->ccount > 1)
				$varCalls = 'calls';
	
			$dispo_table .= "<td style=\"width:100px;padding-left:50px;height:10px;\" class=\"c\"><a style=\"cursor:pointer\" class=\"".$item->status."\">".round(($item->ccount/$total_calls)*100,2)."%</a></td><td style=\"width:100px;\" nowrap=\"nowrap\" class=\"r\"><a style=\"cursor:pointer\" class=\"".$item->status."\">".$status[$item->status]." <span style=\"font-size:12px;font-style:italic;\">(".$item->status.")</span><br /><span style=\"font-size:12px;\">".$item->ccount." $varCalls</span></a></td>";
	
	// 	if ($tr == 0) {
			$dispo_table .= "</tr>";
	// 	}
	
	// 	if ($tr == 1) {
	// 	    $tr = 0;
	// 	} else {
	// 	    $tr = 1;
	// 	}
			
			$data_pie[] = "{\"label\": \"".$status[$item->status]. " (".$item->status.")\",\"data\": Math.floor(".$item->ccount."*100)+1,\"status\": \"".$item->status."\"}";
			$tr++;
		endforeach;
	//    $dispo_table .= "</table>";
	}
	
	if (count($json_data)<1) {
		$json_data[$from_date]['data'][] = "";
		$xMin = "min: 0,";
	}

	if (write_file("$cwd/data/stats-$request-$user_group.json",json_encode($json_data)))
	{
                echo "<!-- {$this->lang->line("go_ok")} -->";
        } else {
                echo "<!-- {$this->lang->line("go_failed")} -->";
	}
}
// End of Statistics Report

// Start of Dashboard JSON Report
if ($pagetitle=="dashboard" && $isGraph)
{
	if (count($total_dialer_calls_output) > 0)
	{
		$i = 1;
		foreach ($total_dialer_calls_output AS $code => $value)
		{
			$data_ticks[] = "[$i, \"$code\"]";
			$data_graph[] = "[$i, $value]";
			$data_codes[$i] = "$code";
			$i++;
		}
	}

    if (count($data_graph)<1) {
	    $xMin = "min: 0,";
    }

//     write_file("$cwd/data/dashboard-$groupId.json",json_encode($json_data));
}
// End of Dashboard JSON Report
?>
<style type="text/css">
<!--
body,
style1,
style2,
style3,
style4,
style8 { font-family: Verdana, Arial, Helvetica, sans-serif; }

.style1 {
	color: #6A6A6A;
	font-weight: normal;
}
.style2 {
	color: #000000;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: bold;
}
.style3 {
	color: #000000;
	font-weight: bold;
}
.style4 {color: #000000; font-weight: normal; }

/* Style for overlay and box */
div[id^="overlay"]{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

div[id^="box"]{
	position:absolute;
	top:-2550px;
	left:14%;
	right:14%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;
	
	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:101;
}

.closebox{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}
-->
</style>
<script>
$(document).ready(function(){
	$(".toolTip").tipTip();
	
	$('.closebox').click(function()
	{
		var lastphone = $('#lastPhone').text();
		$('#box'+lastphone).animate({'top':'-2550px'},500);
		$('#overlay'+lastphone).fadeOut('slow');
	});
	
	$('#export').hover(
		function () {
			$(this).addClass('tabhover');
		},
		function () {
			$(this).removeClass('tabhover');
		}
	);
	
	$('#export').click(
		function () {
			var request = $('#request').html();
			var pagetitle = $('#pagetitle').attr('title');
			var date_range = $('#widgetDate span').html().split(' to ');
			var from_date = date_range[0];
			var to_date = date_range[1];
			var campaign_id = $('#select_campaign').attr('title');
			
//			$.post('<? echo $base; ?>index.php/go_site/go_export_reports/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaign_id + '/' + request + '/');
//			$('#export_reports').load('<? echo $base; ?>index.php/go_site/go_export_reports/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaign_id + '/' + request + '/');
			if (campaign_id != "<? echo $this->lang->line("go_sel_camp"); ?>") {
				window.location = '<? echo $base; ?>index.php/go_site/go_export_reports/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaign_id + '/' + request + '/';
				return false;
			}
		}
	);
});

function viewInfo(phone)
{
	$('#lastPhone').text(phone);
	
	$('#overlay'+phone).fadeIn('fast');
	$('#box'+phone).css({'width': '400px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '20px'});
	$('#box'+phone).animate({
//		top: Math.max(0, (($(window).height() - $('#box'+phone).outerHeight()) / 2) + $(window).scrollTop()) + "px"
		top: "100px"
	}, 500);		
}
</script>
<p style="font-style:italic;font-family:Verdana, Arial, Helvetica, sans-serif;color:#555;font-size:13px;text-align:center;font-weight:100;">
<? if (!preg_match("/call_export_report/", $pagetitle)) { ?>
<span style="font-weight:bold;font-style:normal;"><? echo $this->lang->line("go_selected"); ?> <? echo $iscamp; ?>:</span> <span><? echo "$campaign_id $campaign_name"; ?></span>
<br />
<?
}
if (!preg_match("/dispo/", $pagetitle)) { ?>
<span style="font-weight:bold;font-style:normal;"><? echo $this->lang->line("go_date_range"); ?>:</span> <span id="selected_from_date"><? echo "$from_date"; ?></span> 00:00:00 <span style="font-weight:bold;font-style:normal;"><? echo $this->lang->line("go_to"); ?></span> <span id="selected_to_date"><? echo "$to_date"; ?></span> 23:59:59
<? } ?>
</p>

<table style="width:100%;">
    <tbody>
        <tr>
            <td align="center">

<!-- Start of Statistics Report -->
<? if ($pagetitle=='stats') { ?>
<div class="midheader" style="position:absolute;z-index:10;left:60px;"><? echo $this->lang->line("go_calls_per"); ?> <?php echo ucwords($requests); ?></div>
<table style="margin-left: 20px;" width="100%">
	<tbody>
	<tr>
		<td><div id="graph_placeholder" style="width:95%;height:250px;"></div></td>
			<!--<td class="c"><a class="" href=""><? //echo $inbound_sph; ?></a></td>
			<td class="r"><a class="" href="">Outbound Sales / Hour</a></td>-->
	</tr>
	</tbody>
</table>
<?php
if (strlen($dispo_table)<1) {
	$hide_this = "display:none;";
}
?>
<table><tr><td>
<p id="choices" style="<?php echo $hide_this; ?>"><? echo $this->lang->line("go_show"); ?>:</p>
</tr></td></table>
<br />
<br />
<div style="border-bottom:1px #ececec solid;padding:5px 10px 10px;text-align:left;color:#777;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;font-style:italic;font-size:13px;<?php echo $hide_this; ?>"><? echo $this->lang->line("go_call_statistics"); ?></div>
<table cellpadding="0" cellspacing="0" style="border:0px solid #000;width:100%;height:100px;<?php echo $hide_this; ?>">
    <tr class="first">
        <td height="10" class="b" style="width:100px;padding-left:50px;"><a style="cursor:pointer" class=""><?php echo $total_calls; ?></a></td>
        <td height="10" class="t bold"><a style="cursor:pointer" class=""><? echo $this->lang->line("go_total_calls"); ?></a></td>
        <td rowspan="<?php echo ($tr+5); ?>" align="left" valign="top"><br /><br /><div id="pie_placeholder" style="width:650px;height:400px;"></div><div id="hover"></div></td>
    </tr>
    <tr>
<!--            <td height="30" valign="top" style="visibility:hidden;">Uncalled Leads (NEW): <?php echo $total_new; ?></td> -->
        <td height="10" class="c" style="padding-left:50px;"><a style="cursor:pointer" class=""><?php echo $total_agents; ?></a></td>
        <td height="10" class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_total_agents"); ?></a></td>
    </tr>
    <tr>
        <td height="10" class="c" style="padding-left:50px;"><a style="cursor:pointer" class=""><?php echo $total_leads; ?></a></td>
        <td height="10" class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_lead_count"); ?></a></td>
    </tr>
    <tr>
        <td colspan="2" height="50" valign="bottom" style="border-bottom:1px #ececec solid;padding:5px 10px 10px;text-align:left;color:#777;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;font-style:italic;font-size:13px;"><? echo $this->lang->line("go_disposition_stats"); ?></td>
    </tr>
<!--    <tr>
    	<td colspan="2" width="50%" valign="top">-->
        <?php echo $dispo_table; ?>
<!--        </td>
    </tr>-->
    <tr>
    	<td colspan="2">
			<?php
            if (($tr-1) > 5) {
            ?>
            <table>
                <tbody>
                    <tr>
                     <td class='dp'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <td class='showhide'>
                     <a id='showmore_status' class='showmore'>&nbsp;&raquo; <? echo $this->lang->line("go_click_show_more"); ?> </a>
                     <a style='display: none' id='hidemore_status' class='hidemore'>&nbsp;&raquo; <? echo $this->lang->line("go_click_hide"); ?> </a>
                     </td>
                    </tr>
                </tbody>
            </table>
            <?
            }
            ?>
            <script>
            
                $("#showmore_status").click(function () {
                $(".hiddenStatus").show("slow");
                $("#hidemore_status").show("slow");
                $("#showmore_status").hide("slow");
                
                });
                
                
                $("#hidemore_status").click(function () {
                $(".hiddenStatus").hide("slow");
                $("#showmore_status").show("slow");
                $("#hidemore_status").hide("slow");
                
                });
            
            </script>
        </td>
    </tr>
</table>

<script type="text/javascript">
$(function () {
    var options = {
	colors: ["#565656"],
	legend: { show: false },
	lines: { show: true },
	points: { show: false },
<?php
	if ($request=="daily") {
?>
        xaxis: { <?php echo $xMin; ?> tickDecimals: 0,  tickSize: 1, ticks: [[0, "12 MN"], [1, "1 AM"], [2, "2 AM"], [3, "3 AM"], [4, "4 AM"], [5, "5 AM"], [6, "6 AM"], [7, "7 AM"], [8, "8 AM"], [9, "9 AM"], [10, "10 AM"], [11, "11 AM"], [12, "12 NN"], [13, "1 PM"], [14, "2 PM"], [15, "3 PM"], [16, "4 PM"], [17, "5 PM"], [18, "6 PM"], [19, "7 PM"], [20, "8 PM"], [21, "9 PM"], [22, "10 PM"], [23, "11 PM"]]},
<?php
	}

	if ($request=="weekly") {
?>
        xaxis: { <?php echo $xMin; ?> tickDecimals: 0,  tickSize: 1, ticks: [[0, "Mon"], [1, "Tue"], [2, "Wed"], [3, "Thu"], [4, "Fri"], [5, "Sat"], [6, "Sun"]]},
<?php
	}

	if ($request=="monthly") {
?>
        xaxis: { <?php echo $xMin; ?> tickDecimals: 0,  tickSize: 1, ticks: [[1, "Jan"], [2, "Feb"], [3, "Mar"], [4, "Apr"], [5, "May"], [6, "Jun"], [7, "Jul"], [8, "Aug"], [9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dec"]]},
<?php
	}
?>
        yaxis: { min: 0 },
		shadowSize: 7,
        grid: { borderWidth: 0, hoverable: true, clickable: true }
    };

    var options_pie = {
		series: {
			pie: {
				show: true,
				radius: 6/8,
				offset: {
					left: -120
				}
			}
		},
        grid: {
            hoverable: true,
            clickable: false
        }
    };

    var data_calls = [];
    var data_plot = [];
    var data_status = [];
    var data_json = '';
    var graph_placeholder = $("#graph_placeholder");
    var pie_placeholder = $("#pie_placeholder");
    var currentdate = "";
	var currentstatus = [];
	var seriesColor = [];

    // For Testing Pie Graph
<?php
	if ($data_pie==NULL)
	{
?>
	    data_plot[0] = 0;
<?php
	} else {
// var_dump($data_pie);
		foreach ($data_pie as $key => $item):
?>
	    data_plot[<?php echo $key; ?>] = <?php echo "$item\n"; ?>
<?php
		endforeach;
	}
?>

    var plot_graph = $.plot(graph_placeholder, data_calls, options);
    var plot_pie = $.plot(pie_placeholder, data_plot, options_pie);

    pie_placeholder.bind("plothover", pieHover);
    pie_placeholder.bind("plotclick", pieClick);

    function pieHover(event, pos, obj)
    {
		var laststatus = $('#lastStatus').text();
	    if (!obj)
		{
//	    	$("#hover").html('');
			if (laststatus!='')
				$("."+laststatus).css('color','#464646');
		    return;
		}
		
		if (laststatus!='')
			$("."+laststatus).css('color','#464646');

		var regexp = /\(.*\)/g;
		currentstatus = regexp.exec(obj.series.label);
		$('#lastStatus').text(currentstatus[0].replace(/\(|\)/g,''));
		$("."+currentstatus[0].replace(/\(|\)/g,'')).css('color','#f00');
//	    percent = parseFloat(obj.series.percent).toFixed(2);
//	    $("#hover").html('<span style="font-weight: bold; color: #000">'+obj.series.label+' ('+percent+'%)</span>');
    }

    function pieClick(event, pos, obj) 
    {
	    if (!obj)
		    return;
	    percent = parseFloat(obj.series.percent).toFixed(2);
	    alert(''+obj.series.label+': '+percent+'%');
    }
    
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
	var idNum = null;
    graph_placeholder.bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

//         if ($("#enableTooltip:checked").length > 0) {
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
					
                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(0),
                        y = item.datapoint[1].toFixed(0);
                    var z = '';

					idNum = item.seriesIndex;
					$('label[for="id'+idNum+'"]').css({'color': '#f00'});

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
<?php
	if ($request=="hourly") {
?>
                                x + z + " hr &raquo; " + y + " calls/hour ");
<?php
	}
	
	if ($request=="daily") {
?>
                                y + " calls/day ");
<?php
	}
	
	if ($request=="weekly") {
?>
                                y + " calls/week ");
<?php
	}
	
	if ($request=="monthly") {
?>
                                y + " calls/month ");
<?php
	}
?>
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;
				if (idNum != null)
					$('label[for="id'+idNum+'"]').css({'color': '#000'});
            }
//         }
    });

	$('#cnt-reports').resize(function()
	{
		graph_placeholder.width($('#cnt-reports').width() - 100);
	});

    // initiate a recurring data update
    function fetchData() {
		currentdate = '<? echo mdate("%Y%m%d", now()); ?>';

		function onDataReceived(series) {
			// we get all the data in one go, if we only got partial
			// data, we could merge it with what we already got
			//console.log(series);
			for (x in series) {
				data_calls.push({'label':series[x]['label'],'data':series[x]['data']});
			}

			var testLang = $.plot(graph_placeholder, data_calls, options);

			// hard-code color indices to prevent them from shifting as
			// dates are turned on/off
			var i = 0;
			$.each(data_calls, function(key, val) {
			val.color = i;
			++i;
			});

			// insert checkboxes
			var choiceContainer = $("#choices");
			var m = 0;
			$.each(data_calls, function(key, val) {
				if (val.label!=undefined) {
					choiceContainer.append('&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="' + key +
							'" checked="checked" id="id' + key + '"> ' +
							'<label for="id' + key + '" style="font-family: Verdana, Arial, Helvetica, sans-serif;">'
								+ val.label + '</label>');

					if (m < 7)
					{
						++m;
					}
					else
					{
						choiceContainer.append('<br /><span style="visibility:hidden;"><? echo $this->lang->line("go_show"); ?>:</span>');
						m = 0;
					}
				}
			});
			choiceContainer.find("input").click(plotAccordingToChoices);

			function plotAccordingToChoices() {
				var data = [];

				choiceContainer.find("input:checked").each(function () {
					var key = $(this).attr("name");
					if (key && data_calls[key])
					data.push(data_calls[key]);
				});

				if (data.length > 0)
					testLang = $.plot(graph_placeholder, data, options);
			}

			plotAccordingToChoices();
		}

		$.ajax({
			// usually, we'll just call the same URL, a script
			// connected to a database, but in this case we only
			// have static example files so we need to modify the
			// URL
			//url: "<? echo $base; ?>data/sph-in-hourly-" + currentdate + ".json",
			url: "<? echo $base; ?>data/<? echo $pagetitle; ?>-<? echo $request; ?>-<? echo $user_group; ?>" + ".json",
			method: 'POST',
			dataType: 'json',
			cache: false,
			success: onDataReceived
		});
//	setTimeout(fetchData, 300000);
    }
    fetchData();
});
</script>
<? } ?>
<!-- End of Statistics Report -->

<!-- Start Agent Time Detail -->
<? if ($pagetitle=='agent_detail') { ?>
<table border="0" align="center" cellpadding="1" cellspacing="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;" >
  <tr style="background-color:#FFFFFF" class="style2">
    <td><div align="center" class="style1 style3" nowrap> &nbsp;<? echo $this->lang->line("go_full_name"); ?>&nbsp; </div></td>
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_user_name"); ?>&nbsp; </strong></div></td>
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_calls"); ?>&nbsp; </strong></div></td>
<!--    <td><div align="center" class="style4" nowrap><strong> &nbsp;Time Clock&nbsp; </strong></div></td>-->
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_agent_time"); ?>&nbsp; </strong></div></td>
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_wait"); ?>&nbsp; </strong></div></td>
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_talk"); ?>&nbsp; </strong></div></td>
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_dispo"); ?>&nbsp; </strong></div></td>
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_pause"); ?>&nbsp; </strong></div></td>
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_wrap_up"); ?>&nbsp; </strong></div></td>
    <td><div align="center" class="style4" nowrap><strong> &nbsp;<? echo $this->lang->line("go_customer"); ?>&nbsp; </strong></div></td>
  </tr>
	<?php
    if (isset($campaign_id) && !preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
        if (count($TOPsorted_output) > 0) {
            for ($i=0;$i<count($TOPsorted_output);$i++) {
                echo $TOPsorted_output[$i];
            }
    ?>
      <tr style="background-color:#FFFFFF">
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="left" class="style4">&nbsp; <strong><? echo $this->lang->line("go_total"); ?>:</strong> &nbsp;</div></td>
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="left" class="style4">&nbsp; <strong><? echo $this->lang->line("go_agents_caps"); ?>:</strong> <?=$TOT_AGENTS?> &nbsp;</div></td>
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=($TOTcalls > 0) ? $TOTcalls : 0; ?> &nbsp;</div></td>
    <!--    <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=$TOTtimeTC?> &nbsp;</div></td>-->
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=$TOTALtime?> &nbsp;</div></td>
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=$TOTwait?> &nbsp;</div></td>
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=$TOTtalk?> &nbsp;</div></td>
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=$TOTdispo?> &nbsp;</div></td>
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=$TOTpause?> &nbsp;</div></td>
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=$TOTdead?> &nbsp;</div></td>
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4">&nbsp; <?=$TOTcustomer?> &nbsp;</div></td>
      </tr>
    <?php
        } else {
    ?>
      <tr style="background-color:#EFFBEF">
        <td colspan="15" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><? echo $this->lang->line("go_no_agents_found_time_given"); ?></div></td>
      </tr>
    <?php
        }
    } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="15" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4" style="font-size:12px;"><? echo $this->lang->line("go_pls_pick_camp"); ?></div></td>
      </tr>
    <?php
    }
    ?>
    </table>

	<?php
    if (isset($campaign_id) && !preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id) && count($BOTsorted_output) > 0) {
    ?>
    <p style="font-size:5px;">&nbsp;</p>
    <table border="0" align="center" cellpadding="1" cellspacing="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
      <tr style="background-color:#FFFFFF;" class="style2">
        <td><div align="center" class="style1 style3" nowrap> &nbsp;<? echo $this->lang->line("go_full_name"); ?>&nbsp; </div></td>
    <?php
        echo $sub_statusesTOP;
    ?>
      </tr>
    <?php
        if (count($BOTsorted_output) > 0) {
        for ($i=0;$i<count($BOTsorted_output);$i++) {
            echo $BOTsorted_output[$i];
        }
    ?>
      <tr style="background-color:#FFFFFF;">
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="left" class="style4">&nbsp; <strong><? echo $this->lang->line("go_total"); ?></strong> &nbsp;</div></td>
    <?php
        echo $SUMstatuses;
    ?>
      </tr>
    <?php
        } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="4"><div align="center" class="style4"><? echo $this->lang->line("go_no_agents_found_time_given"); ?></div></td>
      </tr>
    <?php
        }
    ?>
    </table>
    <?php
    }
    if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		if (count($TOPsorted_output)) {
			echo '<span id="export" class="exporttab">'.$this->lang->line("go_export_csv").'</span>';
		}
	}
	
	if (count($TOPsorted_output) < 3 && count($TOPsorted_output) != 0) {
		echo "<br /><br /><br /><br /><br />";	
	}
	
	if (count($TOPsorted_output) == 0 || preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";	
	}
    ?>
<? } ?>
<!-- End Agent Time Detail -->

<!-- Start Agent Performance Detail -->
<? if ($pagetitle=="agent_pdetail") { ?>
    <table border="0" align="center" cellpadding="1" cellspacing="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
      <tr style="background-color:#FFFFFF;" class="style2">
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_full_name"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_id"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_calls"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_time"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_pause"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4" title="Pause Avg"><strong> &nbsp;&raquo; <? echo $this->lang->line("go_avg"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_wait"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4" title="Wait Avg"><strong> &nbsp;&raquo;<? echo $this->lang->line("go_avg"); ?> &nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_talk"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4" title="Talk Avg"><strong> &nbsp;&raquo; <? echo $this->lang->line("go_avg"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_dispo"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4" title="Dispo Avg"><strong> &nbsp;&raquo; <? echo $this->lang->line("go_avg"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_wrap_up"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4" title="Wrap-Up Avg"><strong> &nbsp;&raquo; <? echo $this->lang->line("go_avg"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_customer"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4" title="Customer Avg"><strong> &nbsp;&raquo; <? echo $this->lang->line("go_avg"); ?>&nbsp; </strong></div></td>
      </tr>
    <?php
    if (isset($campaign_id) && !preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
        if (count($TOPsorted_output) > 0) {
        for ($i=0;$i<count($TOPsorted_output);$i++) {
            echo $TOPsorted_output[$i];
        }
    ?>
      <tr style="background-color:#FFFFFF;">
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="left" class="style4" style="font-size:10px">&nbsp; <strong><? echo $this->lang->line("go_total"); ?></strong> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="left" class="style4" style="font-size:10px">&nbsp; <strong><? echo $this->lang->line("go_agents"); ?>:</strong> <?=$TOT_AGENTS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=($TOTcalls > 0) ? $TOTcalls : 0; ?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtime_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotPAUSE_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTavgPAUSE_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotWAIT_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTavgWAIT_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotTALK_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTavgTALK_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotDISPO_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTavgDISPO_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotDEAD_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTavgDEAD_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotCUSTOMER_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTavgCUSTOMER_MS?> &nbsp;</div></td>
      </tr>
    <?php
        } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="16" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><? echo $this->lang->line("go_no_agents_found_time_given"); ?></div></td>
      </tr>
    <?php
        }
    } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="16" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><? echo $this->lang->line("go_pls_pick_camp"); ?></div></td>
      </tr>
    <?php
    }
    ?>
    </table>
    <?php
    if (isset($campaign_id) && !preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id) && count($BOTsorted_output) > 0) {
    ?>
    <p style="font-size:5px;">&nbsp;</p>
    <table border="0" align="center" cellpadding="1" cellspacing="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
      <tr style="background-color:#FFFFFF;" class="style2">
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_full_name"); ?>&nbsp; </strong></div></td>
    <?php
        echo $SstatusesTOP;
    ?>
      </tr>
    <?php
        if (count($MIDsorted_output) > 0) {
        for ($i=0;$i<count($MIDsorted_output);$i++) {
            echo $MIDsorted_output[$i];
        }
    ?>
      <tr style="background-color:#FFFFFF;">
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="left" class="style4" style="font-size:10px">&nbsp; <strong><? echo $this->lang->line("go_total"); ?></strong> &nbsp;</div></td>
    <?php
        echo $SstatusesSUM;
    ?>
      </tr>
    <?php
        } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="4" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><? echo $this->lang->line("go_no_agents_found_time_given"); ?></div></td>
      </tr>
    <?php
        }
    ?>
    </table>
    <table>
    	<tr>
        	<td colspan="3" style="font-size:11px;font-weight:bold;"><? echo $this->lang->line("go_legend_caps"); ?>:</td>
        </tr>
    <?php
		foreach (explode(' ',trim($SUMstatuses)) as $n => $item)
		{
			if (preg_match("/^0$|2$|4$|6$|8$/",$n))
			{
				echo "<tr>";
			}
			echo "<td style=\"font-size:11px;width:5px;\">&nbsp;$item&nbsp;</td><td style=\"font-size:11px;width:5px;\">=</td><td style=\"font-size:11px;\">&nbsp;".$status[$item]."&nbsp;</td>";
			if (preg_match("/^1$|3$|5$|7$|9$/",$n))
			{
				echo "</tr>";
			}
		}
	?>
    </table>
    <?php
    }
    
    if (isset($campaign_id) && !preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id) &&  count($BOTsorted_output) > 0) {
    ?>
    <p style="font-size:5px;">&nbsp;</p>
    <table border="0" align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="padding-right:10px;">
    <table border="0" align="center" cellpadding="1" cellspacing="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
      <tr style="background-color:#FFFFFF;" class="style2">
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_full_name"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_id"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_total_small"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_non_pause"); ?>&nbsp; </strong></div></td>
        <td nowrap><div align="center" class="style4"><strong> &nbsp;<? echo $this->lang->line("go_pause"); ?>&nbsp; </strong></div></td>
      </tr>
    <?php
        if (count($BOTsorted_output) > 0) {
        for ($i=0;$i<count($BOTsorted_output);$i++) {
            echo $BOTsorted_output[$i];
        }
    ?>
      <tr style="background-color:#FFFFFF;">
        <td style="border-top:#D0D0D0 dashed 1px;"><div align="left" class="style4" style="font-size:10px">&nbsp; <strong><? echo $this->lang->line("go_total"); ?></strong> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="left" class="style4" style="font-size:10px">&nbsp; <strong><? echo $this->lang->line("go_agents"); ?>:</strong> <?=$TOT_AGENTS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotTOTAL_MS; ?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotNONPAUSE_MS?> &nbsp;</div></td>
        <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="right" class="style4" style="font-size:10px">&nbsp; <?=$TOTtotPAUSEB_MS?> &nbsp;</div></td>
      </tr>
    <?php
        } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="5" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><? echo $this->lang->line("go_no_agents_found_time_given"); ?></div></td>
      </tr>
    <?php
        }
    ?>
    </table>
    </td><td valign="top" style="padding-left:10px;">
    <table border="0" align="center" cellpadding="1" cellspacing="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
    <tr style="background-color:#FFFFFF;">
    <?php
        echo $SstatusesBOT;
    ?>
    </tr>
    <?php
        if (count($SstatusesBOTR) > 0) {
        for ($i=0;$i<count($SstatusesBOTR);$i++) {
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			
            echo "<tr style=\"background-color:$bgcolor;\">$SstatusesBOTR[$i]</tr>";
        }
    ?>
    <tr style="background-color:#FFFFFF;" class="style2">
    <?php
        echo $SstatusesBSUM;
    ?>
    </tr>
    <?php
        } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="5" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><? echo $this->lang->line("go_no_agents_found_time_given"); ?></div></td>
      </tr>
    <?php
        }
    ?>
    </table>
    </td></tr></table>
    <?php
    }
	
    if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		if (count($TOPsorted_output)) {
			echo '<span id="export" class="exporttab">'.$this->lang->line("go_export_csv").'</span>';
		}
	}
	
	if (count($TOPsorted_output) == 0 || preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";	
	}
}
?>
<!-- End Agent Performance Detail -->

<!-- Start Dial Statuses Summary -->
<? 
if ($pagetitle == "dispo") {
	if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		echo $TOPsorted_output;
	} else {
?>
    <table align="center" cellpadding="1" cellspacing="1" style="width:50%;border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
      <tr class="style2">
        <td><div align="center" class="style1 style3"><strong><? echo $this->lang->line("go_statuses"); ?></strong> </div></td>
        <td><div align="center" class="style3"><strong><? echo $this->lang->line("go_description"); ?></strong></div></td>
        <td><div align="center" class="style3"><strong><? echo $this->lang->line("go_sub_total"); ?> </strong></div></td>
      </tr>
      <tr style="background-color:#EFFBEF;">
        <td colspan="3" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style1"><? echo $this->lang->line("go_pls_sel_camp"); ?></div></td>
      </tr>
    </table>
<?	
	}
	
	if ($SUMstatuses < 10 && $SUMstatuses != 0) {
		for ($i=$SUMstatuses; $i<10; $i++) {
			echo "<br style=\"font-size:16px;\" />";	
		}
	}
	
	if (preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";	
	}
}
?>
<!-- End Dial Statuses Summary -->

<!-- Start Sales Per Agent -->
<? 
if ($pagetitle == "sales_agent") {
?>
<br style="font-size:15px" />
<div align="center"><strong>:: <? echo $this->lang->line("go_outbound_caps"); ?> ::</strong></div>
<br style="font-size:5px" />
<table width="400" border="0" align="center" cellpadding="1" cellspacing="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
  <tr>
    <td nowrap style="text-transform:uppercase"><div align="center" class="style3"><strong>&nbsp;<? echo $this->lang->line("go_agents_name"); ?>&nbsp;</strong></div></td>
    <td nowrap style="text-transform:uppercase"><div align="center" class="style3"><strong>&nbsp;<? echo $this->lang->line("go_agents_id"); ?>&nbsp;</strong></div></td>
    <td nowrap style="text-transform:uppercase" width="120px"><div align="center" class="style3"><strong>&nbsp;<? echo $this->lang->line("go_sales_count"); ?>&nbsp;</strong></div></td>
<!--    <td nowrap style="text-transform:uppercase"><div align="center" class="style2"><strong>&nbsp;Sale1 Count&nbsp;</strong></div></td>
    <td nowrap style="text-transform:uppercase"><div align="center" class="style2"><strong>&nbsp;Sale2 Count&nbsp;</strong></div></td>-->
<!--    <td nowrap style="text-transform:uppercase"><div align="center" class="style2"><strong>&nbsp;TOTAL SALES COUNT&nbsp;</strong></div></td>-->
  </tr>
<?
	if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		if ($TOToutbound) {
			echo $TOPsorted_output;
?>
            <tr>
                <td nowrap colspan="2" style="text-transform:uppercase;border-top:#D0D0D0 dashed 1px;"><div class="style3"><strong>&nbsp;<? echo $this->lang->line("go_total"); ?>&nbsp;</strong></div></td>
                <td nowrap width="120" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><strong>&nbsp;<?php echo $TOToutbound; ?>&nbsp;</strong></div></td>
                <!--    <td nowrap width="120"><div align="center" class="style2"><strong>&nbsp;<?php //echo $total_sales1; ?>&nbsp;</strong></div></td>
                <td nowrap width="120"><div align="center" class="style2"><strong>&nbsp;<?php //echo $total_sales2; ?>&nbsp;</strong></div></td>
                <td nowrap width="120"><div align="center" class="style2"><strong>&nbsp;<?php //echo $total_sales1+$total_sales2; ?>&nbsp;</strong></div></td>-->
            </tr>
<?
		} else {
?>
            <tr style="background-color:#EFFBEF;">
            	<td colspan="3" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4" style="color:red;font-style:italic;font-weight:bold;"><? echo $this->lang->line("go_no_records_found"); ?></div></td>
            </tr>
<?
		}
	} else {
?>
		  <tr style="background-color:#EFFBEF;">
			<td colspan="3" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><? echo $this->lang->line("go_pls_sel_camp"); ?></div></td>
		  </tr>
		<?
	}
?>
</table>

<br style="font-size:30px" />
<div align="center"><strong>:: <? echo $this->lang->line("go_inbound_caps"); ?> ::</strong></div>
<br style="font-size:5px" />
<table width="400" border="0" align="center" cellpadding="1" cellspacing="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
  <tr>
    <td nowrap style="text-transform:uppercase"><div align="center" class="style3"><strong>&nbsp;<? echo $this->lang->line("go_agents_name"); ?>&nbsp;</strong></div></td>
    <td nowrap style="text-transform:uppercase"><div align="center" class="style3"><strong>&nbsp;<? echo $this->lang->line("go_agent_id"); ?>&nbsp;</strong></div></td>
    <td nowrap style="text-transform:uppercase" width="120px"><div align="center" class="style3"><strong>&nbsp;<? echo $this->lang->line("go_sales_count"); ?>&nbsp;</strong></div></td>
  </tr>
<?
	if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		if ($TOTinbound) {
			echo $BOTsorted_output;
?>
            <tr>
                <td nowrap colspan="2" style="text-transform:uppercase;border-top:#D0D0D0 dashed 1px;"><div class="style3"><strong>&nbsp;<? echo $this->lang->line("go_total"); ?>&nbsp;</strong></div></td>
                <td nowrap width="120" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><strong>&nbsp;<?php echo $TOTinbound; ?>&nbsp;</strong></div></td>
            </tr>
<?
		} else {
?>
            <tr style="background-color:#EFFBEF;">
            	<td colspan="3" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4" style="color:red;font-style:italic;font-weight:bold;"><? echo $this->lang->line("go_no_records_found"); ?></div></td>
            </tr>
<?
		}
	} else {
?>
		  <tr style="background-color:#EFFBEF;">
			<td colspan="3" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><? echo $this->lang->line("go_pls_sel_camp"); ?></div></td>
		  </tr>
		<?
	}
?>
</table>
<?
    if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		if (count($TOPsorted_output) || count($BOTsorted_output)) {
			echo '<span id="export" class="exporttab">'.$this->lang->line("go_export_csv").'</span>';
		}
	}

	if (($TOToutbound < 1 || $TOTinbound < 1) || preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
			echo "<br /><br /><br /><br /><br />";
	}
}
?>
<!-- End Sales Per Agent -->

<!-- Start Sales Tracker Outbound/Inbound -->
<?
if ($pagetitle == "sales_tracker") {
?>
<br style="font-size:15px" />
<div align="center" style="text-transform:uppercase;" id="page_title"><strong>:: <? echo $request; ?> ::</strong></div>
<br style="font-size:5px" />
    <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" style="width:95%; border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
      <tr class="style2">
        <td nowrap><div align="center" class="style2"><strong>&nbsp;<? echo $this->lang->line("go_sale"); ?> #&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;<? echo $this->lang->line("go_call_date_time"); ?>&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;<? echo $this->lang->line("go_agent"); ?>&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;<? echo $this->lang->line("go_phone_number"); ?>&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;<? echo $this->lang->line("go_first_name"); ?>&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;<? echo $this->lang->line("go_last_name"); ?>&nbsp;</strong></div></td>
<!--        <td nowrap><div align="center" class="style2"><strong>&nbsp;Address&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;City&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;State&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;Postal Code&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;Email&nbsp;</strong></div></td>
        <td nowrap><div align="center" class="style2"><strong>&nbsp;Alt Number&nbsp;</strong></div></td>-->
        <td nowrap><div align="center" class="style2"><strong>&nbsp;<? echo $this->lang->line("go_info"); ?>&nbsp;</strong></div></td>
      </tr>
    <?php
    if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
        if ($TOPsorted_output) {
            foreach ($TOPsorted_output as $i => $row) {
				if ($x==0) {
					$bgcolor = "#E0F8E0";
					$x=1;
				} else {
					$bgcolor = "#EFFBEF";
					$x=0;
				}
    ?>
              <tr style="background-color:<? echo $bgcolor; ?>">
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $i+1; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo date("Y-m-d h:i:s A",strtotime($row->call_date)); ?>&nbsp;</div></td>
                <td style="text-transform:uppercase;border-top:#D0D0D0 dashed 1px;"><div class="style2" align="center">&nbsp;<?php echo $row->agent; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $row->phone_number; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $row->first_name; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $row->last_name; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<img src="<?php echo $base; ?>img/status_display_i.png" style="width:12px;cursor:pointer;" onclick="viewInfo('<?php echo $row->phone_number; ?>')" class="toolTip" title="<? echo $this->lang->line("go_click_more_info"); ?>" />&nbsp;</div></td>
<!--                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $row->city; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $row->state; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $row->postal; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $row->email; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2">&nbsp;<?php echo $row->alt_phone; ?>&nbsp;</div></td>
                <td style="border-top:#D0D0D0 dashed 1px; cursor:pointer;" class="toolTip" title="<?php echo (strlen($row->comments) > 0) ? $row->comments : "Comment not available."; ?>"><div align="center" class="style2">&nbsp;VIEW COMMENTS&nbsp;</div></td>-->
              </tr>
    <?php
            }
        } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="13" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2" style="color:red;font-style:italic;font-weight:bold;"><? echo $this->lang->line("go_no_records_found"); ?></div></td>
      </tr>
    <?php
        }
    } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="13" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2"><? echo $this->lang->line("go_pls_sel_camp"); ?></div></td>
      </tr>
    <?php
    }
    ?>
    </table>
<?
	if ($TOPsorted_output) {
		foreach ($TOPsorted_output as $row) {
?>
            <!-- Overlay1 -->
            <div id="overlay<?php echo $row->phone_number; ?>" style="display:none;"></div>
            <div id="box<?php echo $row->phone_number; ?>">
            <a class="closebox toolTip" title="<? echo $this->lang->line("go_close"); ?>"></a>
            <div>
                <table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_lead_id"); ?>:</td><td>&nbsp;<?php echo $row->lead_id; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_phone_number"); ?>:</td><td>&nbsp;<?php echo $row->phone_number; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_first_name"); ?>:</td><td>&nbsp;<?php echo $row->first_name; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_last_name"); ?>:</td><td>&nbsp;<?php echo $row->last_name; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_call_date_time"); ?>:</td><td>&nbsp;<?php echo date("Y-m-d h:i:s A",strtotime($row->call_date)); ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_agent"); ?>:</td><td>&nbsp;<?php echo $row->agent; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_address"); ?>:</td><td>&nbsp;<?php echo $row->address; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_city"); ?>:</td><td>&nbsp;<?php echo $row->city; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap<? echo $this->lang->line("go_state"); ?>>:</td><td>&nbsp;<?php echo $row->state; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_postal_code"); ?>:</td><td>&nbsp;<?php echo $row->postal; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_email"); ?>:</td><td>&nbsp;<?php echo $row->email; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_alt_phone"); ?>:</td><td>&nbsp;<?php echo $row->alt_phone; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight:bold;width:40%;" nowrap><? echo $this->lang->line("go_comments"); ?>:</td><td>&nbsp;<?php echo $row->comments; ?></td>
                    </tr>
                </table>
            </div>
            </div>
<?php
		}
	}
	
    if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
                if (count($TOPsorted_output)) {
                        echo '<span id="export" class="exporttab">'.$this->lang->line("go_export_csv").'</span>';
                }
        }

        if (count($TOPsorted_output) < 12 && count($TOPsorted_output) != 0) {
                for ($i=count($TOPsorted_output); $i<12; $i++) {
                        echo "<br style=\"font-size:16px;\" />";
                }
        }

        if (count($TOPsorted_output) < 1 || preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
                echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
        }
}
?>
<!-- End Sales Tracker Outbound/Inbound -->

<!-- Start Inbound Call Report -->
<? if ($pagetitle == "inbound_report") {
        if (count($TOPsorted_output)) {
?>
        <br />
        <div align="center" class="style4"><? echo $this->lang->line("go_search_done"); ?> <strong><? echo count($TOPsorted_output) ?></strong> <? echo $this->lang->line("go_inbound_calls_found"); ?></div>
    <br />
<?
        }
?>
    <table width="800" border="0" cellspacing="1" cellpadding="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
      <tr>
        <td style="text-transform:uppercase;" class="style2"><div align="center"><strong>&nbsp; # &nbsp;</strong></div></td>
        <td style="text-transform:uppercase;" class="style2"><div align="center"><strong>&nbsp; <? echo $this->lang->line("go_date"); ?> &nbsp;</strong></div></td>
        <td style="text-transform:uppercase;" class="style2"><div align="center"><strong>&nbsp; <? echo $this->lang->line("go_agent_id"); ?> &nbsp;</strong></div></td>
        <td style="text-transform:uppercase;" class="style2"><div align="center"><strong>&nbsp; <? echo $this->lang->line("go_phone_number"); ?> &nbsp;</strong></div></td>
        <td style="text-transform:uppercase;" class="style2"><div align="center"><strong>&nbsp; <? echo $this->lang->line("go_time"); ?> &nbsp;</strong></div></td>
        <td style="text-transform:uppercase;" class="style2"><div align="center"><strong>&nbsp; <? echo $this->lang->line("go_call_duration_in_sec"); ?> &nbsp;</strong></div></td>
        <td style="text-transform:uppercase;" class="style2"><div align="center"><strong>&nbsp; <? echo $this->lang->line("go_disposition"); ?> &nbsp;</strong></div></td>
      </tr>

    <?
    if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		if (count($TOPsorted_output)) {
			foreach ($TOPsorted_output as $i => $item) {
				list($ldate, $ltime) = split(' ',$item->call_date);
				if ($c == 1) {
					$bgcolor = "#EFFBEF";
					$c = 0;
				} else {
					$bgcolor = "#E0F8E0";
					$c = 1;
				}
				$phone_number = ($item->phone_number != "") ? $item->phone_number : "{$this->lang->line("go_not_registered")}";
				echo '  <tr style="background-color: '.$bgcolor.';">';
				echo '    <td style="border-top:#D0D0D0 dashed 1px;"><div align="center">'.($i+1).'</div></td>';
				echo '    <td style="border-top:#D0D0D0 dashed 1px;"><div align="center">'.$ldate.'</div></td>';
				echo '    <td style="border-top:#D0D0D0 dashed 1px;">'.$item->user.'</div></td>';
				echo '    <td style="border-top:#D0D0D0 dashed 1px;">'.$phone_number.'</div></td>';
				echo '    <td style="border-top:#D0D0D0 dashed 1px;"><div align="center">'.$ltime.'</div></td>';
				echo '    <td style="border-top:#D0D0D0 dashed 1px;"><div align="center">'.$item->length_in_sec.'</div></td>';
				echo '    <td style="border-top:#D0D0D0 dashed 1px;"><div align="center">'.$item->status.'</div></td>';
				echo '  </tr>';
			}
		} else {
		?>
		  <tr style="background-color:#EFFBEF;">
			<td colspan="7" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2" style="color:red;font-style:italic;font-weight:bold;"><? echo $this->lang->line("go_no_records_found"); ?></div></td>
		  </tr>
		<?php
		}
    } else {
    ?>
      <tr style="background-color:#EFFBEF;">
        <td colspan="13" style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style2"><? echo $this->lang->line("go_pls_sel_camp"); ?></div></td>
      </tr>
    <?php
    }
    
    ?>
    </table>
<?
    if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		if (count($TOPsorted_output)) {
			echo '<span id="export" class="exporttab">'.$this->lang->line("go_export_csv").'</span>';
		}
	}

	if (count($TOPsorted_output) < 12 && count($TOPsorted_output) != 0) {
		for ($i=count($TOPsorted_output); $i<12; $i++) {
			echo "<br style=\"font-size:16px;\" />";	
		}
	}
	
	if (count($TOPsorted_output) < 1 || preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
		echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";	
	}
}
?>
<!-- End Inbound Call Report -->

<!-- Start Call Export Report -->
<?
if ($pagetitle == "call_export_report") {
?>
<script>
$('#submit_export').click(function()
{
	var campaigns = '';
	var pagetitle = '<? echo $pagetitle; ?>';
	var from_date = $('#selected_from_date').html();
	var to_date = $('#selected_to_date').html();
	var request = $('#header_row').val() + ',' + $('#rec_fields').val() + ',' + $('#custom_fields').val() + ',' + $('#call_notes').val() + ',' + $('#export_fields').val();

        if ($('#campaign').val() == null) {
                alert("<? echo $this->lang->line('go_pls_sel_one_more_camp'); ?>"); //("Please select one or more campaign from the list.");
                return false;
        }

	$('#campaign option:selected').each(function()
	{
		campaigns += $(this).text() + "+";
	});
	
	campaigns += ",";
	$('#group option:selected').each(function()
	{
		campaigns += $(this).text() + "+";
	});
	
	campaigns += ",";
	$('#list_id option:selected').each(function()
	{
		campaigns += $(this).text() + "+";
	});
	
	campaigns += ",";
	$('#status option:selected').each(function()
	{
		campaigns += $(this).text() + "+";
	});
	
	if (campaigns.length > 3) {
		console.log('<? echo $base; ?>index.php/go_site/go_export_reports/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaigns + '/' + request + '/');
		window.location = '<? echo $base; ?>index.php/go_site/go_export_reports/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaigns + '/' + request + '/';
		return false;
	}
});

$('#submit_export').hover(
	function() {
		$(this).addClass('tabhover');
	},
	function() {
		$(this).removeClass('tabhover');
});
</script>
<?
	echo "<CENTER><BR>\n";
        echo "<FONT SIZE=3 FACE=\"Arial,Helvetica\"><B>{$this->lang->line("go_exports_calls_report")}</B></FONT><BR><BR>\n";
//      echo "<INPUT TYPE=HIDDEN id=DB VALUE=\"$DB\">";
//      echo "<INPUT TYPE=HIDDEN id=run_export VALUE=\"1\">";
//      echo "<INPUT TYPE=HIDDEN id=accountcode VALUE=\"$accountcode\">";
        echo "<TABLE BORDER=0 CELLSPACING=8><TR><TD ALIGN=LEFT VALIGN=TOP ROWSPAN=2>\n";

        echo "<B>{$this->lang->line("go_header_row")}:</B><BR>\n";
        echo "<select size=1 id=header_row><option selected>{$this->lang->line("go_yes")}</option><option>{$this->lang->line("go_no")}</option></select>\n";

        echo "<BR><BR>\n";

        echo "<B>{$this->lang->line("go_recording_fields")}:</B><BR>\n";
        echo "<select size=1 id=rec_fields>";
        echo "<option>{$this->lang->line("go_id")}</option>";
        echo "<option>{$this->lang->line("go_filename")}</option>";
        echo "<option>{$this->lang->line("go_location")}</option>";
        echo "<option>{$this->lang->line("go_all")}</option>";
        echo "<option selected>{$this->lang->line("go_none")}</option>";
	echo "</select>\n";

	if ($custom_fields_enabled > 0)
		{
		echo "<BR><BR>\n";

                echo "<B>{$this->lang->line("go_custome_fields")}:</B><BR>\n";
                echo "<select size=1 id=custom_fields><option>{$this->lang->line("go_yes")}</option><option selected>{$this->lang->line("go_no")}</option></select>\n";
                }

        echo "<BR><BR>\n";

        echo "<B>{$this->lang->line("go_per_call_notes")}:</B><BR>\n";
        echo "<select size=1 id=call_notes><option>{$this->lang->line("go_yes")}</option><option selected>{$this->lang->line("go_no")}</option></select>\n";

        echo "<BR><BR>\n";

        echo "<B>{$this->lang->line("go_export_fields")}:</B><BR>\n";
        echo "<select size=1 id=export_fields><option selected>{$this->lang->line("go_standard_caps")}</option><option>{$this->lang->line("go_extended")}</option></select>\n";

        ### bottom of first column

        echo "</TD><TD ALIGN=LEFT VALIGN=TOP ROWSPAN=2>\n";
        echo "<font class=\"select_bold\"><B>{$this->lang->line("go_campaigns")}:</B></font><BR><CENTER>\n";
        echo "<SELECT SIZE=15 ID=campaign multiple>\n";
        $LISTcampaigns = explode(",", $allowed_campaigns);
                for ($i=0; $i<count($LISTcampaigns); $i++)
                {
                        if (ereg("\|$LISTcampaigns[$i]\|",$campaign_string))
                                {echo "<option selected value=\"$LISTcampaigns[$i]\">$LISTcampaigns[$i]</option>\n";}
                        else
                                {echo "<option value=\"$LISTcampaigns[$i]\">$LISTcampaigns[$i]</option>\n";}
                }
        echo "</SELECT>\n";

        echo "</TD><TD ALIGN=LEFT VALIGN=TOP ROWSPAN=3>\n";
        echo "<font class=\"select_bold\"><B>{$this->lang->line("go_inbound_groups")}:</B></font><BR><CENTER>\n";
        echo "<SELECT SIZE=15 ID=group multiple>\n";
        echo "<option value=\"--".$this->lang->line('go_none')."--\" selected>--{$this->lang->line("go_none")}--</option>\n";
	sort($inbound_groups);
		foreach ($inbound_groups as $LISTgroups)
		{
			if (ereg("\|".$LISTgroups->group_id."\|",$group_string)) 
				{echo "<option selected value=\"".$LISTgroups->group_id."\">".$LISTgroups->group_id."</option>\n";}
			else
				{echo "<option value=\"".$LISTgroups->group_id."\">".$LISTgroups->group_id."</option>\n";}
		}
	echo "</SELECT>\n";
	echo "</TD><TD ALIGN=LEFT VALIGN=TOP ROWSPAN=3>\n";
        echo "<font class=\"select_bold\"><B>{$this->lang->line("go_lists")}:</B></font><BR><CENTER>\n";
        echo "<SELECT SIZE=15 ID=list_id multiple>\n";
        echo "<option value=\"--".$this->lang->line('go_all')."--\" selected>--{$this->lang->line("go_all")}--</option>\n";
                foreach ($lists_to_print as $LISTlists)
                {
                        if (ereg("\|".$LISTlists->list_id."\|",$list_string))
                                {echo "<option selected value=\"".$LISTlists->list_id."\">".$LISTlists->list_id."</option>\n";}
                        else
                                {echo "<option value=\"".$LISTlists->list_id."\">".$LISTlists->list_id."</option>\n";}
                }
        echo "</SELECT>\n";
        echo "</TD><TD ALIGN=LEFT VALIGN=TOP ROWSPAN=3>\n";
        echo "<font class=\"select_bold\"><B>{$this->lang->line("go_statuses")}:</B></font><BR><CENTER>\n";
        echo "<SELECT SIZE=15 ID=status multiple>\n";
        echo "<option value=\"--".$this->lang->line('go_all')."--\" selected>--{$this->lang->line("go_all")}--</option>\n";
        sort($statuses_to_print);
                foreach ($statuses_to_print as $LISTstatus)
                {
                        if (ereg("\|".$LISTstatus->status."\|",$list_string))
                                {echo "<option selected value=\"".$LISTstatus->status."\">".$LISTstatus->status."</option>\n";}
                        else
                                {echo "<option value=\"".$LISTstatus->status."\">".$LISTstatus->status."</option>\n";}
                }
        echo "</SELECT>\n";

        echo "</TD></TR><TR><TD ALIGN=LEFT VALIGN=TOP COLSPAN=2> &nbsp; \n";

        echo "</TD></TR><TR><TD ALIGN=CENTER VALIGN=TOP COLSPAN=5>\n";
        echo "<INPUT TYPE=SUBMIT VALUE=\"".$this->lang->line("go_submit")."\" STYLE=\"cursor:pointer;\" id=\"submit_export\">\n";
	echo "</TD></TR></TABLE>\n";
}
?>
<!-- End Call Export Report -->

<!-- Start Dashboard -->
<?
if ($pagetitle=="dashboard") {
	if (!$isGraph)
	{
                if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
?>
        <table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><table width="100%"  border="0" cellspacing="1" cellpadding="1" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; cursor:default;">
              <tr class="style1">
                <td colspan="3" nowrap><div align="center"><strong><? echo $this->lang->line("go_dialer_calls_caps"); ?> </strong></div></td>
                </tr>
              <tr class="style1">
                <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><strong><? echo $this->lang->line("go_dispo_code"); ?> </strong></div></td>
                <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><strong><? echo $this->lang->line("go_dispo_name"); ?> </strong></div></td>
                <td nowrap style="border-top:#D0D0D0 dashed 1px;"><div align="center" class="style4"><strong><? echo $this->lang->line("go_count"); ?></strong></div></td>
              </tr>
        <?php echo $total_dialer_calls_output; ?>
              <tr>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center"><span class="style3"><strong>&nbsp;<? echo $this->lang->line("go_sub_total"); ?>&nbsp;</strong></span></div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center"><span class="style3"></span></div></td>
                <td style="border-top:#D0D0D0 dashed 1px;"><div align="center"><span class="style3"><strong>&nbsp;<?php echo $total_dialer_calls; ?>&nbsp;</strong></span></div></td>
              </tr>
            </table></td>
          </tr>
        </table>
<?php
		}
	} else {
?>
		<table style="margin-left: 20px;" width="100%">
			<tbody>
			<tr>
				<td><div id="graph_dashboard" style="width:95%;height:250px;"></div></td>
					<!--<td class="c"><a class="" href=""><? //echo $inbound_sph; ?></a></td>
					<td class="r"><a class="" href="">Outbound Sales / Hour</a></td>-->
			</tr>
			</tbody>
		</table>
		<script>
		$(function() {
			var options = {
				colors: ["#565656"],
				legend: { show: false },
				lines: { show: true },
				points: { show: false },
				xaxis: { <?php echo $xMin; ?> tickDecimals: 0,  tickSize: 1, ticks: [<?php echo (count($data_ticks) > 0) ? implode(',',$data_ticks) : "[0,\"\"],[1,\"\"]"; ?>]},
				yaxis: { min: 0 },
				shadowSize: 7,
				grid: { borderWidth: 0, hoverable: true, clickable: true }
			};

			<?php
			if (count($data_graph) > 0)
			{
			?>
			var data_calls = { data: [<?php echo implode(',', $data_graph); ?>] };
			<?php
			} else {
			?>
			var data_calls = { data: [""] };
			<?php
			}
			?>
			var data_codes = <?php echo json_encode($data_codes); ?>;
			var data_json = '';
			var graph_dashboard = $("#graph_dashboard");
			var currentdate = "";

			var plot_graph = $.plot(graph_dashboard, [ data_calls ], options);

			function showTooltip(x, y, contents) {
				$('<div id="tooltip">' + contents + '</div>').css( {
					position: 'absolute',
					display: 'none',
					top: y + 5,
					left: x + 5,
					border: '1px solid #e7e7e7',
					padding: '2px',
					'font-size': '16px',
					'background-color': '#e7e7e7',
					opacity: 0.90
				}).appendTo("body").fadeIn(200);
			}

			$('#cnt-reports').resize(function()
			{
				graph_dashboard.width($('#cnt-reports').width() - 100);
			});

			graph_dashboard.bind("plothover", function (event, pos, item) {
				$("#x").text(pos.x.toFixed(2));
				$("#y").text(pos.y.toFixed(2));

		//         if ($("#enableTooltip:checked").length > 0) {
					if (item) {
						if (previousPoint != item.dataIndex) {
							previousPoint = item.dataIndex;
							
							$("#tooltip").remove();
							var x = item.datapoint[0].toFixed(0),
								y = item.datapoint[1].toFixed(0);
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
							
							showTooltip(item.pageX, item.pageY,data_codes[x] + " &raquo; " + y + " calls ");
						}
					}
					else {
						$("#tooltip").remove();
						previousPoint = null;
					}
		//         }
			});
		});
		</script>
		<?php
	}

    if (!preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id)) {
        if (strlen($TOPsorted_output) > 100)
        {
        ?>
        <br /><br />
        <table border=0 width="100%">
        <tr><td valign="top" align="right" rowspan="2">
            <table width="100%"  border="0" align="center" cellpadding="1" cellspacing="1" style="cursor:default;">
              <tr>
                <td nowrap colspan="2" style="border-bottom:1px #ececec solid;padding:5px 10px 10px;text-align:left;color:#777;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;font-style:italic;font-size:13px;"><? echo $this->lang->line("go_contact_rate"); ?></td>
              </tr>
              <tr>
                <td nowrap class="b" style="padding-left:50px;"><a style="cursor:pointer"><?php echo ($total_calls>0) ? round(($total_contacts/$total_calls)*100,2) : "0"; ?>%</a></td>
                <td nowrap class="t" width="50"><a style="cursor:pointer"><? echo $this->lang->line("go_contact_rate"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo $total_calls; ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_total_contacts"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo $total_contacts; ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_total_contacts"); ?></a></td>
              </tr>
              <tr>
                <td nowrap colspan="2" style="border-bottom:1px #ececec solid;padding:5px 10px 10px;text-align:left;color:#777;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;font-style:italic;font-size:13px;"><? echo $this->lang->line("go_sales_rate"); ?></td>
              </tr>
              <tr>
                <td nowrap class="b" style="padding-left:50px;"><a style="cursor:pointer"><?php echo ($total_contacts>0) ? round(($total_sales/$total_contacts)*100,2) : "0"; ?>%</a></td>
                <td nowrap class="t"><a style="cursor:pointer"><? echo $this->lang->line("go_sales_rate"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo $total_sales; ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_total_sales"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo (round($total_login_hours/3600,2)>0) ? round(($total_sales/round($total_login_hours/3600,2)),2) : "0"; ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_sales_per_hour"); ?></a></td>
              </tr>
              <tr>
                <td nowrap colspan="2" style="border-bottom:1px #ececec solid;padding:5px 10px 10px;text-align:left;color:#777;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;font-style:italic;font-size:13px;"><? echo $this->lang->line("go_other_stats"); ?></td>
              </tr>
              <tr>
                <td nowrap class="b" style="padding-left:50px;"><a style="cursor:pointer"><?php echo ($total_sales>0) ? round(($total_xfer/$total_sales)*100,2) : "0"; ?>%</a></td>
                <td nowrap class="t"><a style="cursor:pointer"><? echo $this->lang->line("go_transfer_sales_rate"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo $total_xfer; ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_total_transfer"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo (round($total_login_hours/3600,2)>0) ? round(($total_xfer/round($total_login_hours/3600,2)),2) : "0"; ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_transfer_per_hour"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo $total_notinterested; ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_not_interested"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo $total_callbacks; ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_callbacks"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo ($total_login_hours>0) ? round(($total_talk_hours/$total_login_hours)*100,2) : "0"; ?>%</a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_utilization"); ?></a></td>
              </tr>
              <tr>
                <td nowrap class="c"><a style="cursor:pointer"><?php echo round($total_login_hours/3600,2); ?></a></td>
                <td nowrap class="r"><a style="cursor:pointer"><? echo $this->lang->line("go_camp_hours"); ?></a></td>
              </tr>
            </table>
        </td><td valign="top" style="height:400px;">
        	<div id="interactive" style="width:640px; height:400px; border: 0px dashed gainsboro;"></div>
        </td></tr>
        <tr><td align="left" valign="top">
        <div id="hover" style="padding-left:115px;margin-top:-30px;">&nbsp;</div>
        </td></tr>
        </table>
        <br />

		<!-- Table of Agent Dispositions -->
        <table width="300" border="0" align="center" cellpadding="1" cellspacing="0" style="border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; cursor:default;">
          <tr>
            <td nowrap><div align="center" class="style4 style8">&nbsp;<strong><? echo $this->lang->line("go_agent_name_caps"); ?></strong>&nbsp;</div></td>
            <? echo $TOPsorted_output; ?>
        </table>
		<script type="text/javascript">
			$(function () {
				// Pie Chart
				var data = [];
				var series = <?php echo count($SUMstatuses); ?>;
				var statuses = ["<?php echo join("\", \"", $SstatusesTOP); ?>"];
				var status = [<?php echo join(", ", $SUMstatuses); ?>];
				
				for( var i = 0; i<series; i++)
				{
					if (status[i] > 0) {
						data[i] = { label: statuses[i], data: status[i] }
					} else {
						data[i] = { label: "", data: status[i] }
					}
				}
				
				$.plot($("#interactive"), data,
				{
						series: {
							pie: {
								show: true,
								radius: 6/8,
								offset: {
									left: -100
								}
							}
						},
						grid: {
							hoverable: true,
							clickable: false
						}
				});
				$("#interactive").bind("plothover", pieHover);
				$("#interactive").bind("plotclick", pieClick);
				
				function pieHover(event, pos, obj) 
				{
					if (!obj)
					{
						$("#hover").html('');
						return;
					}
					percent = parseFloat(obj.series.percent).toFixed(2);
				// 	$("#hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' ('+percent+'%)</span>');
					$("#hover").html('<span style="font-weight: bold; color: black">'+obj.series.label+' ('+percent+'%)</span>');
				}
				
				function pieClick(event, pos, obj) 
				{
					if (!obj)
								return;
					percent = parseFloat(obj.series.percent).toFixed(2);
					alert(''+obj.series.label+': '+percent+'%');
				}
			});
		</script>
        <br /><br />
  	<?php
		}
	}
	
	if (count($TOPsorted_output) < 1 && preg_match("/{$this->lang->line("go_pls_sel_camp")}/", $campaign_id) && !$isGraph) {
		echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";	
	}
}
?>
<!-- End Dashboard -->

<!-- Start of limesurvey -->
<?if($pagetitle == "limesurvey"):?>

    <style>
        #surveys table {border-spacing:0;border-collapse:0;}
        #surveys tr.header-row{padding:3px 0 3px;white-space:normal;}
        #surveys tr.header-row th{border-bottom:1px dashed #D0D0D0;text-align:left;}
        #surveys tbody tr td{white-space:normal;padding:3.5px;}
        #surveys tbody tr.odd td{border-bottom:1px dashed #D0D0D0;} 
        #surveys tbody tr.even td{border-bottom:1px dashed #D0D0D0;} 
        #surveys tbody tr.odd{background-color:#E0F8E0;}
        #surveys tbody tr.even{background-color:#EFFBEF;}
        a.download {color:#7A9E22;display:none;}
        a.download:hover {font-weight:bold;}
    </style>

    <table width="100%">
        <tr>
           <td width="5%">Survey:</td><td><?=form_dropdown('survey',$survey,null,'id="survey"')?></td>
        </tr>
        <tr>
           <td colspan="2">
                <table id="surveys" style="width:100%;border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;display:none;">
                    <thead><tr class="header-row"></tr></thead>
                    <tbody></tbody>
                </table>
           </td>
        </tr>
        <tr>
           <td colspan="2" style="text-align:right;"><a href="" class="download"><? echo $this->lang->line("go_export_csv"); ?></a></td>
        </tr>
    </table>
    <script>
           $(function(){

                $("#survey").change(function(){
                     if($(this).val() !== "0"){
                            display();
                     } else {
                             $("#surveys").hide();
                             $("#surveys > thead > tr").empty();
                             $("#surveys > tbody").empty();
                     }
                });

                $("#surveys").tablePagination();
               
           });
           

           function display(){
                    $.post(
                                 '<?=$base?>index.php/go_site/display_surveys',
                                 {surveyid:$("#survey").val(),daterange:$("#widgetDate > span").text()},
                                 function(data){


                                       if(data !== "null"){

                                           $("#surveys > thead > tr").empty();
                                           $("#surveys > tbody").empty();
                                           $(".download").attr("href","").hide();  
                                           $("#surveys").show();
                                           var $result = JSON.parse(data);
                                           // create headers
                                           $.each($result.header,function(indx,val){
                                                $("#surveys > thead > tr").append("<th>"+val+"</th>");
                                           });
                                           // create content
                                           $.each($result.content,function(indx,val){
                                               $("#surveys > tbody").append("<tr id='"+ indx +"' class='"+ ((indx % 2) == 0 ? "even" : "odd" ) +"'></tr>");
                                               $.each(val,function(val_indx,val_value){
                                                    $("#surveys > tbody > tr#"+indx).append("<td>"+val_value+"</td>");
                                               });
                                           });
                                           var download_var = "<?=$base?>index.php/go_script_ce/downloadscript/"+$("#survey").val()+"/csv";
                                           $(".download").attr("href",download_var).show();
                                       } else {
                                           $("#surveys").hide();
                                           $("#surveys > thead > tr").empty();
                                           $("#surveys > tbody").empty();
                                           $(".download").attr("href","").hide();
                                       }

                          }
                  );
           }
    </script>
<?endif;?>
<!-- End Limesuvey -->

<!-- Start of CDR -->
<?if($pagetitle == "cdr"):?>

    <style>
        #cdr_table table {border-spacing:0;border-collapse:0;}
        #cdr_table tr.header-row{padding:3px 0 3px;white-space:normal;}
        #cdr_table tr.header-row th{border-bottom:1px dashed #D0D0D0;text-align:left;}
        #cdr_table tbody tr td{white-space:normal;padding:3.5px;}
        #cdr_table tbody tr.odd td{border-bottom:1px dashed #D0D0D0;} 
        #cdr_table tbody tr.even td{border-bottom:1px dashed #D0D0D0;} 
        #cdr_table tbody tr.odd{background-color:#E0F8E0;}
        #cdr_table tbody tr.even{background-color:#EFFBEF;}
        a.download {color:#7A9E22;}
        a.download:hover {font-weight:bold;}
    </style>

    <?=$CDR?>
    <table width="100%">
        <tr>
           <td colspan="2">
                <!--<center><img id="GOloading" src="<?//=$base?>img/goloading.gif" /></center> -->
                <table id="cdr_table" style="width:100%;border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
                    <thead><tr class="header-row"><th><? echo $this->lang->line("go_connect_time"); ?></th><th>CLI</th><th>CLD</th><th><? echo $this->lang->line("go_country"); ?></th><th><? echo $this->lang->line("go_description"); ?></th><th><? echo $this->lang->line("go_billed_duration"); ?></th><th><? echo $this->lang->line("go_cost"); ?></th></tr></thead>
                    <tbody></tbody>
                </table>
           </td>
        </tr>
        <tr>
            <td style="text-align:right;"><a class="download"><? echo $this->lang->line("go_download"); ?></a></td>
        </tr>
    </table>
    <script>
          $(function(){

               $("#select_campaign").css('visibility','hidden');
               $("#daily").hide();
               $("#weekly").hide();
               $("#monthly").hide();
               $("#table_reports > p > span:nth-child(1)").hide();
               $("#table_reports > p > span:nth-child(2)").hide();
               //$("#").empty();
               //$("#surveys > tbody").empty();

               display();
               setTimeout('$("#cdr_table").tablePagination();',5000);


               /*$("#selectDateOk").click(function(){
                    alert('test');
                    //display();
                    //setTimeout('$("#cdr_table").tablePagination();',3000);
               });*/

               var $dates = $("#widgetDate > span").text().split(" to ");
               $("a.download").attr("href","<?=$base?>index.php/go_site/downloadcdr/<?=$i_account?>/csv/"+$dates[0]+","+$dates[1]+"/");

          });

          function display(){

               $.post(
                      '<?=$base?>index.php/go_site/display_cdr',
                      {client:'<?php echo $i_account?>',daterange:$("#widgetDate > span").text()},
                      function(data){
                        //$('#GOloading').hide();
                        //$('#cdr_table').show();

                          if(data.length > 0){
                               var $result = JSON.parse(data);
                               $.each($result,function($indx,$val){
                                   $("#cdr_table > tbody").append("<tr id='"+ $indx +"' class='"+ (($indx % 2) == 0 ? "even" : "odd" ) +"'></tr>");
                                   $.each($val,function(){
                                        $("#cdr_table > tbody > tr#"+ $indx).append("<td>"+this+"</td>");
                                   });
                                   $val = "";
                               });
                          }

                      }
               );


          }

    </script>
<?endif;?>
<!-- End CDR -->


            </td>
	</tr>
    </tbody>
</table>
<div id="lastStatus" style="display:none;"></div>
<div id="lastPhone" style="display:none;"></div>
