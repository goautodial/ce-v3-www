<?php
############################################################################################
####  Name:             go_campaign_list.php                                            ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

if($permissions->campaign_read == "N"){
	die("<br />Error: You do not have permission to view the list of campaigns.");
}
?>
<style type="text/css">
#selectAction, #selectStatusAction, #selectCampStatusAction,
#selectLeadRecycleAction, #selectCampLeadRecycleAction,
#selectPhoneCodeAction, #selectCampPhoneCodeAction,
#selectHotKeysAction, #selectCampHotKeysAction, #selectFiltersAction {
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

#mainTable th,
#statusesTable th,
#leadRecyclingTable th,
#pauseCodeTable th,
#hotKeysTable th,
#filtersTable th {
	text-align:left;
}

.buttones,.buttons {
	color:#7A9E22;
	cursor:pointer;
}

.buttones:hover,.buttons:hover{
	font-weight:bold;
}

.buttons {
	font-size: 12px;
}

/* Table Sorter */
table.tablesorter thead tr .header {
/*	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;*/
	cursor: pointer;
}
/*table.tablesorter thead tr .headerSortUp {
	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/desc.gif);
}*/
/* Table Sorter */

.modify-value {
  font-weight: bold;
  color: #7f7f7f;
}
</style>
<script>
function modify(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '860px', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
			top: "70px"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_settings/'+camp).fadeIn("slow");
	} else {
			alert("Error: You do not have permission to modify this campaign.");
	}
}

function delCamp(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete this Campaign?\n\n'+camp+'\n\nPlease make sure to transfer any existing list ids\nthat have leads uploaded to it to any available campaign.');
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_check_for_leads/"+camp, function(data)
			{
				//alert(data);
				if (data == "OK"){
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_update_campaign_list/delete/'+camp+'/');
				} else {
					alert(data);
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete campaigns.");
	}
}

function modifyStatus(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '900px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
			top: "70px"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_campaign_statuses/'+camp).fadeIn("slow");
	} else {
			alert("Error: You do not have permission to modify campaign statuses.");
	}
}

function delStatus(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete the selected campaign\'s statuses?');
		if (what)
		{
			$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
			$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_delete_campaign_statuses_list/'+camp);
		}
	} else {
		alert("Error: You do not have permission to delete campaign statuses.");
	}
}

function delLeadRecycle(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete the selected campaign\'s statuses?');
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/delete/"+camp, function()
			{
				if ($('#request').text() == 'showLeadRecycling')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete lead recycling statuses.");
	}
}

function delPauseCode(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete the selected campaign\'s pause codes?');
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/delete/"+camp, function()
			{
				if ($('#request').text() == 'showPauseCodes')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete campaign pause codes.");
	}
}

function delHotKeys(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete the selected campaign\'s hotkeys?');
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/delete/"+camp, function()
			{
				if ($('#request').text() == 'showHotKeys')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete campaign hotkeys.");
	}
}

function viewInfo(camp, type, h)
{
	$('#statusOverlay').fadeIn('fast');
	$('#statusBox').css({'width': '400px','height': h,'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '20px', 'position': 'fixed'});
	$('#statusBox').animate({
// 		top: Math.max(0, (($(window).height() - $('#statusBox').outerHeight()) / 2) + $(window).scrollTop()) + "px"
		top: "70px"
	}, 500);
	
	$("#statusOverlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#statusOverlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_view_info/'+camp+'/'+type).fadeIn("slow");		
}

function changePage(pagenum)
{
	var search = $("#search_list").val();
	search = search.replace(/\s/g,"%20");
	$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/'+pagenum+'/'+search);
}

$(function ()
{
	var request = $('#request').html();
	var tabName = '';
	var cntCamps = <?php echo count($campaign['list']); ?>;
	$('.tabtoggle').each(function()
	{
		tabName = $(this).attr('id');
		if (tabName == request)
		{
			$('#' + tabName + '_div').show();
		}
		else
		{
			$('#' + tabName + '_div').hide();
		}
	});
	
	if ($("#request").text()=="showFilters") {
		$('#table_pagelinks').hide();
		$('#table_filterlinks').show();
	} else {
		$('#table_pagelinks').show();
		$('#table_filterlinks').hide();
	}
	
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delCampaign[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delCampaign[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllStatus').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delStatus[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delStatus[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllRecycle').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delLeadRecycling[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delLeadRecycling[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllPauseCodes').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delPauseCodes[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delPauseCodes[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllHotKeys').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delHotKeys[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delHotKeys[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllFilters').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delFilters[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delFilters[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	var toggleAction = $('#go_action_menu').css('display');
	$('#selectAction').click(function()
	{
		if (toggleAction == 'none')
		{
			var position = $(this).offset();
			$('#go_action_menu').css('left',position.left-68);
			$('#go_action_menu').css('top',position.top+16);
			$('#go_action_menu').slideDown('fast');
			toggleAction = $('#go_action_menu').css('display');
		}
		else
		{
			$('#go_action_menu').slideUp('fast');
			$('#go_action_menu').hide();
			toggleAction = $('#go_action_menu').css('display');
		}
	});
	
	var toggleStatus = $('#go_status_menu').css('display');
	$('#selectStatusAction').click(function()
	{
		if (toggleStatus == 'none')
		{
			var position = $(this).offset();
			$('#go_status_menu').css('left',position.left-42);
			$('#go_status_menu').css('top',position.top+16);
			$('#go_status_menu').slideDown('fast');
			toggleStatus = $('#go_status_menu').css('display');
		}
		else
		{
			$('#go_status_menu').slideUp('fast');
			$('#go_status_menu').hide();
			toggleStatus = $('#go_status_menu').css('display');
		}
	});
	
	var toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
	$('#selectRecycleAction').click(function()
	{
		if (toggleLeadRecycling == 'none')
		{
			var position = $(this).offset();
			$('#go_lead_recycle_menu').css('left',position.left-42);
			$('#go_lead_recycle_menu').css('top',position.top+16);
			$('#go_lead_recycle_menu').slideDown('fast');
			toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
		}
		else
		{
			$('#go_lead_recycle_menu').slideUp('fast');
			$('#go_lead_recycle_menu').hide();
			toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
		}
	});
	
	var togglePauseCodes = $('#go_pausecodes_menu').css('display');
	$('#selectPauseCodeAction').click(function()
	{
		if (togglePauseCodes == 'none')
		{
			var position = $(this).offset();
			$('#go_pausecodes_menu').css('left',position.left-42);
			$('#go_pausecodes_menu').css('top',position.top+16);
			$('#go_pausecodes_menu').slideDown('fast');
			togglePauseCodes = $('#go_pausecodes_menu').css('display');
		}
		else
		{
			$('#go_pausecodes_menu').slideUp('fast');
			$('#go_pausecodes_menu').hide();
			togglePauseCodes = $('#go_pausecodes_menu').css('display');
		}
	});
	
	var toggleHotKeys = $('#go_hotkeys_menu').css('display');
	$('#selectHotKeysAction').click(function()
	{
		if (toggleHotKeys == 'none')
		{
			var position = $(this).offset();
			$('#go_hotkeys_menu').css('left',position.left-42);
			$('#go_hotkeys_menu').css('top',position.top+16);
			$('#go_hotkeys_menu').slideDown('fast');
			toggleHotKeys = $('#go_hotkeys_menu').css('display');
		}
		else
		{
			$('#go_hotkeys_menu').slideUp('fast');
			$('#go_hotkeys_menu').hide();
			toggleHotKeys = $('#go_hotkeys_menu').css('display');
		}
	});
	
	var toggleFilters = $('#go_filters_menu').css('display');
	$('#selectFiltersAction').click(function()
	{
		if (toggleFilters == 'none')
		{
			var position = $(this).offset();
			$('#go_filters_menu').css('left',position.left-42);
			$('#go_filters_menu').css('top',position.top+16);
			$('#go_filters_menu').slideDown('fast');
			toggleFilters = $('#go_filters_menu').css('display');
		}
		else
		{
			$('#go_filters_menu').slideUp('fast');
			$('#go_filters_menu').hide();
			toggleFilters = $('#go_filters_menu').css('display');
		}
	});
	
	$(document).mouseup(function (e)
	{
		var content = $('#go_action_menu, #go_status_menu, #go_camp_status_menu, #go_lead_recycle_menu, #go_camp_lead_recycle_menu, #go_pausecodes_menu, #go_camp_pausecodes_menu, #go_hotkeys_menu, #go_camp_hotkeys_menu, #go_filters_menu');
		if (content.has(e.target).length === 0 && (e.target.id != 'selectAction' && e.target.id != 'selectStatusAction'
				&& e.target.id != 'selectRecycleAction' && e.target.id != 'selectCampRecycleAction'
				&& e.target.id != 'selectPauseCodeAction' && e.target.id != 'selectCampPauseCodeAction'
				&& e.target.id != 'selectHotKeysAction' && e.target.id != 'selectCampHotKeysAction'
				&& e.target.id != 'selectFiltersAction'))
		{
			content.slideUp('fast');
			content.hide();
			toggleAction = $('#go_action_menu').css('display');
			toggleStatus = $('#go_status_menu').css('display');
			toggleCampStatus = $('#go_camp_status_menu').css('display');
			toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
			toggleCampLeadRecycling = $('#go_camp_lead_recycle_menu').css('display');
			togglePauseCodes = $('#go_pausecodes_menu').css('display');
			toggleCampPauseCodes = $('#go_camp_pausecodes_menu').css('display');
			toggleHotKeys = $('#go_hotkeys_menu').css('display');
			toggleCampHotKeys = $('#go_camp_hotkeys_menu').css('display');
			toggleFilters = $('#go_filters_menu').css('display');
		}
	});
	
	$('.hoverCampID').each(function()
	{
		$(this).hover(function()
		{
			$(this).css('color','red');
		},
		function()
		{
			$(this).css('color','black');
		});
	});
	
	// Tool Tip
	$(".toolTip").tipTip();

	if (cntCamps > 0)
	{
		// Pagination
		//$('#mainTable').tablePagination();
		//$('#statusesTable').tablePagination();
		//$('#leadRecyclingTable').tablePagination();
		//$('#pauseCodeTable').tablePagination();
		//$('#hotKeysTable').tablePagination();

		// Table Sorter 
		$("#mainTable").tablesorter({headers: { 6: { sorter: false}, 7: {sorter: false} }});
		$("#statusesTable").tablesorter({headers: { 3: { sorter: false}, 4: {sorter: false} }});
		$("#leadRecyclingTable").tablesorter({headers: { 3: { sorter: false}, 4: {sorter: false} }});
		$("#pauseCodeTable").tablesorter({headers: { 3: { sorter: false}, 4: {sorter: false} }});
		$("#hotKeysTable").tablesorter({headers: { 3: { sorter: false}, 4: {sorter: false} }});
	}
	else
	{
		if ($("#search_list").val() == '') {
			addNewCampaign();
		}
	}

	$('#closeboxStatus').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxStatus').animate({'top':'-2550px'},500);
		$('#overlayStatus').fadeOut('slow');

		if ($('#request').text() == 'showStatuses')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#closeboxLeadRecycle').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxLeadRecycle').animate({'top':'-2550px'},500);
		$('#overlayLeadRecycle').fadeOut('slow');

		if ($('#request').text() == 'showLeadRecycling')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#closeboxPauseCodes').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxPauseCodes').animate({'top':'-2550px'},500);
		$('#overlayPauseCodes').fadeOut('slow');

		if ($('#request').text() == 'showPauseCodes')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#closeboxHotKeys').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxHotKeys').animate({'top':'-2550px'},500);
		$('#overlayHotKeys').fadeOut('slow');

		if ($('#request').text() == 'showHotKeys')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#closeboxFilters').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxFilters').animate({'top':'-2550px'},500);
		$('#overlayFilters').fadeOut('slow');

		if ($('#request').text() == 'showFilters')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#statusSubmit').click(function()
	{
		var campID = $('#statusCampID').val();
		var string = $('.statusResult').serialize();
		var err_msg = 'Please check the following error(s):\n\n';
		var err = 0;

		if ($('#statusID').val() == '')
		{
			err_msg += 'Status ID is empty\n';
			err++;
		}
		
		if ($('#sloading').html().match(/Not Available/))
		{
			err_msg += 'Status ID is Not Available\n';
			err++;
		}

		if ($('#statusName').val() == '')
		{
			err_msg += 'Status Name is empty\n';
			err++;
		}

		if (err > 0)
		{
			alert(err_msg);
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_add_new_statuses/"+campID+"/add_status/"+string, function()
			{
				$('#boxStatus').animate({'top':'-2550px'},500);
				$('#overlayStatus').fadeOut('slow');

				if ($('#request').text() == 'showStatuses')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	});

	$('#leadRecycleSubmit').click(function()
	{
		var campID = $('#leadCampID').val();
		var string = $('.leadRecycleResult').serialize();
		var err = 0;

		if (campID.length < 1)
		{
			$('#leadCampID').css('border','solid 1px red');
			err++;
		}

		if ($('#attempt_delay').val().length < 1)
		{
			$('#attempt_delay').css('border','solid 1px red');
			err++;
		}

		if (err > 0)
		{
			alert('Please select or fill-in all the fields.');
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/add/"+string, function()
			{
				$('#boxStatus').animate({'top':'-2550px'},500);
				$('#overlayStatus').fadeOut('slow');
			
				if ($('#request').text() == 'showLeadRecycling')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	});

	$('#pauseCodeSubmit').click(function()
	{
		var campID = $('#pauseCampID').val();
		var string = $('.pauseCodeResult').serialize();
		var err = 0;

		if (campID.length < 1)
		{
			$('#pauseCampID').css('border','solid 1px red');
			err++;
		}

		if ($('#pause_code').val().length < 1)
		{
			$('#pause_code').css('border','solid 1px red');
			err++;
		}

		if ($('#pause_code_name').val().length < 1)
		{
			$('#pause_code_name').css('border','solid 1px red');
			err++;
		}

		if (err > 0)
		{
			alert('Please select or fill-in all the fields.');
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/add/"+string, function()
			{
				$('#boxPauseCodes').animate({'top':'-2550px'},500);
				$('#overlayPauseCodes').fadeOut('slow');
			
				if ($('#request').text() == 'showPauseCodes')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	});

	$('#hotKeysSubmit').click(function()
	{
		var campID = $('#hotKeysCampID').val();
		var string = $('.hotKeysResult').serialize();
		var err = 0;
		var notAvail = 0;

		if (campID.length < 1)
		{
			$('#hotKeysCampID').css('border','solid 1px red');
			err++;
		}

		if ($('#hotKeys').val().length < 1)
		{
			$('#hotKeys').css('border','solid 1px red');
			err++;
		}

		if ($('#statusHotKeys').val().length < 1)
		{
			$('#statusHotKeys').css('border','solid 1px red');
			err++;
		}
		
		if ($('#kloading').html().match(/Not Available/))
		{
			alert("HotKey "+$('#hotKeys').val()+" Not Available.");
			notAvail = 1;
			err++;
		}

		if (err > 0)
		{
			if (!notAvail)
				alert('Please select or fill-in all the fields.');
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/add/"+string, function()
			{
				$('#boxHotKeys').animate({'top':'-2550px'},500);
				$('#overlayHotKeys').fadeOut('slow');
			
				if ($('#request').text() == 'showHotKeys')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	});

	$('#filtersNext').click(function()
	{
		var campID = $('#statusCampID').val();
		var string = $('.statusResult').serialize();
		var err_msg = 'Please check the following error(s):\n\n';
		var err = 0;

		if ($('#lead_filter_id').val() == '')
		{
			err_msg += 'Filter ID is empty\n';
			err++;
		}
		
		if ($('#floading').html().match(/Not Available/))
		{
			err_msg += 'Filter ID is Not Available\n';
			err++;
		}
		
		if ($('#lead_filter_name').val() == '')
		{
			err_msg += 'Filter Name is empty\n';
			err++;
		}
		
		if ($('#user_group').val() == '')
		{
			err_msg += 'User Group not selected\n';
			err++;
		}

		if (err > 0)
		{
			alert(err_msg);
		}
		else
		{
			$('#filtersSubmit').show();
			$('#filtersBack').show();
			$('.filtersDivider').show();
			$('#filters1stTable').hide();
			$('#filters2ndTable').show();
			$(this).hide();
			$('#small_step_number > img').attr('src','<?=$base?>img/step2of2-navigation-small.png');
			$('#step_number > img').attr('src','<?=$base?>img/step2-trans.png');
		}
	});
	
	$("#filtersBack").click(function()
	{
		$('#filtersSubmit').hide();
		$('#filtersNext').show();
		$('.filtersDivider').hide();
		$('#filters1stTable').show();
		$('#filters2ndTable').hide();
		$(this).hide();
		$('#small_step_number > img').attr('src','<?=$base?>img/step1of2-navigation-small.png');
		$('#step_number > img').attr('src','<?=$base?>img/step1-trans.png');
	});

	$('#filtersSubmit').click(function()
	{
		var filterid = $('#lead_filter_id').val();
		var filtersql = $('#lead_filter_sql').val();
		var filters = $('.filtersForm').serialize();
		var err = 0;
		var notAvail = 0;

		if (filtersql.length < 1)
		{
			$('#lead_filter_sql').css('border','solid 1px red');
			err++;
		}

		if (err > 0)
		{
			if (!notAvail)
				alert('Please compose an SQL query.');
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_sql_filters/add/", {filters: filters}, function(data)
			{
				alert(data);
				if (data.indexOf("Success") > -1) {
					$('#boxFilters').animate({'top':'-2550px'},500);
					$('#overlayFilters').fadeOut('slow');
				
					if ($('#request').text() == 'showFilters')
					{
						$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
						$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
					}
				}
			});
		}
	});
	
	$('#leadCampID').change(function(e)
	{
		$(this).css('border','solid 1px #dfdfdf');
	});

	$('#statusID').keyup(function(e)
	{
		checkStatus();
	});
	
	$('#statusID, #statusName').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if ((event.keyCode == 32 && $(this).attr('id') != "statusName") || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which == 32 && $(this).attr('id') != "statusName") || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
	});
	
	$('#attempt_delay').keyup(function(e)
	{
		if (parseInt($(this).val()) < 2)
		{
			$(this).css('border','solid 1px red');
		} else {
			$(this).css('border','solid 1px #dfdfdf');
		}
		
		if (parseInt($(this).val()) > 720)
			$(this).val('720');
	});

	$('#pause_code').keyup(function(e)
	{
		checkPauseCodes();
	});
	
	$('#pause_code, #pause_code_name').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if ((event.keyCode == 32 && $(this).attr('id') != "pause_code_name") || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which == 32 && $(this).attr('id') != "pause_code_name") || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
	});
	
	$('#attempt_delay').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			// Allow: backspace, delete, tab, escape, and enter
			if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: Ctrl+X
            (event.keyCode == 88 && event.ctrlKey === true) || 
             // Allow: Ctrl+C
            (event.keyCode == 67 && event.ctrlKey === true) || 
             // Allow: Ctrl+V
            (event.keyCode == 86 && event.ctrlKey === true) || 
             // Allow: Ctrl+Z
            (event.keyCode == 90 && event.ctrlKey === true) || 
             // Allow: Ctrl+Y
            (event.keyCode == 89 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return true;
			}
			else {
				// Ensure that it is a number and stop the keypress
				if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
					event.preventDefault(); 
				}   
			}
		} else {
			// For ASCII Key Codes
			if ((event.which > 31 && event.which < 48) || event.which > 57)
				return false;
		}
	});
	
	$('.datepicker').datepicker();
	
	$("#fields_to_filter").change(function()
	{
		if ($(this).val()=='entry_date' || $(this).val()=='modify_date') {
			$(".dateOptions").show();
			$(".countOptions").hide();
			$(".otherOptions").hide();
			$(".tzOptions").hide();
			$(".countryOptions").hide();
			$(".areaOptions").hide();
			$(".stateOptions").hide();
			var from_date = $("#filter_by_date").val();
			var to_date = $("#filter_by_end_date").val();
			if ($("#filter_by_end_date").is(":visible")) {
				var sql_string = "BETWEEN '"+from_date+"' AND '"+to_date+"'";
			} else {
				var sql_string = "= '"+from_date+"'";
			}
			$("#filter_sql_preview").val("DATE("+$(this).val()+") "+sql_string);
		} else if ($(this).val()=='called_count') {
			$(".dateOptions").hide();
			$(".countOptions").show();
			$(".otherOptions").hide();
			$(".tzOptions").hide();
			$(".countryOptions").hide();
			$(".areaOptions").hide();
			$(".stateOptions").hide();
			var cnt_val = $("#filter_by_called_count").val();
			var cnt_oper = $("input[name=filter_sql_oper]").val();
			var sql_string = cnt_oper+" '"+cnt_val+"'";
			$("#filter_sql_preview").val($(this).val()+" "+sql_string);
		} else {
			$(".dateOptions").hide();
			$(".countOptions").hide();
			$(".otherOptions").show();
			var cnt_options = "";
			var cnt_oper = $("input[name=filter_sql_other]").val();
			
			if ($(this).val()=='gmt_offset_now') {
				$(".tzOptions").show();
				$(".countryOptions").hide();
				$(".areaOptions").hide();
				$(".stateOptions").hide();
				$("#filter_by_timezone option:selected").each(function()
				{
					cnt_options += "'"+$(this).text()+"',";
				});
				var sql_string = "gmt_offset_now "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
			} else if ($(this).val()=='phone_code') {
				$(".tzOptions").hide();
				$(".countryOptions").show();
				$(".areaOptions").hide();
				$(".stateOptions").hide();
				$("#filter_by_country option:selected").each(function()
				{
					var cnt_arr = $(this).val().split('_');
					cnt_options += "'"+cnt_arr[1]+"',";
				});
				var sql_string = "phone_code "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
			} else if ($(this).val()=='phone_number') {
				$(".tzOptions").hide();
				$(".countryOptions").hide();
				$(".areaOptions").show();
				$(".stateOptions").hide();
				$("#filter_by_areacode option:selected").each(function()
				{
					var cnt_arr = $(this).val().split('_');
					cnt_options += "'"+cnt_arr[1]+"',";
				});
				var sql_string = "LEFT(phone_number,3) "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
			} else if ($(this).val()=='state') {
				$(".tzOptions").hide();
				$(".countryOptions").hide();
				$(".areaOptions").hide();
				$(".stateOptions").show();
				$("#filter_by_state option:selected").each(function()
				{
					var cnt_arr = $(this).val().split('_');
					cnt_options += "'"+cnt_arr[1]+"',";
				});
				var sql_string = "state "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
			} else {
				$(".tzOptions").hide();
				$(".countryOptions").hide();
				$(".areaOptions").hide();
				$(".stateOptions").hide();
				$(".otherOptions").hide();
				var sql_string = "";
			}
			$("#filter_sql_preview").val(sql_string);
		}
		
		if ($(this).val()=='') {
			$("#filter_sql_insert").attr('disabled',true);
			$("input[name=filter_sql_div]").removeAttr('checked');
			$("#filter_sql_span").hide();
		} else {
			var filter_sql = $("#lead_filter_sql").val();
			$("#filter_sql_insert").removeAttr('disabled');
			$("input[name=filter_sql_div]").removeAttr('checked');
			
			if (filter_sql.length > 0) {
				$("#filter_sql_span").show();
			} else {
				$("#filter_sql_span").hide();
			}
		}
	});
	
	$("input[name=filter_sql_date]").click(function()
	{
		if ($(this).val()=='single') {
			$('.date_range').hide();
			$("#filter_by_date").removeAttr("placeholder");
			var from_date = $("#filter_by_date").val();
			var sql_string = "= '"+from_date+"'";
		} else {
			$('.date_range').show();
			$("#filter_by_date").attr("placeholder","Start Date");
			var from_date = $("#filter_by_date").val();
			var to_date = $("#filter_by_end_date").val();
			var sql_string = "BETWEEN '"+from_date+"' AND '"+to_date+"'";
		}
		$("#filter_sql_preview").val("DATE("+$("#fields_to_filter").val()+") "+sql_string);
	});
	
	$("input[name=filter_sql_oper]").click(function()
	{
		var cnt_val = $("#filter_by_called_count").val();
		var cnt_oper = $(this).val();
		var sql_string = cnt_oper+" '"+cnt_val+"'";
		$("#filter_sql_preview").val($("#fields_to_filter").val()+" "+sql_string);
	});
	
	$("input[name=filter_sql_other]").click(function()
	{
		var cnt_options = "";
		var cnt_oper = $(this).val();
		if ($("#fields_to_filter").val()=='gmt_offset_now') {
			$("#filter_by_timezone option:selected").each(function()
			{
				cnt_options += "'"+$(this).text()+"',";
			});
			var sql_string = "gmt_offset_now "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		} else if ($("#fields_to_filter").val()=='phone_code') {
			$("#filter_by_country option:selected").each(function()
			{
				var cnt_arr = $(this).val().split('_');
				cnt_options += "'"+cnt_arr[1]+"',";
			});
			var sql_string = "phone_code "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		} else if ($("#fields_to_filter").val()=='phone_number') {
			$("#filter_by_areacode option:selected").each(function()
			{
				var cnt_arr = $(this).val().split('_');
				cnt_options += "'"+cnt_arr[1]+"',";
			});
			var sql_string = "LEFT(phone_number,3) "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		} else {
			$("#filter_by_state option:selected").each(function()
			{
				var cnt_arr = $(this).val().split('_');
				cnt_options += "'"+cnt_arr[1]+"',";
			});
			var sql_string = $("#fields_to_filter").val()+" "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		}
		$("#filter_sql_preview").val(sql_string);
	});
	
	$(".datepicker").change(function()
	{
		var from_date = $("#filter_by_date").val();
		var to_date = $("#filter_by_end_date").val();
		if ($("#filter_by_end_date").is(":visible")) {
			var sql_string = "BETWEEN '"+from_date+"' AND '"+to_date+"'";
		} else {
			var sql_string = "= '"+from_date+"'";
		}
		$("#filter_sql_preview").val("DATE("+$("#fields_to_filter").val()+") "+sql_string);
	});
	
	$("#filter_by_called_count").change(function()
	{
		var cnt_val = $(this).val();
		var cnt_oper = $("input[name=filter_sql_oper]:checked").val();
		var sql_string = cnt_oper+" '"+cnt_val+"'";
		$("#filter_sql_preview").val($("#fields_to_filter").val()+" "+sql_string);
	});
	
	$("#filter_by_timezone").change(function()
	{
		var cnt_options = "";
		var cnt_oper = $("input[name=filter_sql_other]:checked").val();
		$("#filter_by_timezone option:selected").each(function()
		{
			cnt_options += "'"+$(this).text()+"',";
		});
		var sql_string = "gmt_offset_now "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		$("#filter_sql_preview").val(sql_string);
	});
	
	$("#filter_by_country").change(function()
	{
		var cnt_options = "";
		var cnt_oper = $("input[name=filter_sql_other]:checked").val();
		$("#filter_by_country option:selected").each(function()
		{
			var cnt_arr = $(this).val().split('_');
			cnt_options += "'"+cnt_arr[1]+"',";
		});
		var sql_string = "phone_code "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		$("#filter_sql_preview").val(sql_string);
	});
	
	$("#filter_by_areacode").change(function()
	{
		var cnt_options = "";
		var cnt_oper = $("input[name=filter_sql_other]:checked").val();
		$("#filter_by_areacode option:selected").each(function()
		{
			var cnt_arr = $(this).val().split('_');
			cnt_options += "'"+cnt_arr[1]+"',";
		});
		var sql_string = "LEFT(phone_number,3) "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		$("#filter_sql_preview").val(sql_string);
	});
	
	$("#filter_by_state").change(function()
	{
		var cnt_options = "";
		var cnt_oper = $("input[name=filter_sql_other]:checked").val();
		$("#filter_by_state option:selected").each(function()
		{
			var cnt_arr = $(this).val().split('_');
			cnt_options += "'"+cnt_arr[1]+"',";
		});
		var sql_string = "state "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		$("#filter_sql_preview").val(sql_string);
	});
	
	$("#filter_sql_insert").click(function()
	{
		if ($('#lead_filter_sql').val().indexOf($("#filter_sql_preview").val()) > -1) {
			$("input[name=filter_sql_div]").removeAttr('checked');
			alert('SQL query string already exist.');
		} else {
			var filter_sql_preview = $("#filter_sql_preview").val();
			
			var filter_sql = $("#lead_filter_sql").val();
			var sql_oper = "";
			if (filter_sql.length > 0) {
				$("#filter_sql_span").show();
				if ($("input[name=filter_sql_div]:checked").length) {
					var sql_oper = " "+$("input[name=filter_sql_div]:checked").val();
					filter_sql += sql_oper+" "+filter_sql_preview;
				} else {
					alert("Please select an SQL operator 'AND' or 'OR' to continue.");
				}
			} else {
				$("#filter_sql_span").show();
				filter_sql = filter_sql_preview;
			}
			$("input[name=filter_sql_div]").removeAttr('checked');
			$("#lead_filter_sql").val(filter_sql);
		}
	});
	
	$("#clear_filter").click(function()
	{
		$("#lead_filter_sql").val('');
	});
	
	$('#lead_filter_id').keyup(function(event)
	{
		
		if (event.keyCode != 17)
		{
			if ($(this).val().length > 2)
			{
				$('#floading').load('<? echo $base; ?>index.php/go_campaign_ce/go_sql_filters/check/'+$(this).val());
			} else {
				$('#floading').html("<small style=\"color:red;\">Minimum of 3 characters.</small>");
			}
		}
	});
	
	$('#lead_filter_id,#lead_filter_name,#lead_filter_comments').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if ((event.keyCode == 32 && ($(this).attr('id') != "lead_filter_name" && $(this).attr('id') != "lead_filter_comments")) || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which == 32 && ($(this).attr('id') != "lead_filter_name" && $(this).attr('id') != "lead_filter_comments")) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
	});

});

function checkLeadRecycle()
{
	var campid = $('#leadCampID').val();
	var statusid = $('#leadStatusID').val();
	
	if (campid.length > 0 && statusid.length > 0)
		$('#lloading').load('<? echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/check/'+campid+'/'+statusid);
}

function checkPauseCodes()
{
	var campid = $('#pauseCampID').val();
	var pauseCode = $('#pause_code').val();
	
	if (campid.length > 0 && pauseCode.length > 0)
		$('#ploading').load('<? echo $base; ?>index.php/go_campaign_ce/go_pause_codes/check/'+campid+'/'+pauseCode);
}

function checkStatus()
{
	var campid = $('#statusCampID').val();
	var statusID = $('#statusID').val();
	
	if (statusID.length > 0)
		$('#sloading').load('<? echo $base; ?>index.php/go_campaign_ce/go_check_status/'+campid+'/'+statusID);
	else
		$('#sloading').html('');
}

function checkHotKeys()
{
	var campid = $('#hotKeysCampID').val();
	var hotKeys = $('#hotKeys').val();
	var statusHotKeys = $('#statusHotKeys').val();
	
	if (campid.length > 0 && hotKeys.length > 0 && statusHotKeys.length > 0)
		$('#kloading').load('<? echo $base; ?>index.php/go_campaign_ce/go_hot_keys/check/'+campid+'/'+hotKeys);
}

function modifyRecycle(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
			top: "70px"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/modify/'+camp).fadeIn("slow");
	} else {
		alert("Error: You do not have permission to modify lead recycling statuses.");
	}
}

function delLeadRecycling(status,camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var answer = confirm("Are you sure you want to delete this Status?\n\n"+status);
	
		if (answer)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/delete/"+camp+"/"+status, function()
			{
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/modify/'+camp).fadeIn("slow");
			});
		}
	} else {
		alert("Error: You do not have permission to delete lead recycling statuses.");
	}
}

function modifyLeadRecycling(status,camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/view/"+camp+"/"+status, function(data)
		{
			var items = jQuery.parseJSON(data);
			
			$('#spanLeadStatus').html(status);
			$('#leadStatus').val(status);
			$('#attemptDelay').val(items.attempt_delay);
			$('#attemptMaximum').val(items.attempt_maximum);
			$('#isActive').val(items.active);
			$('.hiddenRecyclingTable').slideDown(500);
		});
	} else {
		alert("Error: You do not have permission to modify lead recycling statuses.");
	}
}

function modifyPauseCode(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
			top: "70px"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_pause_codes/modify/'+camp).fadeIn("slow");
	} else {
		alert("Error: You do not have permission to modify campaign pause codes.");
	}
}

function modifyCampPauseCodes(code,camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/view/"+camp+"/"+code, function(data)
		{
			var items = jQuery.parseJSON(data);
			
			$('#spanPauseCode').html(code);
			$('#pauseCodeID').val(code);
			$('#pauseCodeName').val(items.pause_code_name.replace("+"," "));
			$('#isBillable').val(items.billable);
			$('.hiddenPauseCodesTable').slideDown(500);
		});
	} else {
		alert("Error: You do not have permission to modify campaign pause codes.");
	}
}

function delCampPauseCodes(code,camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var answer = confirm("Are you sure you want to delete this Pause Code?\n\n"+code);
	
		if (answer)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/delete/"+camp+"/"+code, function()
			{
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/modify/'+camp).fadeIn("slow");
			});
		}
	} else {
		alert("Error: You do not have permission to delete campaign pause codes.");
	}
}

function modifyHotKeys(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
			top: "70px"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_hot_keys/modify/'+camp).fadeIn("slow");
	} else {
		alert("Error: You do not have permission to modify campaign hotkeys.");
	}
}

function delCampHotKeys(hotkey,camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var answer = confirm("Are you sure you want to delete this HotKey?\n\n"+hotkey);
	
		if (answer)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/delete/"+camp+"/"+hotkey, function()
			{
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/modify/'+camp).fadeIn("slow");
			});
		}
	} else {
		alert("Error: You do not have permission to delete campaign hotkeys.");
	}
}

function modifyFilters(filter)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
			top: "70px"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_sql_filters/modify/'+filter).fadeIn("slow");
	} else {
		alert("Error: You do not have permission to modify lead filters.");
	}
}
</script>
<div id="showList_div">
<table id="mainTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th>&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;DIAL METHOD</th>
            <th>&nbsp;&nbsp;STATUS</th>
            <th style="display:none;">&nbsp;&nbsp;LEVEL</th>
            <th style="display:none;">&nbsp;&nbsp;REMOTE AGENT STATUS</th>
            <th colspan="3" style="width:6%;text-align:center;" nowrap>
		<span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
        </tr>
    </thead>
    <tbody>
<?php
	if (count($campaign['list']) > 0) {
		$x=0;
		foreach ($campaign['list'] as $row)
		{
			$remote = $campaign['remote'];
			$remote_status = ($remote[$row->campaign_id] != '') ? $remote[$row->campaign_id] : '<em>NON SURVEY CAMPAIGN</em>';
			
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			
			switch ($row->dial_method)
			{
				case "RATIO":
					$dial_method = "Auto-Dial";
					break;
				case "ADAPT_AVERAGE":
					$dial_method = "Predictive";
					break;
				case "MANUAL":
					$dial_method = "Manual";
					break;
				case "INBOUND_MAN":
					$dial_method = "Inbound-Man";
					break;
				default:
					$dial_method = $row->dial_method;
			}
			
			switch ($row->auto_dial_level)
			{
				case "0":
					$auto_dial_level = "OFF";
					break;
				case "1.0":
					$auto_dial_level = "SLOW";
					break;
				case "2.0":
					$auto_dial_level = "NORMAL";
					break;
				case "4.0":
					$auto_dial_level = "HIGH";
					break;
				case "6.0":
					$auto_dial_level = "MAX";
					break;
				default:
					$auto_dial_level = "ADVANCE";
					$auto_dial_num = $row->auto_dial_level;
			}
	
			if ($row->active == 'Y')
			{
				$active = '<span style="color:green;font-weight:bold;">ACTIVE</span>';
			} else {
				$active = '<span style="color:#F00;font-weight:bold;">INACTIVE</span>';
			}
			
			echo "<tr style=\"background-color:$bgcolor;\">\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modify('".$row->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN<br />".$row->campaign_id."\">".$row->campaign_id."</span></td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modify('".$row->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN<br />".$row->campaign_id."\">".str_replace("-","&#150;",$row->campaign_name)."</span></td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$dial_method</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$active</td>\n";
	//		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$auto_dial_level</td>\n";
	//		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$remote_status</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modify('".$row->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN<br />".$row->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delCamp('".$row->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN<br />".$row->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"viewInfo('".$row->campaign_id."','info','135px')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"VIEW INFO FOR CAMPAIGN<br />".$row->campaign_id."\"><img src=\"{$base}img/status_display_i.png\" style=\"cursor:pointer;width:12px;\" /></span></td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delCampaign[]\" value=\"".$row->campaign_id."\" /></td>\n";
			echo "</tr>\n";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
	}
?>
	</tbody>
</table>
</div>

<div id="showStatuses_div" class="hideSpan" align="center">
<table id="statusesTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th style="width:20%">&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;CUSTOM DISPOSITIONS</th>
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectStatusAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllStatus" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	if (count($campaign['list']) > 0) {
		$x=0;
		foreach ($campaign['list'] as $status)
		{
		    if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		    } else {
			$bgcolor = "#EFFBEF";
			$x=0;
		    }
		    
		    $statuses = ($camp_status[$status->campaign_id] != '') ? str_replace(' ',', ',trim($camp_status[$status->campaign_id])) : '<span style="text-decoration:line-through;font-style:italic;color:#777;">NONE</span>';
		    
		    echo "<tr style=\"background-color:$bgcolor;\">\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyStatus('".$status->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN STATUSES<br />".$status->campaign_id."\">".$status->campaign_id."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyStatus('".$status->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN STATUSES<br />".$status->campaign_id."\">".str_replace("-","&#150;",$status->campaign_name)."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;max-width:250px;overflow:hidden;text-overflow:ellipsis\">&nbsp;&nbsp;<span class='toolTip' title='$statuses'>$statuses</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyStatus('".$status->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN STATUSES<br />".$status->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delStatus('".$status->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN STATUSES<br />".$status->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"viewInfo('".$status->campaign_id."','dispo','auto')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"VIEW DISPOSITIONS FOR CAMPAIGN ".$status->campaign_id."\"><img src=\"{$base}img/status_display_i.png\" style=\"cursor:pointer;width:12px;\" /></span></td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delStatus[]\" value=\"".$status->campaign_id."\" /></td>\n";
		    echo "</tr>\n";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
	}
        ?>
    </tbody>
</table>
</div>

<div id="showLeadRecycling_div" class="hideSpan" align="center">
<table id="leadRecyclingTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th style="width:20%">&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;LEAD RECYCLES</th>
            <!--<th>&nbsp;&nbsp;ATTEMPT DELAY</th>-->
            <!--<th>&nbsp;&nbsp;MAXIMUM ATTEMPTS</th>-->
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectRecycleAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllRecycle" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	if (count($campaign['list']) > 0) {
		$x=0;
		foreach ($campaign['list'] as $leadrec)
		{
		    if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		    } else {
			$bgcolor = "#EFFBEF";
			$x=0;
		    }
		    
		    $statuses = ($lead_status[$leadrec->campaign_id] != '') ? str_replace(' ',', ',trim($lead_status[$leadrec->campaign_id])) : '<span style="text-decoration:line-through;font-style:italic;color:#777;">NONE</span>';
		    
		    echo "<tr style=\"background-color:$bgcolor;\">\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyRecycle('".$leadrec->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN LEAD RECYCLING<br />".$leadrec->campaign_id."\">".$leadrec->campaign_id."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyRecycle('".$leadrec->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN LEAD RECYCLING<br />".$leadrec->campaign_id."\">".str_replace("-","&#150;",$leadrec->campaign_name)."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;max-width:250px;overflow:hidden;text-overflow:ellipsis\">&nbsp;&nbsp;<span class='toolTip' title='$statuses'>$statuses</span>&nbsp;&nbsp;</td>\n";
		    //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_delay."&nbsp;&nbsp;</td>\n";
		    //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_maximum."&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyRecycle('".$leadrec->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN LEAD RECYCLING<br />".$leadrec->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delLeadRecycle('".$leadrec->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN LEAD RECYCLING<br />".$leadrec->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delLeadRecycling[]\" value=\"".$leadrec->campaign_id."\" /></td>\n";
		    echo "</tr>\n";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
	}
        ?>
    </tbody>
</table>
</div>

<div id="showPauseCodes_div" class="hideSpan" align="center">
<table id="pauseCodeTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th style="width:20%">&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;PAUSE CODES</th>
            <!--<th>&nbsp;&nbsp;ATTEMPT DELAY</th>-->
            <!--<th>&nbsp;&nbsp;MAXIMUM ATTEMPTS</th>-->
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectPauseCodeAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllPauseCodes" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	if (count($campaign['list']) > 0) {
		$x=0;
		foreach ($campaign['list'] as $pausecode)
		{
		    if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		    } else {
			$bgcolor = "#EFFBEF";
			$x=0;
		    }
		    //var_dump($pause_status);
		    $pausecodes = ($pause_status[$pausecode->campaign_id] != '') ? str_replace(' ',', ',trim($pause_status[$pausecode->campaign_id])) : '<span style="text-decoration:line-through;font-style:italic;color:#777;">NONE</span>';
		    
		    echo "<tr style=\"background-color:$bgcolor;\">\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyPauseCode('".$pausecode->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN PAUSE CODES<br />".$pausecode->campaign_id."\">".$pausecode->campaign_id."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyPauseCode('".$pausecode->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN PAUSE CODES<br />".$pausecode->campaign_id."\">".str_replace("-","&#150;",$pausecode->campaign_name)."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;max-width:250px;overflow:hidden;text-overflow:ellipsis\">&nbsp;&nbsp;<span class='toolTip' title='$pausecodes'>$pausecodes</span>&nbsp;&nbsp;</td>\n";
		    //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_delay."&nbsp;&nbsp;</td>\n";
		    //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_maximum."&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyPauseCode('".$pausecode->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN PAUSE CODES<br />".$pausecode->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delPauseCode('".$pausecode->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN PAUSE CODES<br />".$pausecode->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delPauseCodes[]\" value=\"".$pausecode->campaign_id."\" /></td>\n";
		    echo "</tr>\n";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
	}
        ?>
    </tbody>
</table>
</div>

<div id="showHotKeys_div" class="hideSpan" align="center">
<table id="hotKeysTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th style="width:20%">&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;HOTKEYS</th>
            <!--<th>&nbsp;&nbsp;ATTEMPT DELAY</th>-->
            <!--<th>&nbsp;&nbsp;MAXIMUM ATTEMPTS</th>-->
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectHotKeysAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllHotKeys" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	if (count($campaign['list']) > 0) {
		$x=0;
		foreach ($campaign['list'] as $hotkey)
		{
		    if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		    } else {
			$bgcolor = "#EFFBEF";
			$x=0;
		    }
		    //var_dump($pause_status);
		    $hotkeys = ($hotkey_status[$hotkey->campaign_id] != '') ? str_replace(' ',', ',trim($hotkey_status[$hotkey->campaign_id])) : '<span style="text-decoration:line-through;font-style:italic;color:#777;">NONE</span>';
		    
		    echo "<tr style=\"background-color:$bgcolor;\">\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyHotKeys('".$hotkey->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN HOTKEYS<br />".$hotkey->campaign_id."\">".$hotkey->campaign_id."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyHotKeys('".$hotkey->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN HOTKEYS<br />".$hotkey->campaign_id."\">".str_replace("-","&#150;",$hotkey->campaign_name)."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;max-width:250px;overflow:hidden;text-overflow:ellipsis\">&nbsp;&nbsp;<span class='toolTip' title='$hotkeys'>$hotkeys</span>&nbsp;&nbsp;</td>\n";
		    //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_delay."&nbsp;&nbsp;</td>\n";
		    //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_maximum."&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyHotKeys('".$hotkey->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN HOTKEYS<br />".$hotkey->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delHotKeys('".$hotkey->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN HOTKEYS<br />".$hotkey->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delHotKeys[]\" value=\"".$hotkey->campaign_id."\" /></td>\n";
		    echo "</tr>\n";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
	}
        ?>
    </tbody>
</table>
</div>

<div id="showFilters_div" class="hideSpan" align="center">
<table id="filtersTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;FILTER ID</th>
            <th style="width:30%">&nbsp;&nbsp;FILTER NAME</th>
            <th>&nbsp;&nbsp;</th>
            <!--<th>&nbsp;&nbsp;MAXIMUM ATTEMPTS</th>-->
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectFiltersAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllFilters" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	if (count($filters) > 0) {
		$x=0;
		foreach ($filters as $filter)
		{
		    if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		    } else {
			$bgcolor = "#EFFBEF";
			$x=0;
		    }
		    
		    echo "<tr style=\"background-color:$bgcolor;\">\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyFilters('".$filter->filter_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY FILTER ID<br />".$filter->filter_id."\">".$filter->filter_id."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyFilters('".$filter->filter_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY FILTER ID<br />".$filter->filter_id."\">".str_replace("-","&#150;",$filter->filter_name)."</span>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;</td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyFilters('".$filter->filter_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY FILTER ID<br />".$filter->filter_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delFilters('".$filter->filter_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE FILTER ID<br />".$filter->filter_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></td>\n";
		    echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delFilters[]\" value=\"".$filter->filter_id."\" /></td>\n";
		    echo "</tr>\n";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
	}
        ?>
    </tbody>
</table>
</div>

<div id="table_pagelinks" style="padding-top:10px;text-align: center;"><span style="float: left"><?=$pagelinks['links'] ?></span><span style="float: right"><?=$pagelinks['info'] ?></span></div>
<div id="table_filterlinks" style="padding-top:10px;text-align: center; display: none;"><span style="float: left"><?=$filterlinks['links'] ?></span><span style="float: right"><?=$filterlinks['info'] ?></span></div>

<!-- Status Overlay -->
<div id="overlayStatus" style="display:none;"></div>
<div id="boxStatus">
<a id="closeboxStatus" class="toolTip" title="CLOSE"></a>
<div id="overlayContentStatus">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1-nav-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>Status Wizard » Create New Status</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="statusCampID" name="statusCampID" onchange="javascript:checkStatus();">
					<option value="ALLCAMP">--- ALL CAMPAIGN ---</option>
					<?php
					foreach ($campaign['list'] as $camp)
					{
						echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Status:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><input type="text" maxlength="6" size="8" style="font-size:12px;" id="statusID" name="status" class="statusResult" /><font size="1" color="red">&nbsp;eg. NEW</font>
				<span id="sloading"></span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Status Name:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><input type="text" maxlength="25" size="25" style="font-size:12px;" id="statusName" name="status_name" class="statusResult" /><font size="1" color="red">&nbsp;eg. New Campaign Status</font></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Selectable:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="selectable" class="statusResult"><option value="Y">YES</option><option value="N">NO</option></select></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Human Answered:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="human_answered" class="statusResult"><option value="N">NO</option><option value="Y">YES</option></select></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Sale:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="sale" class="statusResult"><option value="N">NO</option><option value="Y">YES</option></select></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">DNC:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="dnc" class="statusResult"><option value="N">NO</option><option value="Y">YES</option></select></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Customer Contact:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="customer_contact" class="statusResult"><option value="N">NO</option><option value="Y">YES</option></select></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Not Interested:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="not_interested" class="statusResult"><option value="N">NO</option><option value="Y">YES</option></select></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Unworkable:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="unworkable" class="statusResult"><option value="N">NO</option><option value="Y">YES</option></select></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Scheduled Callback:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="scheduled_callback" class="statusResult"><option value="N">NO</option><option value="Y">YES</option></select></td>
		</tr>
	 </table>
	 </tr>
         <tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="hidden" name="category" value="UNDEFINED" class="statusResult" />
				<input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttones" id="statusSubmit" />
			</div>
                </td>
         </tr>
         </table>

</div>
</div>

<!-- Lead Recycle Overlay -->
<div id="overlayLeadRecycle" style="display:none;"></div>
<div id="boxLeadRecycle">
<a id="closeboxLeadRecycle" class="toolTip" title="CLOSE"></a>
<div id="overlayContentLeadRecycle">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1-nav-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>Lead Recycling Wizard » Create New Lead Recycling</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="leadCampID" name="leadCampID" class="leadRecycleResult" onchange="javascript:checkLeadRecycle();">
					<option value="">--- SELECT A CAMPAIGN ---</option>
					<?php
					foreach ($campaign['list'] as $camp)
					{
						echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
					}
					?>
				</select> <span id="lloading"></span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Status:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="leadStatusID" name="leadStatusID" class="leadRecycleResult" onchange="javascript:checkLeadRecycle();">
					<?php
					ksort($all_statuses['list']);
					foreach ($all_statuses['list'] as $key => $value)
					{
						ksort($value);
						echo "<optgroup label=\"".str_replace("_"," ",$key)."\">";
						foreach ($value as $xstatus => $istatus)
						{
							echo "<option value=\"{$xstatus}\">{$xstatus} - {$istatus}</option>";
						}
						echo "</optgroup>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Attempt Delay:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><input type="text" maxlength="3" size="7" style="font-size:12px;" id="attempt_delay" name="attempt_delay" class="leadRecycleResult" /><font size="1" color="red">&nbsp;Should be from 2 to 720 mins (12 hrs).</font></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Attempt Maximum:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="attempt_maximum" class="leadRecycleResult">
			<?php
			for ($x=1;$x<=10;$x++)
			{
				echo "<option>$x</option>";
			}
			?>
			</select></td>
		</tr>
	 </table>
	 </tr>
         <tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttons" id="leadRecycleSubmit" />
			</div>
                </td>
         </tr>
         </table>

</div>
</div>

<!-- Pause Codes Overlay -->
<div id="overlayPauseCodes" style="display:none;"></div>
<div id="boxPauseCodes">
<a id="closeboxPauseCodes" class="toolTip" title="CLOSE"></a>
<div id="overlayContentPauseCodes">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1-nav-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>Pause Code Wizard » Create New Pause Code</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="pauseCampID" name="pauseCampID" class="pauseCodeResult" onchange="javascript:checkPauseCodes();">
					<option value="">--- SELECT A CAMPAIGN ---</option>
					<?php
					foreach ($campaign['list'] as $camp)
					{
						echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Pause Code:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<input type="text" maxlength="6" size="8" style="font-size:12px;" id="pause_code" name="pause_code" class="pauseCodeResult" /> 
				<span id="ploading"></span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Pause Code Name:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><input type="text" maxlength="30" size="30" style="font-size:12px;" id="pause_code_name" name="pause_code_name" class="pauseCodeResult" /></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Billable:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="font-size:12px;" id="billable" name="billable" class="pauseCodeResult">
					<option value="YES">YES</option>
					<option value="NO">NO</option>
					<option value="HALF">HALF</option>
				</select>
			</td>
		</tr>
	 </table>
	 </tr>
         <tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttons" id="pauseCodeSubmit" />
			</div>
                </td>
         </tr>
         </table>

</div>
</div>

<!-- HotKeys Overlay -->
<div id="overlayHotKeys" style="display:none;"></div>
<div id="boxHotKeys">
<a id="closeboxHotKeys" class="toolTip" title="CLOSE"></a>
<div id="overlayContentHotKeys">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1-nav-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>HotKeys Wizard » Create New HotKey</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="hotKeysCampID" name="hotKeysCampID" class="hotKeysResult" onchange="javascript:checkHotKeys();">
					<option value="">--- SELECT A CAMPAIGN ---</option>
					<?php
					foreach ($campaign['list'] as $camp)
					{
						echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">HotKey:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="font-size:12px;" id="hotKeys" name="hotKeys" class="hotKeysResult" onchange="javascript:checkHotKeys();">
					<?php
					for ($i=1;$i<10;$i++)
					{
						echo "<option value=\"$i\">$i</option>";
					}
					?>
				</select> &nbsp; <span id="kloading"></span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Status:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="statusHotKeys" name="statusHotKeys" class="hotKeysResult" onchange="javascript:checkHotKeys();">
					<?php
					ksort($selectable_statuses['list']);
					foreach ($selectable_statuses['list'] as $key => $value)
					{
						ksort($value);
						echo "<optgroup label=\"".str_replace("_"," ",$key)."\">";
						foreach ($value as $xstatus => $istatus)
						{
							echo "<option value=\"{$xstatus}\">{$xstatus} - {$istatus}</option>";
						}
						echo "</optgroup>";
					}
					?>
				</select>
			</td>
		</tr>
	 </table>
	 </tr>
         <tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttons" id="hotKeysSubmit" />
			</div>
                </td>
         </tr>
         </table>

</div>
</div>

<!-- Lead Filters Overlay -->
<div id="overlayFilters" style="display:none;"></div>
<div id="boxFilters">
<a id="closeboxFilters" class="toolTip" title="CLOSE"></a>
<div id="overlayContentFilters">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1of2-navigation-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>Lead Filter Wizard » Create New Filter</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table id="filters1stTable" style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Filter ID:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_input('lead_filter_id',null,'id="lead_filter_id" class="filtersForm" maxlength="10" size="12"'); ?>
				<span id="floading"></span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Filter Name:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_input('lead_filter_name',null,'id="lead_filter_name" class="filtersForm" maxlength="30" size="35"'); ?>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Filter Comments:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_input('lead_filter_comments',null,'id="lead_filter_comments" class="filtersForm" maxlength="255" size="40"'); ?>
			</td>
		</tr>
		<?php
		if (! $this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
		?>
		<tr>
			<td align="right"><label class="modify-value">User Group:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_dropdown('user_group',$user_groups,null,'id="user_group" class="filtersForm"'); ?>
			</td>
		</tr>
		<?php
		}
		?>
	</table>

	<table id="filters2ndTable" style="width:90%;display:none;" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Fields:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_dropdown('fields_to_filter',$fields_to_filter,null,'id="fields_to_filter"'); ?>
			</td>
		</tr>
		<tr class="dateOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter Options:&nbsp;&nbsp;&nbsp;</label></td>
			<td>
				<?=form_radio('filter_sql_date','single',TRUE,'id="filter_sql_single"'); ?> SINGLE &nbsp; 
				<?=form_radio('filter_sql_date','range',FALSE,'id="filter_sql_range"'); ?> RANGE
			</td>
		</tr>
		<tr class="countOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter Options:&nbsp;&nbsp;&nbsp;</label></td>
			<td>
				<?=form_radio('filter_sql_oper','=',TRUE,'id="filter_sql_eq"'); ?> = &nbsp; 
				<?=form_radio('filter_sql_oper','>',FALSE,'id="filter_sql_gt"'); ?> &gt; &nbsp; 
				<?=form_radio('filter_sql_oper','<',FALSE,'id="filter_sql_lt"'); ?> &lt; &nbsp; 
				<?=form_radio('filter_sql_oper','<>',FALSE,'id="filter_sql_noteq"'); ?> &lt;&gt; &nbsp; 
				<?=form_radio('filter_sql_oper','>=',FALSE,'id="filter_sql_gteq"'); ?> &gt;= &nbsp; 
				<?=form_radio('filter_sql_oper','<=',FALSE,'id="filter_sql_lteq"'); ?> &lt;=
			</td>
		</tr>
		<tr class="otherOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter Options:&nbsp;&nbsp;&nbsp;</label></td>
			<td>
				<?=form_radio('filter_sql_other','IN',TRUE,'id="filter_sql_in"'); ?> IN &nbsp; 
				<?=form_radio('filter_sql_other','NOT IN',FALSE,'id="filter_sql_notin"'); ?> NOT IN
			</td>
		</tr>
		<tr class="dateOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter by Date:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_input('filter_by_date',date("Y-m-d"),'id="filter_by_date" class="datepicker" size="15" maxlength="10" readonly="readonly"'); ?> <span class="date_range" style="display:none;">to</span>
				<?=form_input('filter_by_end_date',date("Y-m-d"),'id="filter_by_end_date" placeholder="End Date" class="datepicker date_range" style="display:none;" size="15" maxlength="10" readonly="readonly"'); ?>
			</td>
		</tr>
		<tr class="countOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter by Called Count:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?php
				for($i=0;$i<=50;$i++)
				{
					$called_count[$i] = $i;
				}
				?>
				<?=form_dropdown('filter_by_called_count',$called_count,null,'id="filter_by_called_count"'); ?>
			</td>
		</tr>
		<tr class="countryOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter by Country Code:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_dropdown('filter_by_country',$countrycodes,'USA_1','id="filter_by_country" multiple="multiple" size="10"'); ?>
			</td>
		</tr>
		<tr class="areaOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter by Area Code:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_dropdown('filter_by_areacode',$areacodes,'USA_201','id="filter_by_areacode" multiple="multiple" size="10"'); ?>
			</td>
		</tr>
		<tr class="tzOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter by Timezone:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?php
				$TZ = array('12.75'=>'12.75','12.00'=>'12.00','11.00'=>'11.00','10.00'=>'10.00','9.50'=>'9.50',
					    '9.00'=>'9.00','8.00'=>'8.00','7.00'=>'7.00','6.50'=>'6.50','6.00'=>'6.00',
					    '5.75'=>'5.75','5.50'=>'5.50','5.00'=>'5.00','4.50'=>'4.50','4.00'=>'4.00',
					    '3.50'=>'3.50','3.00'=>'3.00','2.00'=>'2.00','1.00'=>'1.00','0.00'=>'0.00',
					    '-1.00'=>'-1.00','-2.00'=>'-2.00','-3.00'=>'-3.00','-3.50'=>'-3.50',
					    '-4.00'=>'-4.00','-5.00'=>'-5.00','-6.00'=>'-6.00','-7.00'=>'-7.00',
					    '-8.00'=>'-8.00','-9.00'=>'-9.00','-10.00'=>'-10.00','-11.00'=>'-11.00',
					    '-12.00'=>'-12.00');
				echo form_dropdown('filter_by_timezone',$TZ,'0.00','id="filter_by_timezone" multiple="multiple" size="10"');
				?>
			</td>
		</tr>
		<tr class="stateOptions" style="display:none;">
			<td align="right"><label class="modify-value">Filter by State:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_dropdown('filter_by_state',$states,'USA_AK','id="filter_by_state" multiple="multiple" size="10" style="width:300px;"'); ?>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">SQL Preview:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<?=form_input('filter_sql_preview',null,'id="filter_sql_preview" size="55" readonly="readonly"'); ?>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Filter Options:&nbsp;&nbsp;&nbsp;</label></td>
			<td>
				<?=form_button('filter_sql_insert','INSERT','id="filter_sql_insert" disabled="disabled"'); ?> &nbsp; 
				<span id="filter_sql_span" style="display:none;"><?=form_radio('filter_sql_div','AND',FALSE,'id="filter_sql_and"'); ?> AND &nbsp; 
				<?=form_radio('filter_sql_div','OR',FALSE,'id="filter_sql_or"'); ?> OR</span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Filter SQL:&nbsp;&nbsp;&nbsp;<br /><small><a id="clear_filter">Clear SQL</a></small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
			<td>
				<textarea name="lead_filter_sql" id="lead_filter_sql" rows="10" cols="50" class="filtersForm" style="resize:vertical;"></textarea><br />
				<small style="color:red;">* Disclaimer: Improper use may result in service disruption.<br /><span style="visibility:hidden;"><small>* Disclaimer: </small></span>Use at your own risk.</small>
			</td>
		</tr>
	</table>
	</tr>
	<tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="button" value="Next" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttons" id="filtersNext" />
				<input type="button" value="Back" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;display:none;" class="buttons" id="filtersBack" /><span style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;display:none;" class="filtersDivider">|</span><input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;display:none;" class="buttons" id="filtersSubmit" />
			</div>
                </td>
	</tr>
	</table>

</div>
</div>

<div id="showRealtime_div" class="hideSpan" align="center">
<img src="<?php echo $base; ?>img/goloading.gif" />
</div>
