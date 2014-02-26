<?php
########################################################################################################
####  Name:             	go_reports_body_jsloader.php   		      	                    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:	        Christopher Lomuntad                                        	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$base = base_url();

if (empty($pagetitle)) {
	$pagetitle = 'stats';
}
?>

<script type="text/javascript">
//<![CDATA[
(function(){
var c = document.body.className;
c = c.replace(/no-js/, 'js');
document.body.className = c;
})();
//]]>
</script>


<script type="text/javascript">
/* <![CDATA[ */
var commonL10n = {
	warnDelete: "You are about to permanently delete the selected items.\n  \'Cancel\' to stop, \'OK\' to delete."
};
try{convertEntities(commonL10n);}catch(e){};
var wpAjax = {
	noPerm: "You do not have permission to do that.",
	broken: "An unidentified error has occurred."
};
try{convertEntities(wpAjax);}catch(e){};
var adminCommentsL10n = {
	hotkeys_highlight_first: "",
	hotkeys_highlight_last: ""
};
var thickboxL10n = {
	next: "Next &gt;",
	prev: "&lt; Prev",
	image: "Image",
	of: "of",
	close: "Close",
	noiframes: "This feature requires inline frames. You have iframes disabled or your browser does not support them."
};
try{convertEntities(thickboxL10n);}catch(e){};
var plugininstallL10n = {
	show: false,
	plugin_information: "Plugin Information:",
	ays: "Are you sure you want to install this plugin?"
};
try{convertEntities(plugininstallL10n);}catch(e){};
/* ]]> */
</script>
<script type="text/javascript" src="<? echo $base; ?>js/go_fload_scripts.js"></script> 
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>


<script>
$(function()
	{
		// On page load
		var pagetitle = $('#pagetitle').attr('title');
		var request = $('#request').text();
		var campaign_id = $('#select_campaign').attr('title');
		var date_range = $('#widgetDate span').html().split(' to ');
		var from_date = date_range[0];
		var to_date = date_range[1];
		if (campaign_id == 'Select a Campaign') {
			campaign_id = null;
		}
		$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_reports_output/' + pagetitle+'/' + from_date + '/' + to_date + '/' + campaign_id + '/'+request+'/').fadeIn("slow");
//		$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_reports_output/' + pagetitle+'/').fadeIn("slow");
	});
</script>


<script>
$(document).ready(function()
	{
	$('#pagetitle').click(
		function () {
			var position = $(this).offset();
			$('#go_reports_menu').css('left',position.left);
			$('#go_reports_menu').css('top',position.top+23);
			$('#go_reports_menu').slideDown('slow', function() {
				//$(this).css('height','230px');
			});
		}
	);
	
// 	$('#go_reports_menu').hover(
// 		function () {
// 			$(this).css('height','207px');
// 			$(this).show();
// 		},
// 		function () {
// 			$(this).slideUp('slow');
// 		}
// 	);
	
	$('li.go_reports_submenu').hover(
		function () {
			$(this).css('background-color','#CCC');
		},
		function () {
			$(this).css('background-color','white');
		}
	);

	$('li.go_reports_submenu').click(function () {
		var subID = $(this).attr('id');

		if (subID.match("/|agent_detail|agent_pdetail|dispo|sales_agent|inbound_report|call_export_report|dashboard|limesurvey|/g")) {
			$('#outbound').empty().html('Weekly');
			$('#outbound').attr('id','weekly');
			$('#inbound').empty().html('Monthly');
			$('#inbound').attr('id','monthly');
			$('#daily').css('visibility','hidden');
			$('#weekly').css('visibility','hidden');
			$('#monthly').css('visibility','hidden');
			$('#request').empty().html('daily');
//					$('#request_tab').css('visibility','hidden');
			$('#select_campaign').css('visibility','visible');
// 			$('#select_campaign').html('Select a Campaign');
		}
		if (subID=="stats") {
			$('#outbound').empty().html('Weekly');
			$('#outbound').attr('id','weekly');
			$('#inbound').empty().html('Monthly');
			$('#inbound').attr('id','monthly');
			$('#daily').css('visibility','visible');
			$('#weekly').css('visibility','visible');
			$('#monthly').css('visibility','visible');
			$('#weekly').removeClass('menuOn');
			$('#weekly').addClass('menuOff');
			$('#request').empty().html('daily');
//					$('#request_tab').css('visibility','visible');
			//$('#export').css('visibility','hidden');
			$('#select_campaign').css('visibility','visible');
// 			$('#select_campaign').html('Select a Campaign');
		}
		if (subID.match("/|sales_tracker|/g")) {
			$('#weekly').empty().html('Outbound');
			$('#weekly').attr('id','outbound');
			$('#monthly').empty().html('Inbound');
			$('#monthly').attr('id','inbound');
			$('#daily').css('visibility','hidden');
			$('#outbound').css('visibility','visible');
			$('#inbound').css('visibility','visible');
			$('#request').empty().html('outbound');
			$('#outbound').removeClass('menuOff');
			$('#outbound').addClass('menuOn');
//					$('#request_tab').css('visibility','visible');
			$('#select_campaign').css('visibility','visible');
// 			$('#select_campaign').html('Select a Campaign');
		}
		if (subID=="inbound_report") {
			$('#daily').css('visibility','hidden');
//					$('#request_tab').css('visibility','hidden');
// 			$('#select_campaign').html('Select a Campaign');
		}
		if (subID=="call_export_report") {
			$('#daily').css('visibility','hidden');
//					$('#request_tab').css('visibility','hidden');
			$('#select_campaign').css('visibility','hidden');
		}

		if(subID === "limesurvey"){
			   $("#select_campaign").css('visibility','hidden');
		}

		$('#pagetitle').html($(this).attr('title'));
		$('#pagetitle').attr('title',$(this).attr('id'));
		$('#go_reports_menu').slideUp('slow');
		//$('#export').css('visibility','hidden');
		var request = $('#request').text();
		var campaign_id = $('#select_campaign').attr('title');
		var date_range = $('#widgetDate span').html().split(' to ');
		var from_date = date_range[0];
		var to_date = date_range[1];
		if (campaign_id == 'Select a Campaign') {
			campaign_id = null;
		}

		$('#table_reports').empty().html('<img src="<? echo $base; ?>img/goloading.gif" />');
		$('#table_reports').fadeIn("slow").load('<? echo $base; ?>index.php/go_site/go_reports_output/' + subID+'/' + from_date + '/' + to_date + '/' + campaign_id + '/'+request+'/');
	});
	
	$('#select_campaign').click(
		function () {
			var pageTitle = $('#pagetitle').attr('title');
			
			if (pageTitle.match("/|inbound_report|/g")) {
				var position = $('#select_campaign').offset();
				$('#inbound_groups').css('left',position.left);
				$('#inbound_groups').css('top',position.top+23);
				var sHeight = $('#inbound_groups').css('height');
				if (sHeight.replace("px", "") > 250) {
					$('#inbound_groups ul').css({'height' : '250px', 'overflow-x' : 'hidden'});
				}
				$('#inbound_groups').slideToggle('slow');
			} else {
				var position = $('#select_campaign').offset();
				$('#campaign_ids').css('left',position.left);
				$('#campaign_ids').css('top',position.top+23);
				var sHeight = $('#campaign_ids').css('height');
				if (sHeight.replace("px", "") > 250) {
					$('#campaign_ids ul').css({'height' : '250px', 'overflow-x' : 'hidden'});
				}
				$('#campaign_ids').slideToggle('slow');
			}
		}
	);
	
	$('li.go_campaign_submenu').hover(
		function () {
			$(this).css('background-color','#CCC');
		},
		function () {
			$(this).css('background-color','white');
		}
	);

	$('li.go_campaign_submenu').click(function () {
		$('#select_campaign').html($(this).text());
		$('#select_campaign').attr('title',$(this).attr('title'));
//		$('#select_campaign').css('padding-right','72px');
		var request = $('#request').html();
		var pagetitle = $('#pagetitle').attr('title');
		var date_range = $('#widgetDate span').html().split(' to ');
		var from_date = date_range[0];
		var to_date = date_range[1];
		var campaign_id = $(this).attr('title');
		if (pagetitle.match("/|inbound_report|/g")) {
			$('#inbound_groups').slideUp('slow');
		} else {
			$('#campaign_ids').slideUp('slow');
		}

		if ( (campaign_id != "Select a Campaign") && (! pagetitle.match("/|stats|dispo|dashboard|/g"))) {
			$('#export').css('visibility','visible');
		}
		if (from_date.length < 10) { from_date = '<? echo $dateNOW; ?>'; }
		if (to_date.length < 10) { to_date = '<? echo $dateNOW; ?>'; }

		$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_reports_output/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaign_id + '/' + request + '/').fadeIn("slow");
	});
	
	$(document).mouseup(function (e)
	{
// 		var pagetitle = $('#pagetitle').attr('title');
// 		if (pagetitle.match("/|inbound_report|/g")) {
// 			var content = $('#inbound_groups');
// 		} else {
			var content = $('#campaign_ids,#inbound_groups,#go_reports_menu');
// 		}

		if (content.has(e.target).length === 0)
		{
			content.slideUp('slow');
		}
	});
	
//	$('#selectDate').click(function()
//	{
//		if($('#hiddenToggle').css("visibility") == 'visible') {
//			var campaign_id = $('#select_campaign').attr('title');
//			var request = $('#request').html();
//			var pagetitle = $('#pagetitle').attr('title');
//			var date_range = $('#widgetDate span').html().split(' to ');
//			var from_date = date_range[0];
//			var to_date = date_range[1];
//			
//			if (from_date.length < 10) { from_date = '<? echo $dateNOW; ?>'; }
//			if (to_date.length < 10) { to_date = '<? echo $dateNOW; ?>'; }
//			
////			if (campaign_id == 'Select a Campaign') {
////				campaign_id = null;
////			}
//			
//			if (campaign_id != 'Select a Campaign') {
//				$("#table_reports").empty().html('<img src="<? echo $base; ?>img/goloading.gif" />');
//				$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_reports_output/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaign_id + '/' + request + '/').fadeIn("slow");
//			}
//			$('#hiddenToggle').css('visibility', 'hidden');
//		} else {
//			$('#hiddenToggle').css('visibility', 'visible');
//		}
//	});
	
	$('#selectDateOK').click(function()
	{
		var campaign_id = $('#select_campaign').attr('title');
		var request = $('#request').html();
		var pagetitle = $('#pagetitle').attr('title');
		var date_range = $('#widgetDate span').html().split(' to ');
		var from_date = date_range[0];
		var to_date = date_range[1];
		
		if (from_date.length < 10) { from_date = '<? echo $dateNOW; ?>'; }
		if (to_date.length < 10) { to_date = '<? echo $dateNOW; ?>'; }
		
//		if (campaign_id == 'Select a Campaign') {
//			campaign_id = null;
//		}

		if (campaign_id != 'Select a Campaign') {
			$("#table_reports").empty().html('<img src="<? echo $base; ?>img/goloading.gif" />');
			$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_reports_output/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaign_id + '/' + request + '/').fadeIn("slow");
		}
		$('#hiddenToggle').css('visibility', 'hidden');

                $.fn.exists = function(){return this.length  > 0;}
                var $surbi = $("#table_reports").children("table").children("tbody").children("tr").children("td").children("table").children("tbody").children("tr").children("td").find("select#survey").exists();
                if($surbi){
                     if(window.display !== undefined){
                          display();
                     }
                }
	});


	$('.menuOff').hover(
		function (e) {
			var campaign_id = $('#select_campaign').attr('title');
			var request = $('#request').html();
			if (campaign_id != 'Select a Campaign')
			{
				if (request == $(this).attr('id'))
				{
					$(this).css({'background-color': '#fff', 'color': '#000'});
				}
				else
				{
					$(this).css({'background-color': '#dfdfdf', 'color': '#000'});
				}
			}
		},
		function (e) {
			var campaign_id = $('#select_campaign').attr('title');
			var request = $('#request').html();
			if (campaign_id != 'Select a Campaign')
			{
				if (request == $(this).attr('id'))
				{
					$(this).css({'background-color': '#fff', 'color': '#000'});
				}
				else
				{
					$(this).css({'background-color': '#efefef', 'color': '#777'});
				}
			}
		}
	);


	$('.tabtoggle').click(function() {
		var request = $(this).attr('id');
		var campaign_id = $('#select_campaign').attr('title');
		if (campaign_id != 'Select a Campaign')
		{
			$('.tabtoggle').each(function() {
				if (request == $(this).attr('id')) {
					$(this).addClass('menuOn');
					$(this).removeClass('menuOff');
					$(this).css({'background-color': '#fff', 'color': '#000'});
					$('#request').html(request);
				} else {
					$(this).addClass('menuOff');
					$(this).removeClass('menuOn');
					$(this).css({'background-color': '#efefef', 'color': '#777'});
				}
			});
		}

		var request = $('#request').html();
		var pagetitle = $('#pagetitle').attr('title');
		var date_range = $('#widgetDate span').html().split(' to ');
		var from_date = date_range[0];
		var to_date = date_range[1];
		if (from_date.length < 10) { from_date = '<? echo $dateNOW; ?>'; }
		if (to_date.length < 10) { to_date = '<? echo $dateNOW; ?>'; }

//		if (campaign_id == 'Select a Campaign') {
//			campaign_id = null;
//		}

		if (campaign_id != 'Select a Campaign') {
			$("#table_reports").empty().html('<img src="<? echo $base; ?>img/goloading.gif" />');
			$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_reports_output/' + pagetitle + '/' + from_date + '/' + to_date + '/' + campaign_id + '/' + request + '/').fadeIn("slow");
		}
	});
	
	$(".toolTip").tipTip();
	
});







</script>
