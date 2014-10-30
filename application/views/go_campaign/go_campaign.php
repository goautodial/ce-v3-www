<?php
############################################################################################
####  Name:             go_campaign.php                                                 ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
<script src="<? echo $base; ?>js/jscolor/jscolor.js" type="text/javascript"></script>
<style type="text/css">
body, table, td, textarea, input, select, div, span{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:13px;
	font-stretch:normal;
}

body {
	height: 90%;
}

/* Style for overlay and box */
#overlay{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#statusOverlay,#fileOverlay,#hopperOverlay,#overlayStatus,
#overlayLeadRecycle,#overlayPauseCodes,#overlayHotKeys,#overlayFilters{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:102;
}

#box{
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

#statusBox,#hopperBox,#boxStatus,#boxLeadRecycle,#boxPauseCodes,#boxHotKeys,#boxFilters{
	position:absolute;
	top:-2550px;
	left:30%;
	right:30%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:103;
}

#fileBox{
	position:absolute;
	top:-2550px;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:103;
}

#closebox, #statusClosebox, #fileClosebox, #hopperClosebox, #closeboxStatus,
#closeboxLeadRecycle, #closeboxPauseCodes, #closeboxHotKeys, #closeboxFilters {
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

#mainTable td{
	font-size:13px;
}

#list_within_campaign td {
	font-size:10px;
}

.advance_settings {
	background-color:#EFFBEF;
	display:none;
}

.menuOn{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:6px 10px 7px 10px;";
}
else
{
	echo "padding:6px 10px 6px 10px;";
}
?>
	color:#777;
	font-size:13px;
	cursor:pointer;
	background-color:#FFF;
	border-right:#CCC 1px solid;
	color:#000;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.menuOff{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:6px 10px 6px 10px;";
}
else
{
	echo "padding:6px 10px 5px 10px;";
}
?>
	color:#555;
	font-size:13px;
	cursor:pointer;
	background-color:#efefef;
	border-right:#CCC 1px solid;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.menuOff:hover{
	background-color:#dfdfdf;
	color:#000;
}

#account_info_status .table_campaigns {
	margin:9px 0px 0px -10px;
	padding:7px 10px 0px 9px;
	border-top:#DDD 1px solid;
	width:100%;
}

.go_action_menu{
	z-index:999;
	position:absolute;
	top:188px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
}

#go_action_menu ul, #go_status_menu ul,
#go_camp_status_menu ul, #go_lead_recycle_menu ul,
#go_camp_lead_recycle_menu ul, #go_pausecodes_menu ul,
#go_camp_pausecodes_menu ul, #go_hotkeys_menu ul, #go_camp_hotkeys_menu ul,
#go_filters_menu ul {
	list-style-type:none;
	padding: 1px;
	margin: 0px;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.go_action_submenu, .go_status_submenu, .go_camp_status_submenu,
.go_lead_recycle_submenu, .go_camp_lead_recycle_submenu,
.go_pausecodes_submenu, .go_camp_pausecodes_submenu,
.go_hotkeys_submenu, .go_camp_hotkeys_submenu, .go_filters_submenu {
	padding: 3px 10px 3px 5px;
	margin: 0px;
}

.rightdiv{
	cursor:pointer;
	color:#555;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.hideSpan {
	display:none;
}

#showAllLists {
	color: #F00;
	font-size: 10px;
	cursor: pointer;
}
</style>
<script>
$(function()
{
	var camp_create = '<?php echo $permissions->campaign_create; ?>';
	var camp_read = '<?php echo $permissions->campaign_read; ?>';
	var camp_update = '<?php echo $permissions->campaign_update; ?>';
	var camp_delete = '<?php echo $permissions->campaign_delete; ?>';
	
	if (camp_read=='N')
	{
		$("#showStatuses,#showLeadRecycling,#showPauseCodes,#showHotKeys").hide();
	}
	
	$('.tabtoggle').click(function()
	{
		var request = $(this).attr('id');
		var currentTab = '';
		$('.tabtoggle').each(function() {
			currentTab = $(this).attr('id');
			if (request == $(this).attr('id')) {
				$(this).addClass('menuOn');
				$(this).removeClass('menuOff');
				$('#' + currentTab + '_div').show();
				$('#request').html(request);
				if (currentTab == 'showRealtime')
				{
					$('#' + currentTab + '_div').empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#' + currentTab + '_div').load('<? echo $base; ?>index.php/go_campaign_ce/go_monitoring/');
				}
				
				switch (currentTab)
				{
					case "showStatuses":
						$('#add_campaign').hide();
						$('#add_status').show();
						$('#add_lead_recycle').hide();
						$('#add_pause_code').hide();
						$('#add_hotkey').hide();
						$('#add_filter').hide();
						$('#table_pagelinks').show();
						$('#table_filterlinks').hide();
						break;
					
					case "showLeadRecycling":
						$('#add_campaign').hide();
						$('#add_status').hide();
						$('#add_lead_recycle').show();
						$('#add_pause_code').hide();
						$('#add_hotkey').hide();
						$('#add_filter').hide();
						$('#table_pagelinks').show();
						$('#table_filterlinks').hide();
						break;
					
					case "showPauseCodes":
						$('#add_campaign').hide();
						$('#add_status').hide();
						$('#add_lead_recycle').hide();
						$('#add_pause_code').show();
						$('#add_hotkey').hide();
						$('#add_filter').hide();
						$('#table_pagelinks').show();
						$('#table_filterlinks').hide();
						break;
					
					case "showHotKeys":
						$('#add_campaign').hide();
						$('#add_status').hide();
						$('#add_lead_recycle').hide();
						$('#add_pause_code').hide();
						$('#add_hotkey').show();
						$('#add_filter').hide();
						$('#table_pagelinks').show();
						$('#table_filterlinks').hide();
						break;
					
					case "showFilters":
						$('#add_campaign').hide();
						$('#add_status').hide();
						$('#add_lead_recycle').hide();
						$('#add_pause_code').hide();
						$('#add_hotkey').hide();
						$('#add_filter').show();
						$('#table_pagelinks').hide();
						$('#table_filterlinks').show();
						break;
					
					default:
						$('#add_campaign').show();
						$('#add_status').hide();
						$('#add_lead_recycle').hide();
						$('#add_pause_code').hide();
						$('#add_hotkey').hide();
						$('#add_filter').hide();
						$('#table_pagelinks').show();
						$('#table_filterlinks').hide();
				}
				
			} else {
				$(this).addClass('menuOff');
				$(this).removeClass('menuOn');
				$('#' + currentTab + '_div').hide();
				if (currentTab == 'showRealtime')
				{
					$('#' + currentTab + '_div').empty();
				}
			}
		});
	});

	$('li.go_action_submenu,li.go_status_submenu,li.go_camp_status_submenu,li.go_lead_recycle_submenu,li.go_camp_lead_recycle_submenu,li.go_pausecodes_submenu,li.go_camp_pausecodes_submenu,li.go_hotkeys_submenu,li.go_camp_hotkeys_submenu,li.go_filters_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});

	$('li.go_action_submenu').click(function () {
		var selectedCampaign = [];
		$('input:checkbox[id="delCampaign[]"]:checked').each(function()
		{
			selectedCampaign.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedCampaign.length<1)
		{
			alert('Please select a Campaign.');
		}
		else
		{
			var s = '';
			if (selectedCampaign.length>1)
				s = 's';

			if (action == 'delete')
			{
				if ('<?php echo $permissions->campaign_delete; ?>'!='N')
				{
					var what = confirm('Are you sure you want to delete the selected Campaign'+s+'?');
					if (what)
					{
						$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_check_for_leads/"+selectedCampaign, function(data)
						{
							//alert(data);
							if (data == "OK"){
								$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_update_campaign_list/'+action+'/'+selectedCampaign+'/');
							} else {
								alert(data);
							}
						});
					}
				} else {
					alert("Error: You do not have permission to delete campaign"+s+".");
				}
			}
			else
			{
				if ('<?php echo $permissions->campaign_update; ?>'!='N')
				{
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_update_campaign_list/'+action+'/'+selectedCampaign+'/');
				} else {
					alert("Error: You do not have permission to update campaign"+s+".");
				}
			}
		}
	});

	$('li.go_status_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedStatus = [];
			$('input:checkbox[id="delStatus[]"]:checked').each(function()
			{
				selectedStatus.push($(this).val());
			});
	
			$('#go_status_menu').slideUp('fast');
			$('#go_status_menu').hide();
			toggleStatus = $('#go_status_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedStatus.length<1)
			{
				alert('Please select a Campaign.');
			}
			else
			{
				var s = '';
				if (selectedStatus.length>1)
					s = 'es';
	
				var what = confirm('Are you sure you want to delete the selected Status'+s+'?');
				if (what)
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_delete_campaign_statuses_list/'+selectedStatus+'/');
				}
			}
		} else {
			alert("Error: You do not have permission to delete campaign statuses"+s+".");
		}
	});

	$('li.go_camp_status_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedCampStatus = [];
			var camp = $('#campaign_id_mod').val();
			$('input:checkbox[id="delCampStatus[]"]:checked').each(function()
			{
				selectedCampStatus.push($(this).val());
			});
	
			$('#go_camp_status_menu').slideUp('fast');
			$('#go_camp_status_menu').hide();
			toggleCampStatus = $('#go_camp_status_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedCampStatus.length<1)
			{
				alert('Please select a Status.');
			}
			else
			{
				var s = '';
				if (selectedCampStatus.length>1)
					s = 'es';
	
				var what = confirm('Are you sure you want to delete the selected Status'+s+'?');
				if (what)
				{
					$('#overlayContent').load('<? echo $base; ?>index.php/go_campaign_ce/go_get_campaign_statuses/'+camp+'/delete_status/'+selectedCampStatus);
				}
			}
		} else {
			alert("Error: You do not have permission to delete campaign status"+s+".");
		}
	});

	$('li.go_lead_recycle_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedLeadRecycling = [];
			$('input:checkbox[id="delLeadRecycling[]"]:checked').each(function()
			{
				selectedLeadRecycling.push($(this).val());
			});
	
			$('#go_lead_recycle_menu').slideUp('fast');
			$('#go_lead_recycle_menu').hide();
			toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedLeadRecycling.length<1)
			{
				alert('Please select a Campaign.');
			}
			else
			{
				var s = '';
				if (selectedLeadRecycling.length>1)
					s = 'es';
	
				var what = confirm('Are you sure you want to delete the selected Status'+s+'?');
				if (what)
				{
					$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/delselected/"+selectedLeadRecycling, function()
					{
						if ($('#request').text() == 'showLeadRecycling')
						{
							$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
							$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
						}
					});
				}
			}
		} else {
			alert("Error: You do not have permission to delete lead recycling status"+s+".");
		}
	});

	$('li.go_camp_lead_recycle_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedCampLeadRecycling = [];
			var camp = $('#campaign_id_mod').val();
			$('input:checkbox[id="delCampLeadRecycling[]"]:checked').each(function()
			{
				selectedCampLeadRecycling.push($(this).val());
			});
	
			$('#go_camp_lead_recycle_menu').slideUp('fast');
			$('#go_camp_lead_recycle_menu').hide();
			toggleCampLeadRecycling = $('#go_camp_lead_recycle_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedCampLeadRecycling.length<1)
			{
				alert('Please select a Status.');
			}
			else
			{
				var s = '';
				if (selectedCampLeadRecycling.length>1)
					s = 'es';
	
				var what = confirm('Are you sure you want to delete the selected Status'+s+'?');
				if (what)
				{
					$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/delete_status/"+camp+"/"+selectedCampLeadRecycling, function()
					{
						$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
						$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/modify/'+camp).fadeIn("slow");
					});
				}
			}
		} else {
			alert("Error: You do not have permission to delete lead recycling status"+s+".");
		}
	});

	$('li.go_pausecodes_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedPauseCodes = [];
			$('input:checkbox[id="delPauseCodes[]"]:checked').each(function()
			{
				selectedPauseCodes.push($(this).val());
			});
	
			$('#go_pausecodes_menu').slideUp('fast');
			$('#go_pausecodes_menu').hide();
			togglePauseCodes = $('#go_pausecodes_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedPauseCodes.length<1)
			{
				alert('Please select a Campaign.');
			}
			else
			{
				var s = '';
				if (selectedPauseCodes.length>1)
					s = 's';
	
				var what = confirm('Are you sure you want to delete the selected Pause Code'+s+'?');
				if (what)
				{
					$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/delselected/"+selectedPauseCodes, function()
					{
						if ($('#request').text() == 'showPauseCodes')
						{
							$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
							$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
						}
					});
				}
			}
		} else {
			alert("Error: You do not have permission to delete campaign pause code"+s+".");
		}
	});

	$('li.go_camp_pausecodes_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedCampPauseCodes = [];
			var camp = $('#campaign_id_mod').val();
			$('input:checkbox[id="delCampPauseCodes[]"]:checked').each(function()
			{
				selectedCampPauseCodes.push($(this).val());
			});
	
			$('#go_camp_pausecodes_menu').slideUp('fast');
			$('#go_camp_pausecodes_menu').hide();
			toggleCampPauseCodes = $('#go_camp_pausecodes_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedCampPauseCodes.length<1)
			{
				alert('Please select a Pause Code.');
			}
			else
			{
				var s = '';
				if (selectedCampPauseCodes.length>1)
					s = 's';
	
				var what = confirm('Are you sure you want to delete the selected Pause Code'+s+'?');
				if (what)
				{
					$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/delete_status/"+camp+"/"+selectedCampPauseCodes, function()
					{
						$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
						$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/modify/'+camp).fadeIn("slow");
					});
				}
			}
		} else {
			alert("Error: You do not have permission to delete campaign pause code"+s+".");
		}
	});

	$('li.go_hotkeys_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedHotKeys = [];
			$('input:checkbox[id="delHotKeys[]"]:checked').each(function()
			{
				selectedHotKeys.push($(this).val());
			});
	
			$('#go_hotkeys_menu').slideUp('fast');
			$('#go_hotkeys_menu').hide();
			toggleHotKeys = $('#go_hotkeys_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedHotKeys.length<1)
			{
				alert('Please select a Campaign.');
			}
			else
			{
				var s = '';
				if (selectedHotKeys.length>1)
					s = 's';
	
				var what = confirm('Are you sure you want to delete the selected HotKey'+s+'?');
				if (what)
				{
					$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/delselected/"+selectedHotKeys, function()
					{
						if ($('#request').text() == 'showHotKeys')
						{
							$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
							$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
						}
					});
				}
			}
		} else {
			alert("Error: You do not have permission to delete campaign hotkey"+s+".");
		}
	});

	$('li.go_camp_hotkeys_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedCampHotKeys = [];
			var camp = $('#campaign_id_mod').val();
			$('input:checkbox[id="delCampHotKeys[]"]:checked').each(function()
			{
				selectedCampHotKeys.push($(this).val());
			});
	
			$('#go_camp_hotkeys_menu').slideUp('fast');
			$('#go_camp_hotkeys_menu').hide();
			toggleCampHotKeys = $('#go_camp_hotkeys_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedCampHotKeys.length<1)
			{
				alert('Please select a Pause Code.');
			}
			else
			{
				var s = '';
				if (selectedCampHotKeys.length>1)
					s = 's';
	
				var what = confirm('Are you sure you want to delete the selected HotKey'+s+'?');
				if (what)
				{
					$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/delete_status/"+camp+"/"+selectedCampHotKeys, function()
					{
						$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
						$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/modify/'+camp).fadeIn("slow");
					});
				}
			}
		} else {
			alert("Error: You do not have permission to delete campaign hotkey"+s+".");
		}
	});

	$('li.go_filters_submenu').click(function () {
		if ('<?php echo $permissions->campaign_delete; ?>'!='N')
		{
			var selectedFilters = [];
			$('input:checkbox[id="delFilters[]"]:checked').each(function()
			{
				selectedFilters.push($(this).val());
			});
	
			$('#go_filters_menu').slideUp('fast');
			$('#go_filters_menu').hide();
			toggleFilters = $('#go_filters_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedFilters.length<1)
			{
				alert('Please select a Lead Filter.');
			}
			else
			{
				var s = '';
				if (selectedFilters.length>1)
					s = 's';
	
				var what = confirm('Are you sure you want to delete the selected Lead Filter'+s+'?');
				if (what)
				{

					$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_sql_filters/delete/"+selectedFilters, function()
					{
						if ($('#request').text() == 'showFilters')
						{
							$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
							$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
						}
					});
				}
			}
		} else {
			alert("Error: You do not have permission to delete campaign statuses"+s+".");
		}
	});

	$('#add_campaign').click(function()
	{
		if (camp_create!='N')
		{
			$('#overlay').fadeIn('fast');
			$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
			$('#box').animate({
				top: "70px"
			}, 500);
			
			$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_wizard/').fadeIn("slow");
		} else {
			alert("Error: You do not have permission to add a new campaign.");
		}
	});
	$('#add_status').click(function()
	{
		if (camp_create!='N')
		{
			$('#overlayStatus').fadeIn('fast');
			$('#boxStatus').css({'width': '750px','margin-left': 'auto', 'margin-right': 'auto', 'left': '14%', 'right': '14%', 'padding-bottom': '10px', 'position': 'fixed'});
			$('#boxStatus').animate({
				top: "70px"
			}, 500);
		} else {
			alert("Error: You do not have permission to add a new campaign status.");
		}
	});
	$('#add_lead_recycle').click(function()
	{
		if (camp_create!='N')
		{
			$('#overlayLeadRecycle').fadeIn('fast');
			$('#boxLeadRecycle').css({'width': '700px','margin-left': 'auto', 'margin-right': 'auto', 'left': '14%', 'right': '14%', 'padding-bottom': '10px', 'position': 'fixed'});
			$('#boxLeadRecycle').animate({
				top: "70px"
			}, 500);
		} else {
			alert("Error: You do not have permission to add a new lead recycling status.");
		}
	});
	$('#add_pause_code').click(function()
	{
		if (camp_create!='N')
		{
			$('#overlayPauseCodes').fadeIn('fast');
			$('#boxPauseCodes').css({'width': '700px','margin-left': 'auto', 'margin-right': 'auto', 'left': '14%', 'right': '14%', 'padding-bottom': '10px', 'position': 'fixed'});
			$('#boxPauseCodes').animate({
				top: "70px"
			}, 500);
		} else {
			alert("Error: You do not have permission to add a new pause code.");
		}
	});
	$('#add_hotkey').click(function()
	{
		if (camp_create!='N')
		{
			$('#overlayHotKeys').fadeIn('fast');
			$('#boxHotKeys').css({'width': '700px','margin-left': 'auto', 'margin-right': 'auto', 'left': '14%', 'right': '14%', 'padding-bottom': '10px', 'position': 'fixed'});
			$('#boxHotKeys').animate({
				top: "70px"
			}, 500);
		} else {
			alert("Error: You do not have permission to add a new hotkey status.");
		}
	});
	$('#add_filter').click(function()
	{
		if (camp_create!='N')
		{
			$('#overlayFilters').fadeIn('fast');
			$('#boxFilters').css({'width': '800px','margin-left': 'auto', 'margin-right': 'auto', 'left': '14%', 'right': '14%', 'padding-bottom': '10px', 'position': 'absolute'});
			$('#boxFilters').animate({
				top: "-50px"
			}, 500);
		} else {
			alert("Error: You do not have permission to add a new lead filter.");
		}
	});

	$('#closebox').click(function()
	{
		$('.advance_settings').hide();
		$('#advance_link').html('[ + ADVANCE SETTINGS ]');
		$('#box').animate({'top':'-2550px'},500);
		$('#overlay').fadeOut('slow');

		if ($('#request').text() == 'showStatuses' || $('#request').text() == 'showLeadRecycling'
			|| $('#request').text() == 'showPauseCodes' || $('#request').text() == 'showHotKeys'
			|| $('#request').text() == 'showList')
		{
		    var search = $('#search_list').val();
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/1/'+search);
		}

		if ($('#wizardSpan').text() != '' && $('#wizardSpan').text() == 'true')
		{
			var campaign_type = $('#campaign_type').val();
			var campaign_id = $('#campaignIDSpan').text();
			var step = $('#stepNumber').text();
			$('#wizardSpan').text('false');

			if (step > 1)
			{
				$.post('<?php echo $base; ?>index.php/go_campaign_ce/go_campaign_wizard/back/1/'+campaign_id);
			}
		}
	});

	$('#statusClosebox').click(function()
	{
		var camp = $(this).attr('rel');
		$('#statusBox').animate({'top':'-2550px'},500);
		$('#statusOverlay').fadeOut('slow');

//		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
//		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_campaign_statuses/'+camp).fadeIn("slow");
	});
	
	$("#showAllLists").click(function()
	{
		$(this).hide();
		$("#search_list").val('');
		$("#table_reports").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
	});
	
	$("#search_list_button").click(function()
	{
		$('#showAllLists').show();
		var search = $("#search_list").val();
		search = search.replace(/\s/g,"%20");
		if (search.length > 2) {
			$("#table_reports").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/1/'+search);
		} else {
			alert("Please enter at least 3 characters to search.");
		}
	});
	
	$('#search_list').bind("keydown keypress", function(event)
	{
		if (event.type == "keydown") {
			// For normal key press
			if (event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.which == 13 && event.type == "keydown") {
			$('#showAllLists').show();
			var search = $("#search_list").val();
			search = search.replace(/\s/g,"%20");
			if (search.length > 2) {
				$("#table_reports").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/1/'+search);
			} else {
				alert("Please enter at least 3 characters to search.");
			}
		}
	});
	
	$('#showAllLists').hide();
	$('#search_list').val('');

	// Test: refresh every 4secs
	var refreshId = setInterval(function()
	{
	    if ($('#showRealtime_div').is(":visible"))
	    {
			$('#showRealtime_div').load('<? echo $base; ?>index.php/go_campaign_ce/go_monitoring/');
	    }
	}, 4000);
});

function addNewCampaign()
{
	if ('<?php echo $permissions->campaign_create; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
		$('#box').animate({
			top: "70px"
		}, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_wizard/').fadeIn("slow");
	} else {
		alert("Error: You do not have permission to add a new campaign.");
	}
}

function addNewStatus()
{
	var str = $('.addNewStatus').serialize();
	var camp = $('#campIDStatus').val();
	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_add_new_statuses/'+camp+'/add_status/'+str).fadeIn("slow");
}
</script>
<div id='outbody' class="wrap">
<div id="icon-campaign" class="icon32"></div>
<div style="float: right;margin-top:15px;margin-right:25px;"><span id="showAllLists" style="display: none">[Clear Search]</span>&nbsp;<?=form_input('search_list',null,'id="search_list" maxlength="100" placeholder="Search '.$bannertitle.'"') ?>&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="search_list_button" style="cursor: pointer;" /></div>
<h2><? echo $bannertitle; ?></h2>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<!-- START LEFT WIDGETS -->
			<div class="postbox-container" style="width:99%;min-width:1200px;">
				<div class="meta-box-sortables ui-sortable">

					<!-- GO REPORTS WIDGET -->
					<div id="account_info_status" class="postbox">
						<div class="rightdiv toolTip" id="add_campaign" title="Add New Campaign">
							Add New Campaign <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="rightdiv toolTip" style="display:none;" id="add_status" title="Add New Status">
							Add New Status <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="rightdiv toolTip" style="display:none;" id="add_lead_recycle" title="Add New Lead Recycle">
							Add New Lead Recycle <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="rightdiv toolTip" style="display:none;" id="add_pause_code" title="Add New Pause Code">
							Add New Pause Code <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="rightdiv toolTip" style="display:none;" id="add_hotkey" title="Add New HotKey">
							Add New HotKey <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="rightdiv toolTip" style="display:none;" id="add_filter" title="Add New Filter">
							Add New Filter <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="hndle">
							<span><span id="title_bar" />&nbsp;<!--Campaign Listings--></span><!-- Title Bar -->
								<span class="postbox-title-action"></span>
							</span>
						</div>
						<div class="inside">

                            <div style="margin:<?php echo (preg_match("/^Windows/",$userOS)) ? "-23px" : "-22px"; ?> 0px -2px -10px;" id="request_tab"><span id="showList" class="tabtoggle menuOn">Campaigns</span><span id="showStatuses" class="tabtoggle menuOff">Dispositions</span><span id="showLeadRecycling" class="tabtoggle menuOff">Lead Recycling</span><span id="showPauseCodes" class="tabtoggle menuOff">Pause Codes</span><span id="showHotKeys" class="tabtoggle menuOff">HotKeys</span><span id="showFilters" class="tabtoggle menuOff">Lead Filters</span><span id="showRealtime" class="tabtoggle menuOff hidden">Real-time Monitoring</span><span id="request" style="display:none;">showList</span></div>

							<div class="table_campaigns">
                                <div id="table_reports">
                                </div>
                            </div>

							<div class="widgets-content-text">
							<br class="clear">

								<br class="clear">
							<br class="clear">
							<!--
							<p>
								Configure this widget's (today's status) settings.
								<a href="" class="button">Configure</a>
							</p>
							<p>
								You are using GoAutoDial 3.0.1.
								<a href="" class="button">Update to 3.1.3</a>
							</p>
							-->
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- END LEFT WIDGETS -->

			<!-- START RIGHT WIDGETS -->
			<div class="postbox-container" style="width:49.5%;display:none;">
				<div class="meta-box-sortables ui-sortable">

				</div>
			</div>
			<!-- END RIGHT WIDGETS -->

			<div class="postbox-container" style="display:none;width:49.5%;">
				<div style="" id="column3-sortables" class="meta-box-sortables ui-sortable">
				</div>
			</div>
			<div class="postbox-container" style="display:none;width:49.5%;">
				<div style="" id="column4-sortables" class="meta-box-sortables ui-sortable">
				</div>
			</div>
		</div><!-- dashboard-widgets -->
	</div><!-- dashboard-widgets-wrap -->
</div><!-- wrap -->

<!-- Overlay1 -->
<div id="overlay" style="display:none;"></div>
<div id="box">
<a id="closebox" class="toolTip" title="CLOSE"></a>
<div id="overlayContent"></div>
</div>

<!-- Overlay2 -->
<div id="statusOverlay" style="display:none;"></div>
<div id="statusBox">
<a id="statusClosebox" class="toolTip" title="CLOSE"></a>
<div id="statusOverlayContent"></div>
</div>

<!-- Hopper Overlay -->
<div id="hopperOverlay" style="display:none;"></div>
<div id="hopperBox">
<a id="hopperClosebox" class="toolTip" title="CLOSE"></a>
<div id="hopperOverlayContent"></div>
</div>

<!-- Action Menu -->
<div id='go_action_menu' class='go_action_menu'>
<ul>
<li class="go_action_submenu" title="Activate Selected" id="activate">Activate Selected</li>
<li class="go_action_submenu" title="Deactivate Selected" id="deactivate">Deactivate Selected</li>
<li class="go_action_submenu" title="Delete Selected" id="delete">Delete Selected</li>
</ul>
</div>

<!-- Status Menu -->
<div id='go_status_menu' class='go_action_menu'>
<ul>
<li class="go_status_submenu" title="Delete Selected" id="delete_status">Delete Selected</li>
</ul>
</div>

<!-- Campaign Status Menu -->
<div id='go_camp_status_menu' class='go_action_menu'>
<ul>
<li class="go_camp_status_submenu" title="Delete Selected" id="delete_status">Delete Selected</li>
</ul>
</div>

<!-- Lead Recycle Menu -->
<div id='go_lead_recycle_menu' class='go_action_menu'>
<ul>
<li class="go_lead_recycle_submenu" title="Delete Selected" id="delete_status">Delete Selected</li>
</ul>
</div>

<!-- Campaign Lead Recycle Menu -->
<div id='go_camp_lead_recycle_menu' class='go_action_menu'>
<ul>
<li class="go_camp_lead_recycle_submenu" title="Delete Selected" id="delete_status">Delete Selected</li>
</ul>
</div>

<!-- Pause Codes Menu -->
<div id='go_pausecodes_menu' class='go_action_menu'>
<ul>
<li class="go_pausecodes_submenu" title="Delete Selected" id="delete_status">Delete Selected</li>
</ul>
</div>

<!-- Campaign Pause Codes Menu -->
<div id='go_camp_pausecodes_menu' class='go_action_menu'>
<ul>
<li class="go_camp_pausecodes_submenu" title="Delete Selected" id="delete_status">Delete Selected</li>
</ul>
</div>

<!-- HotKeys Menu -->
<div id='go_hotkeys_menu' class='go_action_menu'>
<ul>
<li class="go_hotkeys_submenu" title="Delete Selected" id="delete_status">Delete Selected</li>
</ul>
</div>

<!-- Campaign HotKeys Menu -->
<div id='go_camp_hotkeys_menu' class='go_action_menu'>
<ul>
<li class="go_camp_hotkeys_submenu" title="Delete Selected" id="delete_status">Delete Selected</li>
</ul>
</div>

<!-- Filters Menu -->
<div id='go_filters_menu' class='go_action_menu'>
<ul>
<li class="go_filters_submenu" title="Delete Selected" id="delete_filters">Delete Selected</li>
</ul>
</div>

<!-- Debug-->
<div id="showDebug" style="display: none"></div>

<div id="hiddenToggle" style="display: none"></div>
<div id="wizardSpan" style="display: none">false</div>
<div class="clear" style="display: none"></div></div><!-- wpbody-content -->
<div class="clear" style="display: none"></div></div><!-- wpbody -->
<div class="clear" style="display: none"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
