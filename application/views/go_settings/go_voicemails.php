<?php
####################################################################################################
####  Name:             	go_voicemails.php                                                   ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
?>
<style type="text/css">
body, table, td, textarea, input, select, div, span{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:13px;
	font-stretch:normal;
}

body{height:90%;}

a:hover {
	color:#F00;
	cursor:pointer;
}

#selectAction {
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
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

#statusOverlay,#fileOverlay,#hopperOverlay,#overlayStatus{
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

#statusBox,#fileBox,#hopperBox,#boxStatus{
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

#closebox, #statusClosebox, #fileClosebox, #hopperClosebox, #closeboxStatus{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

#mainTable th {
	text-align:left;
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

#account_info_status .table_container {
	margin:9px 0px 0px -10px;
	padding:7px 10px 0px 9px;
	border-top:#CCC 1px solid;
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

#go_action_menu ul, #go_status_menu ul, #go_camp_status_menu ul{
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

.go_action_submenu, .go_status_submenu, .go_camp_status_submenu{
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

/* Table Sorter */
table.tablesorter thead tr .header {
	cursor: pointer;
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

				if (currentTab == 'showList')
				{
					$('#add_server').show();
				} else {
					$('#add_server').hide();
				}
			} else {
				$(this).addClass('menuOff');
				$(this).removeClass('menuOn');
				$('#' + currentTab + '_div').hide();
			}
		});
	});

	$('#closebox').click(function()
	{
		$('#box').animate({'top':'-2550px'},500);
		$('#overlay').fadeOut('slow');
		
		//if (intervalHandle!==null)
		//{
		//	clearInterval(intervalHandle);
		//}
	});
	
	$("#showAllLists").click(function()
	{
		$(this).hide();
		$("#search_list").val('');
		$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#table_container').load('<? echo $base; ?>index.php/go_voicemail_ce/go_update_voicemail_list/');
	});
	
	$("#search_list_button").click(function()
	{
		var search = $("#search_list").val();
		search = search.replace(/\s/g,"%20");
		if (search.length > 2) {
			$('#showAllLists').show();
			$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#table_container').load('<? echo $base; ?>index.php/go_voicemail_ce/go_update_voicemail_list/search/1/'+search);
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
			if ((event.which > 32 && event.which < 48) || (event.which == 32 && $(this).attr('id') != "statusName") || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.which == 13 && event.type == "keydown") {
			var search = $("#search_list").val();
			search = search.replace(/\s/g,"%20");
			if (search.length > 2) {
				$('#showAllLists').show();
				$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#table_container').load('<? echo $base; ?>index.php/go_voicemail_ce/go_update_voicemail_list/search/1/'+search);
			} else {
				alert("Please enter at least 3 characters to search.");
			}
		}
	});

	$('#add_voicemail').click(addNewVoicemail);
	
	// Tool Tip
	$(".toolTip").tipTip();
	
	$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#table_container').load('<? echo $base; ?>index.php/go_voicemail_ce/go_update_voicemail_list/');
});

function addNewVoicemail()
{
	$('#overlay').fadeIn('fast');
	$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#box').animate({
		top: "70px"
	}, 500);

	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_voicemail_ce/go_voicemail_wizard/').fadeIn("slow");
}

function modify(voicemail)
{
	$('#overlay').fadeIn('fast');
	$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
	$('#box').animate({
		top: "70px"
	}, 500);

	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_voicemail_ce/go_get_voicemail/view/'+voicemail).fadeIn("slow");
}

function delVoicemail(voicemail)
{
	var answer = confirm("Are you sure you want to delete "+voicemail+"?");
	
	if (answer)
	{
		$.post("<?=$base?>index.php/go_voicemail_ce/go_voicemail_wizard", { voicemail: voicemail, action: "delete_voicemail" },
		function(data){
			if (data=="DELETED")
			{
				alert("VOICEMAIL ENTRY "+data);
				location.reload();
			}
		});
	}
}
</script>
<div id='outbody' class="wrap">
<div id="icon-voicefile" class="icon32">
</div>
<div style="float: right;margin-top:15px;margin-right:25px;"><span id="showAllLists" style="display: none">[Clear Search]</span>&nbsp;<?=form_input('search_list',null,'id="search_list" maxlength="100" placeholder="Search '.$bannertitle.'"') ?>&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="search_list_button" style="cursor: pointer;" /></div>
<h2><? echo $bannertitle; ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

			<!-- START LEFT WIDGETS -->
			<div class="postbox-container" style="width:99%;min-width:1200px;">
				<div class="meta-box-sortables ui-sortable">

					<!-- GO WIDGET -->
					<div id="account_info_status" class="postbox">
						<div class="rightdiv toolTip" id="add_voicemail" title="Add New Voicemail">
                        	Add New Voicemail <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="hndle">
							<span><span id="title_bar" />&nbsp;</span><!-- Title Bar -->
								<span class="postbox-title-action"></span>
							</span>
						</div>
						<div class="inside">

                            <div style="margin:<?php echo (preg_match("/^Windows/",$userOS)) ? "-23px" : "-22px"; ?> 0px -2px -10px;" id="request_tab"><span id="showList" class="tabtoggle menuOn">Voicemails Boxes</span><span id="request" style="display:none;">showList</span></div>

							<div id="table_container" class="table_container">
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


							<?
echo "<div id='popup_analytics_all' class='popup_block'>\n";
?>

			<div class="table go_dashboard_analytics_in_popup_cmonth_daily" id="go_dashboard_analytics_in_popup_cmonth_daily">
			</div>

			<div class="table go_dashboard_analytics_in_popup_weekly" id="go_dashboard_analytics_in_popup_weekly">
			</div>

			<div class="table go_dashboard_analytics_in_popup_hourly" id="go_dashboard_analytics_in_popup_hourly">
			</div>

<?
echo "</div>\n";
?>

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

<!-- Debug-->
<div id="showDebug"></div>

<div id="hiddenToggle" style="visibility:hidden"></div>
<div id="wizardSpan" style="visibility:hidden">false</div>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
