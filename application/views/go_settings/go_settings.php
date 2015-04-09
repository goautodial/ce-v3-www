<?php
########################################################################################################
####  Name:             	go_settings.php                                                     ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
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

#closebox{
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
	text-align: left;
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

img.logo {
	vertical-align: middle;
	width: 164px;
	height: 64px;
}

img.login {
	vertical-align: middle;
	width: 130px;
	height: 40px;
}

table.theme_settings {
	width: 800px;
	-webkit-border-radius: 7px;
	-moz-border-radius: 7px;
	border-radius: 7px;
	border:1px solid #DFDFDF;
	margin:auto;
	padding:10px 20px;
}

.rightTD {
	text-align: right;
	font-weight: bold;
}

#updatetheme {
	cursor: pointer;
	border: 1px solid #BBB;
	color: #464646;
	background-color: #FFF;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	margin: 1px;
	padding: 3px;
	line-height: 15px;
	font-size: 13px;
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
	});
	
	$('.toolTip').tipTip();
});
</script>
<div id='outbody' class="wrap">
<div id="icon-settings" class="icon32">
</div>
<h2><? echo $bannertitle; ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

			<!-- START LEFT WIDGETS -->
			<div class="postbox-container" style="width:99%;min-width:1200px;">
				<div class="meta-box-sortables ui-sortable">

					<!-- GO WIDGET -->
					<div id="account_info_status" class="postbox">
						<div class="rightdiv toolTip" style="display: none;" id="search_logs" title="<? echo $this->lang->line('go_search_logs'); ?>">
                        	<? echo $this->lang->line('go_search_logs'); ?> <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="hndle">
							<span><span id="title_bar" />&nbsp;</span><!-- Title Bar -->
								<span class="postbox-title-action"></span>
							</span>
						</div>
						<div class="inside">

                            <div style="margin:<?php echo (preg_match("/^Windows/",$userOS)) ? "-23px" : "-22px"; ?> 0px -2px -10px;" id="request_tab"><span id="showServer" class="tabtoggle menuOn"><? echo $this->lang->line('go_server'); ?></span><span id="showTheme" class="tabtoggle hideSpan"><? echo $this->lang->line('go_theme'); ?></span><span id="request" style="display:none;"><? echo $this->lang->line('go_show_list'); ?></span></div>

							<div id="showServer_div" class="table_container">
							<?php
							$settings_path = config_item('VARWWWPATH') . "/application/views/go_systemsettings/index.php";
							include($settings_path);
							?>
							</div>
							
							<div id="showTheme_div" class="table_container hideSpan" style="width:100%;">
								<br />
								<table class="theme_settings">
									<tr>
										<td class="rightTD"><? echo $this->lang->line('go_comp_name'); ?>:</td>
										<td>&nbsp;<?=form_input('company_name',$server_settings[0]->company_name,'id="company_name" size="50" maxlength="100"')?></td>
									</tr>
									<tr>
										<td class="rightTD"><? echo $this->lang->line('go_comp_logo'); ?>:</td>
										<td>&nbsp;<?=form_upload('company_logo',null,'id="company_logo"')?> &nbsp;&nbsp;&nbsp;&nbsp; <img src="<?=$base?>login/<?=$server_settings[0]->company_logo?>" class="logo" /></td>
									</tr>
									<tr>
										<td class="rightTD"><? echo $this->lang->line('go_theme_color'); ?>:</td>
										<td>&nbsp;<?=form_input('theme_color',$server_settings[0]->theme_color,'id="theme_color" class="color" maxlength="50"')?></td>
									</tr>
									<tr>
										<td class="rightTD"><? echo $this->lang->line('go_login_color'); ?>:</td>
										<td>&nbsp;<?=form_input('login_color',$server_settings[0]->login_color,'id="login_color" class="color" maxlength="50"')?></td>
									</tr>
									<tr>
										<td class="rightTD"><? echo $this->lang->line('go_login_button'); ?>:</td>
										<td>&nbsp;<?=form_upload('login_button',null,'id="login_button"')?> &nbsp;&nbsp;&nbsp;&nbsp; <img src="<?=$base?>login/<?=$server_settings[0]->login_button?>" class="login" /></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: center"><?=form_button('updatetheme',' Submit ','id="updatetheme"')?></td>
									</tr>
								</table>
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
<a id="closebox" class="toolTip" title="<? echo strtoupper($this->lang->line('go_close')); ?>"></a>
<div id="overlayContent"></div>
</div>

<!-- Debug-->
<div id="showDebug"></div>

<div id="hiddenToggle" style="visibility:hidden"></div>
<div id="wizardSpan" style="visibility:hidden">false</div>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
