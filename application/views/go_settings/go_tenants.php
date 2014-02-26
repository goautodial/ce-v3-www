<?php
####################################################################################################
####  Name:             	go_tenants_ce.php                                                   ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
?>
<script src="<? echo $base; ?>js/jscolor/jscolor.js" type="text/javascript"></script>
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
	text-decoration:none;
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
#overlayTenants{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#overlay,#fileOverlay,#listOverlay,#overlayAgent{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:102;
}

#boxTenants{
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

#box,#fileBox,#listBox,#boxAgent{
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

#closebox, #closeboxTenants, #fileClosebox, #listClosebox, #closeboxAgent{
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

table.tablesorter tbody tr.odd td {
	background-color:#EFFBEF;
}

table.tablesorter tbody tr.even td {
	background-color:#E0F8E0;
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

	$('#closeboxTenants').click(function()
	{
		$('#boxTenants').animate({'top':'-2550px'},500);
		$('#overlayTenants').fadeOut('slow');
        $(".advance_settings").hide();
	});

	$('#closebox').click(function()
	{
		$('#box').animate({'top':'-2550px'},500);
		$('#overlay').fadeOut('slow');
	});

	$('#fileClosebox').click(function()
	{
		$('#fileBox').animate({'top':'-2550px'},500);
		$('#fileOverlay').fadeOut('slow');
	});

	$('#listClosebox').click(function()
	{
		$('#listBox').animate({'top':'-2550px'},500);
		$('#listOverlay').fadeOut('slow');
	});

	$('#closeboxAgent').click(function()
	{
		$('#boxAgent').animate({'top':'-2550px'},500);
		$('#overlayAgent').fadeOut('slow');
	});

	$('#add_tenant').click(addNewTenant);

	$('#saveUser').click(function() {
		var das_tenantID = $('#tenant_id_view').val();
		var das_userID = $('#users_id').val();
		var das_userPass = $('#users_pass').val();
		var das_userFname = $('#users_full_name').val();
		var das_userPlogin = $('#users_phone_login').val();
		var das_userPpass = $('#users_phone_pass').val();
		var das_userStatus = $('#users_status').val();

		$.post('<? echo $base; ?>index.php/go_site/go_update_user_info',{user_id:das_userID,pass:das_userPass,full_name:das_userFname,phone_login:das_userPlogin,phone_pass:das_userPpass,active:das_userStatus},function(data,status) {
			alert(data);
			$('#boxAgent').animate({'top':'-550px'},500);
			$('#overlayAgent').fadeOut('slow');

			$("#overlayContentTenants").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#overlayContentTenants').fadeOut("slow").load('<? echo $base; ?>index.php/go_tenants_ce/go_get_tenant/view/'+das_tenantID).fadeIn("slow");
		});
	});
	
	$("#showAllLists").click(function()
	{
		$(this).hide();
		$("#search_list").val('');
		$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#table_container').load('<? echo $base; ?>index.php/go_tenants_ce/go_update_tenants_list/');
	});
	
	$("#search_list_button").click(function()
	{
		$('#showAllLists').show();
		var search = $("#search_list").val()
		search = search.replace(/\s/g,"%20");
		if (search.length > 2) {
			$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#table_container').load('<? echo $base; ?>index.php/go_tenants_ce/go_update_tenants_list/search/1/'+search);
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
				$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#table_container').load('<? echo $base; ?>index.php/go_tenants_ce/go_update_tenants_list/search/1/'+search);
			} else {
				alert("Please enter at least 3 characters to search.");
			}
		}
	});
	
	// Tool Tip
	$(".toolTip").tipTip();
	
	$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#table_container').load('<? echo $base; ?>index.php/go_tenants_ce/go_update_tenants_list/');
});

function addNewTenant()
{
	$('#overlay').fadeIn('fast');
	$('#box').css({'width': '760px','margin-left': '-10%', 'margin-right': '10%', 'padding-bottom': '10px'});
	$('#box').animate({
		top: "70px"
	}, 500);

	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_tenants_ce/go_tenants_wizard/').fadeIn("slow");
}

function modify(tenant)
{
	$('#overlayTenants').fadeIn('fast');
	$('#boxTenants').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
	$('#boxTenants').animate({
		top: "70px"
	}, 500);

	$('#tenant_id_view').val(tenant);
	$("html, body").animate({ scrollTop: 0 }, 500);
	$("#overlayContentTenants").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContentTenants').fadeOut("slow").load('<? echo $base; ?>index.php/go_tenants_ce/go_get_tenant/view/'+tenant).fadeIn("slow");
}

function delTenant(tenant)
{
	var answer = confirm("WARNING! Are you sure you want to delete "+tenant+"?\n\nThis will DELETE ALL entries under this tenant.\n> Users\n> Campaigns\n> List IDs\n> Phones\n> Leads uploaded\n\nClick OK to continue.");
	
	if (answer)
	{
		$.post("<?=$base?>index.php/go_tenants_ce/go_tenants_wizard", { tenantid: tenant, action: "delete_tenant" },
		function(data){
			if (data=="DELETED")
			{
				alert("TENANT ENTRY "+data);
				location.reload();
			}
		});
	}
}

function modifyCampaignTenant(camp)
{
	$('#overlay').fadeIn('fast');
	$('#box').css({'width': '860px', 'left': '14%', 'right': '14%', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
	$('#box').animate({
		top: "70px"
	}, 500);
	
	$("html, body").animate({ scrollTop: 0 }, 500);
	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_settings/'+camp).fadeIn("slow");
}

function modifyListIDTenant(list_id)
{
	var isAdvance = $('#isAdvance').val();
	var campaign_id = $('#campaign_id').val();
	$('#overlay').fadeIn('fast');
	$('#box').css({'width': '660px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#box').animate({
		top: "70px",
		left: "14%",
		right: "14%"
	}, 500);

	$("html, body").animate({ scrollTop: 0 }, 500);
	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_get_listid/'+list_id+'/'+isAdvance).fadeIn("slow");
}

function modifyPhonesTenant(phone)
{
	$('#overlay').fadeIn('fast');
	$('#box').css({'width': '660px', 'margin-left': '48.5%', 'left': '-330px', 'padding-bottom': '10px'});
	$('#box').animate({
		top: "70px"
	}, 500);

	$("html, body").animate({ scrollTop: 0 }, 500);
	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_phones_ce/go_get_phone/view/'+phone).fadeIn("slow");
}

function modifyAgentTenant(das_userID)
{
    $('#boxAgent').show();
    $('#boxAgent').css({'width': '500px', 'margin-left': '50%', 'left': '-250px', 'padding-bottom': '20px'});
    $('#boxAgent').animate({
	top: Math.max(0, (($(window).height() - $('#boxAgent').outerHeight()) / 2) + $(window).scrollTop()) + "px"
	// top: "70px"
    }, 500);
    $('#overlayAgent').fadeIn('fast');
	$.post('<? echo $base; ?>index.php/go_site/go_get_user_info/'+das_userID, function(data,status) {
		var data_array = data.split('|');
		$('#users_id').val(data_array[0]);
		$('#users_id_span').text(data_array[0]);
		$('#users_pass').val(data_array[1]);
		$('#users_full_name').val(data_array[2]);
		$('#users_phone_login').val(data_array[3]);
		$('#users_phone_pass').val(data_array[4]);
		$('#users_status').val(data_array[5]);
	});
}

function changePage(pagenum)
{
	var search = $("#search_list").val();
	search = search.replace(/\s/g,"%20");
	$("#table_container").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#table_container').load('<? echo $base; ?>index.php/go_tenants_ce/go_update_tenants_list/search/'+pagenum+'/'+search);
}
</script>
<div id='outbody' class="wrap">
<div id="icon-tenants" class="icon32">
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
						<div class="rightdiv toolTip" id="add_tenant" title="Add New Tenant">
                        	Add New Tenant <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="hndle">
							<span><span id="title_bar" />&nbsp;</span><!-- Title Bar -->
								<span class="postbox-title-action"></span>
							</span>
						</div>
						<div class="inside">

                            <div style="margin:<?php echo (preg_match("/^Windows/",$userOS)) ? "-23px" : "-22px"; ?> 0px -2px -10px;" id="request_tab"><span id="showList" class="tabtoggle menuOn">Tenants</span><span id="request" style="display:none;">showList</span></div>

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

<!-- Overlay2 -->
<div id="overlayTenants" style="display:none;"></div>
<div id="boxTenants">
<a id="closeboxTenants" class="toolTip" title="CLOSE"></a>
<div id="overlayContentTenants"></div>
</div>

<!-- Overlay3 -->
<div id="listOverlay" style="display:none;"></div>
<div id="listBox">
<a id="listClosebox" class="toolTip" title="CLOSE"></a>
<div id="listOverlayContent"></div>
</div>

<!-- File Overlay -->
<span id="audioButtonClicked" style="display:none;"></span>
<div id="fileOverlay" style="display:none;"></div>
<div id="fileBox">
<a id="fileClosebox" class="toolTip" title="CLOSE"></a>
<div id="fileOverlayContent">
<table style="width: 100%">
	<tr>
		<td>List of Files Uploaded:</td>
	</tr>
<?php
$WeBServeRRooT = '/var/lib/asterisk';
$storage = 'sounds';
if(is_dir($WeBServeRRooT.'/'.$storage)){
	if ($handle = opendir($WeBServeRRooT.'/'.$storage)) {
		$ctr = 0;
		while (false !== ($file = readdir($handle))) {
			if($file != '..' && $file != "."){
				$prefix = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "go_".$this->session->userdata('user_group')."_" : "go_";
				if(preg_match("/^$prefix/",$file)){
					$fname = str_replace(".wav","",$file);
					if ($ctr % 2 == 0)
						echo "<tr>";
					
					echo "<td style='white-space:nowrap;width:50%'>&raquo; <a id='$ctr' href='#'>$fname</a>\n";
					echo "<script>
							$('#$ctr').click(function (){
								var input_id = $('#audioButtonClicked').text();
								$('input#'+input_id+'_afterhours_filename_override').val($('#$ctr').text());
							});
						</script>";
					echo "&nbsp;&nbsp;</td>";
					
					if ($ctr % 2 == 1)
						echo "</tr>\n";
					
					$ctr++;
				}
			}
		}
		closedir($handle);
	}
}
?>
</table>
</div>
</div>

<!-- Overlay for Agent View/Edit -->
<div id="overlayAgent" style="display:none;"></div>
<div id="boxAgent" style="display:none;">
<a id="closeboxAgent" class="toolTip" title="CLOSE"></a>
<div id="overlayContentAgent">
<div style="text-align:center;font-weight:bold;font-size:14px;">Modify User</div>
<br />
<?=form_hidden('tenant_id_view',null,'id="tenant_id_view"'); ?>
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:100%;">
    <tr>
    	<td style="text-align:right;font-weight:bold;width:40%" nowrap>Agent ID:</td><td>&nbsp;<span id="users_id_span"></span><input type="hidden" id="users_id" name="users_id" value="" /></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Password:</td><td><input type="text" id="users_pass" name="users_pass" value="" maxlength="10" /></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Full Name:</td><td><input type="text" id="users_full_name" name="users_full_name" value="" /></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Phone Login:</td><td><input type="text" id="users_phone_login" name="users_phone_login" value="" readonly="readonly" /></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Phone Pass:</td><td><input type="text" id="users_phone_pass" name="users_phone_pass" value="" maxlength="10" /></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Active:</td><td><select id="users_status"><option>N</option><option>Y</option></select></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>&nbsp;</td><td><input type="button" id="saveUser" name="saveUser" value="SUBMIT" style="cursor:pointer;" /></td>
    </tr>
</table>
</div>
</div>
<!-- Overlay for Agent View/Edit -->

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
