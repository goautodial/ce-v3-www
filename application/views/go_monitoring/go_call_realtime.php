<?php
############################################################################################
####  Name:             go_call_realtime.php                                            ####
####  Type:             ci model                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
<script>
$(function()
{
    var noLiveCalls = <?php echo $no_live_calls; ?>;
    if (noLiveCalls)
    {
 	$('#realTimeMonitor').hide();
 	$('#legendMonitoring').hide();
 	$('#noAgents').show();
	//$('#realTimeMonitor').show();
	//$('#legendMonitoring').show();
	//$('#noAgents').hide();
    }
    else
    {
	$('#realTimeMonitor').show();
	$('#legendMonitoring').show();
	$('#noAgents').hide();
    }

    $('.toolTip').tipTip();

    $('#closeboxMonitor').click(function()
    {
		$('#boxMonitor').animate({'top':'-550px'},500);
		$('#overlayMonitor').fadeOut('slow');
    });

	var realtimeExpand = $("#realTimeRowsPerPage").html();
	$("#realtime_monitoring").tablePagination({rowsPerPage: realtimeExpand});
	$("#tablePagination_paginater").hide();
// 	$("#realtime_monitoring").tablesorter({sortList:[[3,1],[1,0]]});

	$("#tablePagination_rowsPerPage").change(function(){
		$("#realTimeRowsPerPage").html($(this).val());
		$("html, body").animate({ scrollTop: 0 }, 500);
	});
});

function sendMonitor(user, sessionid, serverip)
{
    $('#overlayMonitor').fadeIn('fast');
// alert(window.offset);
	$('#boxMonitor').show();
    $('#agent').text(user);
    $('#boxMonitor').css({'width': '300px', 'height': '130px', 'margin-left': '50%', 'left': '-150px', 'padding-bottom': '20px'});
    $('#boxMonitor').animate({
// 	top: Math.max(0, (($(window).height() - $('#boxMonitor').outerHeight()) / 2) + $(window).scrollTop()) + "px"
		top: "70px"
    }, 500);

    $('#session_id').val(sessionid);
    $('#server_ip').val(serverip);
}
</script>
<style type="text/css">
.tBorder{
	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
}
.tBorderSmall{
	-webkit-border-top-left-radius: 3px;
	-webkit-border-top-right-radius: 3px;
	-moz-border-radius-topleft: 3px;
	-moz-border-radius-topright: 3px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border:0px solid #90B09F;
	font-size:11px;
}
ul{
	list-style-type:disc;
	list-style-position:inside;
}
li{
	line-height:10px;
}

/* Style for overlay and box */
#overlayMonitor{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#boxMonitor{
	position:absolute;
	top:-550px;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:101;
}

#closeboxMonitor{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}
</style>
<div id=noAgents style="display:none;">
<table border=0 cellpadding=0 cellspacing=0 width="100%" align=center style="margin-top:0px;"><tr>
<td align="center">
<table border=0 cellpadding=0 cellspacing=0 style="font-family:Verdana, Arial, Helvetica, sans-serif;" align=center>
<tr>
<td style="width:7px;height:50px;"></td>
<td style="width:100px;font-size:30px;white-space:nowrap;" align=center> &nbsp; NO CALLS AVAILABLE &nbsp; </td>
<td style="width:6px;height:50px;"></td>
</tr>
</table>
</td>
</tr></table>
</div>


<div id=realTimeMonitor style="display:none;">
<div id="campTitle" style="font-weight:bold;font-size:16px;color:#333;">Call Monitoring</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />
<table id='realtime_monitoring' border=0 cellpadding=1 cellspacing=1 width="102%" align=center class="tBorderSmall" style="margin-top:0px;margin-left:-5px;border-spacing: 0px 0px;">
<?php echo $realtimeHTML; ?>
</table>
<br />
<?php
//if ($active_calls > -99) {
?>
<table style="display: none">
	<tbody>
		<br>

		<tr>

		 <td class='dp' nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		 <td class='showhide'>
		 <a id='showmore_logins' class='showmore' style='cursor: pointer;'>&nbsp;&raquo; Click here to show more... </a>
		 <a style='display: none; cursor: pointer;' id='hidemore_logins' class='hidemore'>&nbsp;&raquo; Click here to hide... </a>

		 </td>
		</tr>
	</tbody>
</table>
<?
//}
?>

<script>

    $("#showmore_logins").click(function () {
    $("#realtime_monitoring #trid").show("slow");
    $("#hidemore_logins").show("slow");
    $("#showmore_logins").hide("slow");
	$("#isExpanded").html('1');

    });


    $("#hidemore_logins").click(function () {
    $("#realtime_monitoring #trid").hide("slow");
    $("#showmore_logins").show("slow");
    $("#hidemore_logins").hide("slow");
	$("#isExpanded").html('0');

    });

	if ($('#isExpanded').html() == 0)
	{
		$("#showmore_logins").css("display","block");
		$("#hidemore_logins").css("display","none");
	}
	else
	{
		$("#hidemore_logins").css("display","block");
		$("#showmore_logins").css("display","none");
	}


    </script>
	</tbody>
</table>
</div>
<table id="legendMonitoring" cellpadding="0" cellspacing="0" style="color:#333;display:none;">
	<tr>
    	<td colspan="2">
			<small style="font-weight:bold;">LEGEND:</small>
        </td>
    </tr>
	<tr>
    	<td>
        	<span style="background-color:#BAEE62;color:black;border:#000 1px solid;font-size:5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
    	<td style="font-size:9px;padding-left:3px;">
        	Outbound Calls
        </td>
    </tr>
	<tr>
    	<td>
        	<span style="background-color:#F9F57A;color:black;border:#000 1px solid;font-size:5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
    	<td style="font-size:9px;padding-left:3px;">
        	Inbound Calls
        </td>
    </tr>
</table>
