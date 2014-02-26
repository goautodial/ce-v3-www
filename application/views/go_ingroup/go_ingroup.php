<?php
############################################################################################
####  Name:             go_ingroup.php                                                  ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  Modified by:      Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
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

#closebox {
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

#account_info_status .table_ingroups {
	margin:9px 0px 0px -10px;
	padding:7px 10px 0px 9px;
	border-top:#DDD 1px solid;
	width:100%;
}

.go_action_menu,.go_did_menu,.go_ivr_menu{
	z-index:999;
	position:absolute;
	top:188px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
}

#go_action_menu ul,#go_did_menu ul,#go_ivr_menu ul {
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

.go_action_submenu,.go_did_submenu,.go_ivr_submenu {
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

#showAllLists,#showAllUsers {
	color: #F00;
	font-size: 10px;
	cursor: pointer;
}
</style>
<link type="text/css" rel="stylesheet" href="<?=base_url()?>css/go_common_ce.css">
<link href="<?=base_url()?>css/go_callmenu/go_callmenu.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<? echo $base; ?>js/go_ingroup/jscolor.js"></script>
<script>
$(function()
{
	var inbound_create = '<?php echo $permissions->inbound_create; ?>';
	var inbound_read = '<?php echo $permissions->inbound_read; ?>';
	var inbound_update = '<?php echo $permissions->inbound_update; ?>';
	var inbound_delete = '<?php echo $permissions->inbound_delete; ?>';
	
	if (inbound_read=='N')
	{
		$("#showDIDs,#showIVRs").hide();
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
				
				switch (currentTab)
				{
					case "showDIDs":
						$('#add_ingroup').hide();
						$('#add_did').show();
						$('#add_ivr').hide();
						$('#search_ingroup').attr('placeholder','Search DIDs');
						break;
					
					case "showIVRs":
						$('#add_ingroup').hide();
						$('#add_did').hide();
						$('#add_ivr').show();
						$('#search_ingroup').attr('placeholder','Search IVRs');
						break;
					
					default:
						$('#add_ingroup').show();
						$('#add_did').hide();
						$('#add_ivr').hide();
						$('#search_ingroup').attr('placeholder','Search In-groups');
				}
				
			} else {
				$(this).addClass('menuOff');
				$(this).removeClass('menuOn');
				$('#' + currentTab + '_div').hide();
			}
		});
	});

	$('li.go_action_submenu,li.go_did_submenu,li.go_ivr_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});

	$('li.go_action_submenu').click(function () {
		var selectedIngroup = [];
		$('input:checkbox[id="delIngroup[]"]:checked').each(function()
		{
			selectedIngroup.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedIngroup.length<1)
		{
			alert('Please select a In-group.');
		}
		else
		{
			var s = '';
			if (selectedIngroup.length>1)
				s = 's';

			if (action == 'delete')
			{
				if ('<?php echo $permissions->ingroup_delete; ?>'!='N')
				{
					var what = confirm('Are you sure you want to delete the selected In-group'+s+'?');
					if (what)
					{
						$.post("<?php echo $base; ?>index.php/go_ingroup/deletesubmit/"+selectedIngroup, { action: "deletelist" }, function(data)
						{
							//alert(data);
							var datas = data.split(":");
							var i=0;
							if (datas[0] == "SUCCESS"){
								$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
								$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
							} else {
								alert(data);
							}
						});
					}
				} else {
					alert("Error: You do not have permission to delete in-group"+s+".");
				}
			}
			else
			{
				if ('<?php echo $permissions->ingroup_update; ?>'!='N')
				{
					$.post('<? echo $base; ?>index.php/go_ingroup/go_update_ingroup_list/'+action+'/'+selectedIngroup+'/', function(data)
					{
						$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
						$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
					});
				} else {
					alert("Error: You do not have permission to update in-group"+s+".");
				}
			}
		}
	});

	$('li.go_did_submenu').click(function () {
		var selectedDID = [];
		$('input:checkbox[id="delDID[]"]:checked').each(function()
		{
			selectedDID.push($(this).val());
		});

		$('#go_did_menu').slideUp('fast');
		$('#go_did_menu').hide();
		toggleDIDAction = $('#go_did_menu').css('display');

		var action = $(this).attr('id');
		if (selectedDID.length<1)
		{
			alert('Please select a DID.');
		}
		else
		{
			var s = '';
			if (selectedDID.length>1)
				s = 's';

			if (action == 'delete')
			{
				if ('<?php echo $permissions->ingroup_delete; ?>'!='N')
				{
					var what = confirm('Are you sure you want to delete the selected DID'+s+'?');
					if (what)
					{
						$.post("<?php echo $base; ?>index.php/go_ingroup/deletesubmit/"+selectedDID, { action: "deletedid" }, function(data)
						{
							//alert(data);
							var datas = data.split(":");
							var i=0;
							if (datas[0] == "SUCCESS"){
								$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
								$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
							} else {
								alert(data);
							}
						});
					}
				} else {
					alert("Error: You do not have permission to delete DID"+s+".");
				}
			}
			else
			{
				if ('<?php echo $permissions->ingroup_update; ?>'!='N')
				{
					$.post('<? echo $base; ?>index.php/go_ingroup/go_update_did_list/'+action+'/'+selectedDID+'/', function(data)
					{
						$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
						$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
					});
				} else {
					alert("Error: You do not have permission to update in-group"+s+".");
				}
			}
		}
	});

	$('li.go_ivr_submenu').click(function () {
		var selectedIVR = [];
		$('input:checkbox[id="delIVR[]"]:checked').each(function()
		{
			selectedIVR.push($(this).val());
		});

		$('#go_ivr_menu').slideUp('fast');
		$('#go_ivr_menu').hide();
		toggleIVRAction = $('#go_ivr_menu').css('display');

		var action = $(this).attr('id');
		if (selectedIVR.length<1)
		{
			alert('Please select a Call Menu / IVR.');
		}
		else
		{
			var s = '';
			if (selectedIVR.length>1)
				s = 's';

			if (action == 'delete')
			{
				if ('<?php echo $permissions->ingroup_delete; ?>'!='N')
				{
					var what = confirm('Are you sure you want to delete the selected Call Menu / IVR'+s+'?');
					if (what)
					{
						$.post("<?php echo $base; ?>index.php/go_ingroup/deletesubmit/"+selectedIVR, { action: "deletecallmenu" }, function(data)
						{
							//alert(data);
							var datas = data.split(":");
							var i=0;
							if (datas[0] == "SUCCESS"){
								$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
								$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
							} else {
								alert(data);
							}
						});
					}
				} else {
					alert("Error: You do not have permission to delete Call Menu / IVR"+s+".");
				}
			}
		}
	});

	$('#add_ingroup').click(function()
	{
		if (inbound_create!='N')
		{
			$('#overlay').fadeIn('fast');
			$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
			$('#box').animate({
				top: "70px"
			}, 500);
			
			$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_wizard/ingroup').fadeIn("slow");
		} else {
			alert("Error: You do not have permission to add a new in-group.");
		}
	});

	$('#add_did').click(function()
	{
		if (inbound_create!='N')
		{
			$('#overlay').fadeIn('fast');
			$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
			$('#box').animate({
				top: "70px"
			}, 500);
			
			$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_wizard/did').fadeIn("slow");
		} else {
			alert("Error: You do not have permission to add a new did.");
		}
	});

	$('#add_ivr').click(function()
	{
		if (inbound_create!='N')
		{
			$('#overlay').fadeIn('fast');
			$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
			$('#box').animate({
				top: "70px"
			}, 500);
			
			$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_wizard/ivr').fadeIn("slow");
		} else {
			alert("Error: You do not have permission to add a new call menu.");
		}
	});

	$('#closebox').click(function()
	{
		$('.advance_settings').hide();
		$('#advance_link').html('[ + ADVANCE SETTINGS ]');
		$('#box').animate({'top':'-3550px'},500);
		$('#overlay').fadeOut('slow');

		if ($('#request').text() == 'showList' || $('#request').text() == 'showDIDs' || $('#request').text() == 'showIVRs')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
		}
	});
	
	$("#showAllLists").click(function()
	{
		$(this).hide();
		$("#search_ingroup").val('');
		$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
	});
	
	$("#search_ingroup_button").click(function()
	{
		var type = 'ingroup';
		if ($("#showDIDs_div").is(':visible')) {
			var type = 'did';
		}
		
		if ($("#showIVRs_div").is(':visible')) {
			var type = 'ivr';
		}
		
		$('#showAllLists').show();
		var search = $("#search_ingroup").val();
		search = search.replace(/\s/g,"%20");
		if (search.length > 2) {
			$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
			$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_change_page/'+type+'/1/'+search);
		} else {
			alert("Please enter at least 3 alphanumeric characters to search.");
		}
	});
	
	$('#search_ingroup').bind("keydown keypress", function(event)
	{
		if (event.type == "keydown") {
			// For normal key press
			if (event.keyCode == 32 || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
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
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.which == 13 && event.type == "keydown") {
			var type = 'ingroup';
			if ($("#showDIDs_div").is(':visible')) {
				var type = 'did';
			}
			
			if ($("#showIVRs_div").is(':visible')) {
				var type = 'ivr';
			}
			
			$('#showAllLists').show();
			var search = $("#search_ingroup").val();
			search = search.replace(/\s/g,"%20");
			if (search.length > 2) {
				$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
				$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_change_page/'+type+'/1/'+search);
			} else {
				alert("Please enter at least 3 alphanumeric characters to search.");
			}
		}
	});
	
	$("#agentrankvalue").tablesorter();

	$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
});

function addIngroup()
{
	if ('<?php echo $permissions->inbound_create; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'fixed'});
		$('#box').animate({
			top: "70px"
		}, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_wizard/ingroup').fadeIn("slow");
	} else {
		alert("Error: You do not have permission to add a new in-group.");
	}
}

var isShift=false;
         
function isAlphaNumericwospace(keyCode)
{    
	if (keyCode == 16)
		isShift = true;

	return (((keyCode >= 48 && keyCode <= 57) && isShift == false) || (keyCode >= 65 && keyCode <= 90) || keyCode == 8 ||  keyCode == 35 ||  keyCode == 36 || keyCode == 37 || keyCode == 39 || keyCode == 46 || (keyCode >= 96 && keyCode <= 105));

	// 8  - Backspace Key
	// 35 - Home Key
	// 36 - End Key
	// 37 - Left Arrow Key
	// 39 - Right Arrow Key
	// 46 - Del Key  
}    

function isAlphaNumericwspace(keyCode)
{    
	if (keyCode == 16)
		isShift = true;

	return (((keyCode >= 48 && keyCode <= 57) && isShift == false) || (keyCode >= 65 && keyCode <= 90) || keyCode == 8 ||  keyCode == 35 ||  keyCode == 36 || keyCode == 37 || keyCode == 39 || keyCode == 46 || (keyCode >= 96 && keyCode <= 105) || keyCode == 32 || keyCode == 222);

	// 8  - Backspace Key
	// 35 - Home Key
	// 36 - End Key
	// 37 - Left Arrow Key
	// 39 - Right Arrow Key
	// 46 - Del Key  
} 

function KeyUp(keyCode)
{
	if (keyCode == 16)
		isShift = false;
}

function formsubmitlist() 
{
	var group_id = document.getElementById('group_id').value;         
	var group_name = document.getElementById('group_name').value;         
	
	if(group_id == "") {
		alert('Group I.D. is a required field.');
		return false;                                           
	}
	
	if(group_id.length < 2) {
		alert('Group I.D. must have at least 2 characters in length.');
		return false;                                           
	}
	
	if(group_name == "") {
		alert('Group Name is a required field.');
		return false;                                           
	}
	
	if(group_name.length < 2) {
		alert('Group Name must have at least 2 characters in length.');
		return false;                                           
	}
	
	document['go_listfrm'].submit();                         
}

function formsubmitdid() 
{
	var did_pattern = document.getElementById('did_pattern').value;		
	var did_description = document.getElementById('did_description').value;		

	if(did_pattern == "") {
		alert('Extension is a required field.');
		return false;						
	}	
	
	if(did_description == "") {
		alert('Description is a required field.');
		return false;						
	}	
	
	document['go_didfrm'].submit();
}

function showAdvanceMenuOptions(num)
{
	if ($(".advanceCallMenu_"+num).is(':visible')) {
		$(".advanceCallMenu_"+num).hide();
		$(".minMax").html("<pre style='display:inline'>[+]</pre> Advance Settings");
	} else {
		$(".advanceCallMenu_"+num).show();
		$(".minMax").html("<pre style='display:inline'>[-]</pre> Advance Settings");
	}
}

function showFilterOptions(action)
{
	if ($("#efilter_inbound_number").is(':visible')) {
		var route = $("#efilter_action").val();
		switch (action) {
			case "GROUP":
				$(".filterGroup").show();
				$(".filterAction").show();
				$(".filterURL").hide();
				showRouteOptions(route,'Filter');
				break;
			case "URL":
				$(".filterURL").show();
				$(".filterAction").show();
				$(".filterGroup").hide();
				showRouteOptions(route,'Filter');
				break;
			default:
				$(".filterGroup,.filterURL,.filterAction").hide();
				showRouteOptions('DISABLED','Filter');
		}
	} else {
		showRouteOptions('DISABLED','Filter');
	}
}

function showRouteOptions(route,type)
{
    switch (route) {
	    case "AGENT":
		    $(".didAgent"+type).show();
		    $(".didExtension"+type+",.didVoicemail"+type+",.didPhone"+type+",.didInbound"+type+",.didCallMenu"+type).hide();
		    break;
	    case "EXTEN":
		    $(".didExtension"+type).show();
		    $(".didAgent"+type+",.didVoicemail"+type+",.didPhone"+type+",.didInbound"+type+",.didCallMenu"+type).hide();
		    break;
	    case "VOICEMAIL":
		    $(".didVoicemail"+type).show();
		    $(".didAgent"+type+",.didExtension"+type+",.didPhone"+type+",.didInbound"+type+",.didCallMenu"+type).hide();
		    break;
	    case "PHONE":
		    $(".didPhone"+type).show();
		    $(".didAgent"+type+",.didVoicemail"+type+",.didExtension"+type+",.didInbound"+type+",.didCallMenu"+type).hide();
		    break;
	    case "IN_GROUP":
		    $(".didInbound"+type).show();
		    $(".didAgent"+type+",.didVoicemail"+type+",.didPhone"+type+",.didExtension"+type+",.didCallMenu"+type).hide();
		    break;
	    case "CALLMENU":
		    $(".didCallMenu"+type).show();
		    $(".didAgent"+type+",.didVoicemail"+type+",.didPhone"+type+",.didInbound"+type+",.didExtension"+type).hide();
		    break;
	    default:
		    $(".didCallMenu"+type+",.didAgent"+type+",.didVoicemail"+type+",.didPhone"+type+",.didInbound"+type+",.didExtension"+type).hide();
    }
}
</script>
<div id='outbody' class="wrap">
<div id="icon-inbound" class="icon32"></div>
<div style="float: right;margin-top:10px;margin-right:25px;"><span id="showAllLists" style="display: none">[Clear Search]</span>&nbsp;<?=form_input('search_ingroup',null,'id="search_ingroup" maxlength="100" placeholder="Search '.$bannertitle.'"') ?>&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="search_ingroup_button" style="cursor: pointer;" /></div>
<h2><? echo $bannertitle; ?></h2>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<!-- START LEFT WIDGETS -->
			<div class="postbox-container" style="width:99%;min-width:1200px;">
				<div class="meta-box-sortables ui-sortable">

					<!-- GO REPORTS WIDGET -->
					<div id="account_info_status" class="postbox">
						<div class="rightdiv toolTip" id="add_ingroup" title="Add New In-group">
                        	Add New In-group <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="rightdiv toolTip" style="display:none;" id="add_did" title="Add New DID">
                        	Add New DID <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="rightdiv toolTip" style="display:none;" id="add_ivr" title="Add New Call Menu">
                        	Add New Call Menu <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="hndle">
							<span><span id="title_bar" />&nbsp;<!--Campaign Listings--></span><!-- Title Bar -->
								<span class="postbox-title-action"></span>
							</span>
						</div>
						<div class="inside">

                            <div style="margin:<?php echo (preg_match("/^Windows/",$userOS)) ? "-23px" : "-22px"; ?> 0px -2px -10px;" id="request_tab"><span id="showList" class="tabtoggle menuOn">Ingroups</span><span id="showDIDs" class="tabtoggle menuOff">Phone Numbers (DIDs/TFNs)</span><span id="showIVRs" class="tabtoggle menuOff">Interactive Voice Response (IVR) Menus</span><span id="request" style="display:none;">showList</span></div>

							<div class="table_ingroups">
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

<!-- Action Menu -->
<div id='go_action_menu' class='go_action_menu'>
<ul>
<li class="go_action_submenu" title="Activate Selected" id="activate">Activate Selected</li>
<li class="go_action_submenu" title="Deactivate Selected" id="deactivate">Deactivate Selected</li>
<li class="go_action_submenu" title="Delete Selected" id="delete">Delete Selected</li>
</ul>
</div>

<!-- DID Menu -->
<div id='go_did_menu' class='go_did_menu'>
<ul>
<li class="go_did_submenu" title="Activate Selected" id="activate">Activate Selected</li>
<li class="go_did_submenu" title="Deactivate Selected" id="deactivate">Deactivate Selected</li>
<li class="go_did_submenu" title="Delete Selected" id="delete">Delete Selected</li>
</ul>
</div>

<!-- Call Menu / IVR Menu -->
<div id='go_ivr_menu' class='go_ivr_menu'>
<ul>
<li class="go_ivr_submenu" title="Delete Selected" id="delete">Delete Selected</li>
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
